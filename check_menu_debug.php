<?php
/**
 * Menu Debug Tool
 * Access this file directly: http://localhost/rhemazimbabwe/check_menu_debug.php
 * This will show you what's in your database and help fix the menu
 */

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'ssdb';

// Connect to database
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<html><head><title>Menu Debug Tool</title>";
echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
.container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
h1 { color: #333; border-bottom: 3px solid #3c8dbc; padding-bottom: 10px; }
h2 { color: #3c8dbc; margin-top: 30px; }
table { width: 100%; border-collapse: collapse; margin: 20px 0; }
th { background: #3c8dbc; color: white; padding: 10px; text-align: left; }
td { padding: 8px; border-bottom: 1px solid #ddd; }
tr:hover { background: #f9f9f9; }
.success { color: green; font-weight: bold; }
.error { color: red; font-weight: bold; }
.warning { color: orange; font-weight: bold; }
.btn { display: inline-block; padding: 10px 20px; background: #3c8dbc; color: white; text-decoration: none; border-radius: 3px; margin: 10px 5px; }
.btn:hover { background: #357ca5; }
.info { background: #d9edf7; padding: 15px; border-left: 4px solid #31b0d5; margin: 20px 0; }
</style></head><body>";

echo "<div class='container'>";
echo "<h1>🔍 Partners Menu Debug Tool</h1>";

// 1. Check Partners main menu
echo "<h2>1. Partners Main Menu</h2>";
$sql = "SELECT id, menu, access_permissions, is_active, sidebar_display FROM sidebar_menus WHERE menu LIKE '%Partner%' OR lang_key LIKE '%partner%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Menu</th><th>Access Permissions</th><th>Active</th><th>Sidebar Display</th></tr>";
    while($row = $result->fetch_assoc()) {
        $status = $row['is_active'] == 1 ? "<span class='success'>✓ Active</span>" : "<span class='error'>✗ Inactive</span>";
        $display = $row['sidebar_display'] == 1 ? "<span class='success'>✓ Yes</span>" : "<span class='error'>✗ No</span>";
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td><strong>{$row['menu']}</strong></td>";
        echo "<td>{$row['access_permissions']}</td>";
        echo "<td>{$status}</td>";
        echo "<td>{$display}</td>";
        echo "</tr>";
    }
    echo "</table>";

    $partners_menu_id = $conn->query("SELECT id FROM sidebar_menus WHERE menu = 'Partners' LIMIT 1")->fetch_assoc()['id'];
    echo "<div class='info'>✓ Partners main menu ID: <strong>{$partners_menu_id}</strong></div>";
} else {
    echo "<p class='error'>✗ No Partners menu found!</p>";
    $partners_menu_id = 40; // default
}

// 2. Check Partners submenus
echo "<h2>2. Partners Submenus</h2>";
$sql = "SELECT id, sidebar_menu_id, menu, `key`, url, level, is_active, access_permissions
        FROM sidebar_sub_menus
        WHERE sidebar_menu_id = {$partners_menu_id}
        ORDER BY level";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Menu</th><th>Key</th><th>URL</th><th>Level</th><th>Active</th></tr>";
    $has_giving_types = false;
    while($row = $result->fetch_assoc()) {
        $status = $row['is_active'] == 1 ? "<span class='success'>✓</span>" : "<span class='error'>✗</span>";
        if ($row['key'] == 'giving_types') $has_giving_types = true;

        $highlight = ($row['key'] == 'giving_types') ? "style='background: #d4edda;'" : "";

        echo "<tr {$highlight}>";
        echo "<td>{$row['id']}</td>";
        echo "<td><strong>{$row['menu']}</strong></td>";
        echo "<td>{$row['key']}</td>";
        echo "<td>{$row['url']}</td>";
        echo "<td>{$row['level']}</td>";
        echo "<td>{$status}</td>";
        echo "</tr>";
    }
    echo "</table>";

    if ($has_giving_types) {
        echo "<div class='info success'>✓ Giving Types menu EXISTS in database</div>";
    } else {
        echo "<div class='info error'>✗ Giving Types menu NOT FOUND in database</div>";
    }
} else {
    echo "<p class='error'>✗ No submenus found under Partners!</p>";
}

// 3. Specific check for Giving Types
echo "<h2>3. Giving Types Menu Status</h2>";
$sql = "SELECT * FROM sidebar_sub_menus WHERE `key` = 'giving_types'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "<table>";
    echo "<tr><th>Field</th><th>Value</th></tr>";
    foreach ($row as $key => $value) {
        echo "<tr><td><strong>{$key}</strong></td><td>{$value}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p class='error'>✗ Giving Types menu does not exist</p>";
    echo "<div class='info warning'>";
    echo "<strong>ACTION REQUIRED:</strong> Click the button below to create the Giving Types menu";
    echo "</div>";
}

// 4. Fix button
echo "<h2>4. Quick Fix</h2>";
echo "<form method='POST' style='margin: 20px 0;'>";
echo "<button type='submit' name='fix_menu' class='btn'>🔧 Fix Giving Types Menu Now</button>";
echo "<button type='submit' name='activate_all' class='btn' style='background: #f39c12;'>⚡ Activate All Partner Menus</button>";
echo "</form>";

// Handle fix action
if (isset($_POST['fix_menu'])) {
    echo "<div style='background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0;'>";
    echo "<h3>Fixing Menu...</h3>";

    // Delete existing
    $conn->query("DELETE FROM sidebar_sub_menus WHERE `key` = 'giving_types'");
    echo "<p>1. Deleted old menu entry (if any)</p>";

    // Insert new (with NULL permission_group_id to avoid Module_lib error)
    $sql = "INSERT INTO sidebar_sub_menus
            (sidebar_menu_id, menu, `key`, lang_key, url, level, access_permissions, permission_group_id, activate_controller, activate_methods, addon_permission, is_active)
            VALUES
            ({$partners_menu_id}, 'Giving Types', 'giving_types', 'giving_types', 'admin/givingtypes', 4, '(''partners'', ''can_view'')', NULL, 'givingtypes', 'index,add,edit,show,delete', '', 1)";

    if ($conn->query($sql)) {
        echo "<p class='success'>2. ✓ Giving Types menu created successfully!</p>";
        echo "<p><strong>Next Steps:</strong></p>";
        echo "<ol>";
        echo "<li>Clear your browser cache (Ctrl + Shift + Delete)</li>";
        echo "<li>Logout from admin panel</li>";
        echo "<li>Login again</li>";
        echo "<li>Check the Partners menu in sidebar</li>";
        echo "</ol>";
    } else {
        echo "<p class='error'>2. ✗ Error: " . $conn->error . "</p>";
    }
    echo "</div>";

    // Refresh to show new data
    echo "<script>setTimeout(function(){ window.location.reload(); }, 2000);</script>";
}

// Handle activate all action
if (isset($_POST['activate_all'])) {
    echo "<div style='background: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 20px 0;'>";
    echo "<h3>Activating All Partner Menus...</h3>";

    $sql = "UPDATE sidebar_sub_menus SET is_active = 1 WHERE sidebar_menu_id = {$partners_menu_id}";
    if ($conn->query($sql)) {
        echo "<p class='success'>✓ All partner menus activated!</p>";
    }

    echo "</div>";
    echo "<script>setTimeout(function(){ window.location.reload(); }, 2000);</script>";
}

// 5. Check permissions
echo "<h2>5. Permission Check</h2>";
$sql = "SELECT * FROM permission_category WHERE short_code = 'partners' OR short_code LIKE '%giving%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Name</th><th>Short Code</th><th>View</th><th>Add</th><th>Edit</th><th>Delete</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['name']}</td>";
        echo "<td><code>{$row['short_code']}</code></td>";
        echo "<td>" . ($row['enable_view'] ? "✓" : "✗") . "</td>";
        echo "<td>" . ($row['enable_add'] ? "✓" : "✗") . "</td>";
        echo "<td>" . ($row['enable_edit'] ? "✓" : "✗") . "</td>";
        echo "<td>" . ($row['enable_delete'] ? "✓" : "✗") . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<hr style='margin: 40px 0;'>";
echo "<p><a href='admin/givingtypes' class='btn'>→ Go to Giving Types</a></p>";
echo "<p style='color: #666; font-size: 12px;'>Debug Tool - Delete this file after fixing the menu</p>";

echo "</div></body></html>";

$conn->close();
?>
