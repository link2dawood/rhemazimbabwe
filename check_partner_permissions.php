<?php
/**
 * Partner Permissions Diagnostic Tool
 * Use this to check why permissions aren't showing in partner sidebar
 */

require_once('db.php'); // Assumes you have a db connection file

// Configuration - Change these values
$PARTNER_EMAIL = 'test@example.com'; // Change to your test partner's email
$PARTNER_ID = null; // Or set partner ID directly if you know it

echo "<h1>Partner Permissions Diagnostic Tool</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    .success { color: green; font-weight: bold; }
    .error { color: red; font-weight: bold; }
    .warning { color: orange; font-weight: bold; }
    table { border-collapse: collapse; width: 100%; background: white; margin: 10px 0; }
    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    th { background: #3c8dbc; color: white; }
    .box { background: white; padding: 20px; margin: 20px 0; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    h2 { color: #3c8dbc; border-bottom: 2px solid #3c8dbc; padding-bottom: 10px; }
    pre { background: #f9f9f9; padding: 15px; border-left: 4px solid #3c8dbc; overflow-x: auto; }
    .check { color: green; }
    .cross { color: red; }
</style>";

try {
    // Step 1: Find the partner
    echo "<div class='box'>";
    echo "<h2>Step 1: Finding Partner</h2>";
    
    if ($PARTNER_ID) {
        $partner_query = "SELECT * FROM partners WHERE id = $PARTNER_ID";
    } else {
        $partner_query = "SELECT * FROM partners WHERE email = '$PARTNER_EMAIL'";
    }
    
    $partner = $conn->query($partner_query)->fetch_assoc();
    
    if (!$partner) {
        echo "<p class='error'>✗ Partner not found!</p>";
        echo "<p>Email searched: $PARTNER_EMAIL</p>";
        exit;
    }
    
    echo "<p class='success'>✓ Partner found!</p>";
    echo "<table>";
    echo "<tr><th>Field</th><th>Value</th></tr>";
    echo "<tr><td>ID</td><td><strong>{$partner['id']}</strong></td></tr>";
    echo "<tr><td>Partner Code</td><td><strong>{$partner['partner_code']}</strong></td></tr>";
    echo "<tr><td>Name</td><td>{$partner['firstname']} {$partner['lastname']}</td></tr>";
    echo "<tr><td>Email</td><td>{$partner['email']}</td></tr>";
    echo "<tr><td>Status</td><td><span style='color: " . ($partner['status'] == 'active' ? 'green' : 'red') . "'>{$partner['status']}</span></td></tr>";
    echo "<tr><td>Has Password</td><td>" . (!empty($partner['password']) ? '<span class="check">✓ Yes</span>' : '<span class="cross">✗ No</span>') . "</td></tr>";
    echo "</table>";
    echo "</div>";
    
    $partner_id = $partner['id'];
    
    // Step 2: Check available permission types
    echo "<div class='box'>";
    echo "<h2>Step 2: Available Permission Types in System</h2>";
    $perm_types_query = "SELECT * FROM partner_permission_types WHERE is_active = 1 ORDER BY sort_order";
    $perm_types_result = $conn->query($perm_types_query);
    
    echo "<table>";
    echo "<tr><th>ID</th><th>Permission Name</th><th>Permission Code</th><th>Description</th></tr>";
    
    $available_codes = [];
    while ($ptype = $perm_types_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$ptype['id']}</td>";
        echo "<td><strong>{$ptype['permission_name']}</strong></td>";
        echo "<td><code>{$ptype['permission_code']}</code></td>";
        echo "<td>{$ptype['description']}</td>";
        echo "</tr>";
        $available_codes[] = $ptype['permission_code'];
    }
    echo "</table>";
    echo "<p><strong>Total available permission types: </strong>" . count($available_codes) . "</p>";
    echo "<p><strong>Codes: </strong>" . implode(', ', $available_codes) . "</p>";
    echo "</div>";
    
    // Step 3: Check granted permissions
    echo "<div class='box'>";
    echo "<h2>Step 3: Permissions Granted to This Partner</h2>";
    $granted_query = "SELECT pp.*, ppt.permission_name 
                      FROM partner_permissions pp
                      LEFT JOIN partner_permission_types ppt ON ppt.permission_code = pp.permission_code
                      WHERE pp.partner_id = $partner_id 
                      AND pp.is_granted = 1";
    $granted_result = $conn->query($granted_query);
    
    if ($granted_result->num_rows == 0) {
        echo "<p class='error'>✗ No permissions granted to this partner!</p>";
        echo "<p class='warning'>Admin needs to grant permissions at: <code>admin/partners/permissions/{$partner_id}</code></p>";
    } else {
        echo "<p class='success'>✓ {$granted_result->num_rows} permission(s) granted</p>";
        echo "<table>";
        echo "<tr><th>Permission Name</th><th>Permission Code</th><th>Granted At</th><th>Will Show in Sidebar?</th></tr>";
        
        $granted_codes = [];
        while ($gperm = $granted_result->fetch_assoc()) {
            $granted_codes[] = $gperm['permission_code'];
            $in_sidebar = in_array($gperm['permission_code'], ['library', 'online_courses', 'download_centre', 'gmeet', 'zoom', 'events_access']);
            
            echo "<tr>";
            echo "<td><strong>{$gperm['permission_name']}</strong></td>";
            echo "<td><code>{$gperm['permission_code']}</code></td>";
            echo "<td>{$gperm['granted_at']}</td>";
            echo "<td>" . ($in_sidebar ? '<span class="check">✓ YES</span>' : '<span class="cross">✗ NO (not in sidebar menu)</span>') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<p><strong>Granted permission codes: </strong>" . implode(', ', $granted_codes) . "</p>";
    }
    echo "</div>";
    
    // Step 4: Sidebar Menu Check
    echo "<div class='box'>";
    echo "<h2>Step 4: What Will Show in Partner Sidebar</h2>";
    
    $sidebar_permissions = ['library', 'online_courses', 'download_centre', 'gmeet', 'zoom', 'events_access'];
    
    echo "<table>";
    echo "<tr><th>Permission Code</th><th>Menu Label</th><th>Granted?</th><th>Will Display?</th></tr>";
    
    $menu_labels = [
        'library' => 'Library',
        'online_courses' => 'Online Courses',
        'download_centre' => 'Download Centre',
        'gmeet' => 'Google Meet',
        'zoom' => 'Zoom',
        'events_access' => 'Events & Calendar'
    ];
    
    foreach ($sidebar_permissions as $code) {
        $is_granted = in_array($code, $granted_codes);
        echo "<tr>";
        echo "<td><code>$code</code></td>";
        echo "<td><strong>{$menu_labels[$code]}</strong></td>";
        echo "<td>" . ($is_granted ? '<span class="check">✓ YES</span>' : '<span class="cross">✗ NO</span>') . "</td>";
        echo "<td>" . ($is_granted ? '<span class="success">✓ Will show in sidebar</span>' : '<span class="error">✗ Won\'t show</span>') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
    
    // Step 5: SQL to grant all permissions
    echo "<div class='box'>";
    echo "<h2>Step 5: Quick Fix - Grant All Permissions</h2>";
    echo "<p>Run this SQL to grant ALL permissions to this partner:</p>";
    echo "<pre>";
    echo "DELETE FROM partner_permissions WHERE partner_id = $partner_id;\n\n";
    echo "INSERT INTO partner_permissions (partner_id, permission_code, is_granted, granted_by, granted_at)\nVALUES\n";
    
    $values = [];
    foreach ($available_codes as $code) {
        $values[] = "($partner_id, '$code', 1, 1, NOW())";
    }
    echo implode(",\n", $values);
    echo ";";
    echo "</pre>";
    echo "</div>";
    
    // Step 6: Summary
    echo "<div class='box'>";
    echo "<h2>Summary</h2>";
    echo "<ul>";
    echo "<li><strong>Partner ID:</strong> $partner_id</li>";
    echo "<li><strong>Partner Code:</strong> {$partner['partner_code']}</li>";
    echo "<li><strong>Total Permissions Granted:</strong> " . count($granted_codes) . "</li>";
    echo "<li><strong>Permissions that will show in sidebar:</strong> " . count(array_intersect($granted_codes, $sidebar_permissions)) . "</li>";
    echo "</ul>";
    
    echo "<h3>Next Steps:</h3>";
    echo "<ol>";
    echo "<li>Login as admin</li>";
    echo "<li>Go to: <code>admin/partners/permissions/$partner_id</code></li>";
    echo "<li>Check the permissions you want to grant</li>";
    echo "<li>Click 'Save Permissions'</li>";
    echo "<li>Partner logs out and back in to see new menu items</li>";
    echo "</ol>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='box'>";
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
    echo "<p>Make sure the database connection is configured in db.php</p>";
    echo "</div>";
}
?>

