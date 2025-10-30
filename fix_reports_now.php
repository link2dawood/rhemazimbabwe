<?php
/**
 * Partner Reports Permission Fix Tool
 * Run this once: http://localhost/rhemazimbabwe/fix_reports_now.php
 */

$conn = new mysqli('localhost', 'root', '', 'ssdb');

if ($conn->connect_error) {
    die("Connection failed");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Fix Partner Reports</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); }
        h1 { color: #333; border-bottom: 3px solid #667eea; padding-bottom: 10px; }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .info { background: #e7f3ff; padding: 15px; border-left: 4px solid #2196F3; margin: 20px 0; border-radius: 4px; }
        .success-box { background: #d4edda; padding: 20px; border-left: 4px solid #28a745; margin: 20px 0; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { background: #667eea; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        .btn { display: inline-block; padding: 15px 30px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; border: none; cursor: pointer; font-size: 16px; font-weight: bold; }
        .btn:hover { background: #218838; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔧 Partner Reports Permission Fix</h1>

    <?php
    // Check if permission exists
    $result = $conn->query("SELECT * FROM permission_category WHERE short_code = 'partner_reports'");
    $permission_exists = $result->num_rows > 0;

    if (!$permission_exists && !isset($_POST['run_fix'])) {
        // Permission doesn't exist - show diagnosis
        echo "<div class='info'>";
        echo "<h3>❌ Problem Detected</h3>";
        echo "<p><strong>The 'partner_reports' permission does not exist in the database.</strong></p>";
        echo "<p>This is why your reports are showing 'no data available'.</p>";
        echo "<p>The system is blocking access because it can't find the permission.</p>";
        echo "</div>";

        // Show current state
        echo "<h3>Current State:</h3>";
        echo "<table>";
        echo "<tr><th>Check</th><th>Status</th></tr>";
        echo "<tr><td>partner_reports permission</td><td class='error'>✗ Missing</td></tr>";

        $result = $conn->query("SELECT COUNT(*) as count FROM partners WHERE is_active = 1");
        $row = $result->fetch_assoc();
        echo "<tr><td>Active Partners</td><td class='success'>✓ {$row['count']} found</td></tr>";

        $result = $conn->query("SELECT COUNT(*) as count FROM partner_contributions");
        $row = $result->fetch_assoc();
        echo "<tr><td>Contributions</td><td class='success'>✓ {$row['count']} found</td></tr>";
        echo "</table>";

        echo "<form method='POST'>";
        echo "<button type='submit' name='run_fix' class='btn'>🔧 Fix It Now!</button>";
        echo "</form>";

    } elseif (isset($_POST['run_fix'])) {
        // Run the fix
        echo "<h2>Running Fix...</h2>";

        // Step 1: Get permission group ID
        $result = $conn->query("SELECT perm_group_id FROM permission_category WHERE short_code = 'partners' LIMIT 1");
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $perm_group_id = $row['perm_group_id'];

            echo "<p class='success'>✓ Step 1: Found permission group ID: {$perm_group_id}</p>";

            // Step 2: Add permission_category entry
            $sql = "INSERT INTO permission_category (perm_group_id, name, short_code, enable_view, enable_add, enable_edit, enable_delete, created_at)
                    VALUES ({$perm_group_id}, 'Partner Reports', 'partner_reports', 1, 0, 0, 0, NOW())";

            if ($conn->query($sql)) {
                echo "<p class='success'>✓ Step 2: Created 'partner_reports' permission</p>";

                $partner_reports_id = $conn->insert_id;
                echo "<p class='info'>New permission ID: {$partner_reports_id}</p>";

                // Step 3: Get partner permission ID
                $result = $conn->query("SELECT id FROM permission_category WHERE short_code = 'partners'");
                $row = $result->fetch_assoc();
                $partners_perm_id = $row['id'];

                echo "<p class='success'>✓ Step 3: Found 'partners' permission ID: {$partners_perm_id}</p>";

                // Step 4: Copy permissions from partners to partner_reports
                $sql = "INSERT INTO roles_permissions (role_id, perm_cat_id, can_view, can_add, can_edit, can_delete)
                        SELECT role_id, {$partner_reports_id}, can_view, 0, 0, 0
                        FROM roles_permissions
                        WHERE perm_cat_id = {$partners_perm_id}
                        ON DUPLICATE KEY UPDATE can_view = VALUES(can_view)";

                if ($conn->query($sql)) {
                    echo "<p class='success'>✓ Step 4: Granted permissions to roles</p>";

                    // Show which roles got permission
                    $result = $conn->query("
                        SELECT r.name, rp.can_view
                        FROM roles_permissions rp
                        JOIN roles r ON r.id = rp.role_id
                        WHERE rp.perm_cat_id = {$partner_reports_id}
                    ");

                    echo "<h4>Roles with partner_reports permission:</h4>";
                    echo "<table>";
                    echo "<tr><th>Role</th><th>Can View</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['name']}</td>";
                        echo "<td>" . ($row['can_view'] ? '✓ Yes' : '✗ No') . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";

                    // Success message
                    echo "<div class='success-box'>";
                    echo "<h2>✅ Fix Completed Successfully!</h2>";
                    echo "<h3>Next Steps:</h3>";
                    echo "<ol>";
                    echo "<li><strong>Clear browser cache:</strong> Press Ctrl + Shift + Delete</li>";
                    echo "<li><strong>Logout</strong> from the admin panel</li>";
                    echo "<li><strong>Login</strong> again</li>";
                    echo "<li><strong>Test the reports:</strong><ul>";
                    echo "<li><a href='admin/partnerreports/partner_information'>Partner Information Report</a></li>";
                    echo "<li><a href='admin/partnerreports/giving_collection_by_type'>Giving Collection By Type</a></li>";
                    echo "<li><a href='admin/partnerreports/partner_statement'>Partner Statement</a></li>";
                    echo "<li><a href='admin/partnerreports/balance_giving_report'>Balance Giving Report</a></li>";
                    echo "</ul></li>";
                    echo "</ol>";
                    echo "<p><strong>Delete this file after confirming reports work!</strong></p>";
                    echo "</div>";

                } else {
                    echo "<p class='error'>✗ Step 4 Failed: " . $conn->error . "</p>";
                }

            } else {
                // Check if error is because it already exists
                if (strpos($conn->error, 'Duplicate entry') !== false) {
                    echo "<p class='success'>✓ Permission already exists (maybe created by previous run)</p>";

                    // Get the ID and continue with role permissions
                    $result = $conn->query("SELECT id FROM permission_category WHERE short_code = 'partner_reports'");
                    $row = $result->fetch_assoc();
                    $partner_reports_id = $row['id'];

                    $result = $conn->query("SELECT id FROM permission_category WHERE short_code = 'partners'");
                    $row = $result->fetch_assoc();
                    $partners_perm_id = $row['id'];

                    $sql = "INSERT INTO roles_permissions (role_id, perm_cat_id, can_view, can_add, can_edit, can_delete)
                            SELECT role_id, {$partner_reports_id}, can_view, 0, 0, 0
                            FROM roles_permissions
                            WHERE perm_cat_id = {$partners_perm_id}
                            ON DUPLICATE KEY UPDATE can_view = VALUES(can_view)";

                    $conn->query($sql);
                    echo "<p class='success'>✓ Updated role permissions</p>";
                    echo "<p><strong>Clear cache, logout, login, and try again!</strong></p>";

                } else {
                    echo "<p class='error'>✗ Step 2 Failed: " . $conn->error . "</p>";
                }
            }

        } else {
            echo "<p class='error'>✗ Error: Could not find 'partners' permission</p>";
        }

    } else {
        // Permission exists
        echo "<div class='success-box'>";
        echo "<h3>✓ Permission Already Exists</h3>";
        echo "<p>The 'partner_reports' permission is already in the database.</p>";
        echo "</div>";

        // Show details
        $result = $conn->query("SELECT * FROM permission_category WHERE short_code = 'partner_reports'");
        $perm = $result->fetch_assoc();

        echo "<h4>Permission Details:</h4>";
        echo "<table>";
        echo "<tr><th>Property</th><th>Value</th></tr>";
        echo "<tr><td>ID</td><td>{$perm['id']}</td></tr>";
        echo "<tr><td>Name</td><td>{$perm['name']}</td></tr>";
        echo "<tr><td>Short Code</td><td>{$perm['short_code']}</td></tr>";
        echo "<tr><td>Permission Group ID</td><td>{$perm['perm_group_id']}</td></tr>";
        echo "<tr><td>Enable View</td><td>" . ($perm['enable_view'] ? 'Yes' : 'No') . "</td></tr>";
        echo "</table>";

        // Check role assignments
        $result = $conn->query("
            SELECT r.name, rp.can_view
            FROM roles_permissions rp
            JOIN roles r ON r.id = rp.role_id
            WHERE rp.perm_cat_id = {$perm['id']}
        ");

        echo "<h4>Roles with Access:</h4>";
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Role</th><th>Can View</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>{$row['name']}</td><td>" . ($row['can_view'] ? '✓' : '✗') . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='error'>⚠️ No roles have permission! This might be the problem.</p>";
            echo "<form method='POST'>";
            echo "<button type='submit' name='run_fix' class='btn'>Grant Permissions to Roles</button>";
            echo "</form>";
        }

        echo "<h4>If Reports Still Don't Work:</h4>";
        echo "<ol>";
        echo "<li>Clear browser cache (Ctrl + Shift + Delete)</li>";
        echo "<li>Logout and login again</li>";
        echo "<li>Check browser console (F12) for JavaScript errors</li>";
        echo "<li>Verify your user account has a role with can_view = 1</li>";
        echo "</ol>";
    }

    $conn->close();
    ?>

    <hr style="margin-top: 40px;">
    <p style="color: #666; font-size: 12px; text-align: center;">
        Fix Tool - Delete after reports are working
    </p>
</div>
</body>
</html>
