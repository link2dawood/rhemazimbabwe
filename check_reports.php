<?php
/**
 * Partner Reports Checker
 * Access: http://localhost/rhemazimbabwe/check_reports.php
 * This will check if all reports are properly configured
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

?>
<!DOCTYPE html>
<html>
<head>
    <title>Partner Reports Checker</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 3px solid #3c8dbc; padding-bottom: 10px; }
        h2 { color: #3c8dbc; margin-top: 30px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th { background: #3c8dbc; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        tr:hover { background: #f9f9f9; }
        .status-ok { background: #d4edda; padding: 5px 10px; border-radius: 3px; }
        .status-error { background: #f8d7da; padding: 5px 10px; border-radius: 3px; }
        .info { background: #d9edf7; padding: 15px; border-left: 4px solid #31b0d5; margin: 20px 0; }
        .btn { display: inline-block; padding: 10px 20px; background: #3c8dbc; color: white; text-decoration: none; border-radius: 3px; margin: 5px; }
        .btn:hover { background: #357ca5; }
    </style>
</head>
<body>
<div class="container">
    <h1>📊 Partner Reports System Checker</h1>

    <?php
    $issues = [];
    $warnings = [];

    // 1. Check if controllers exist
    echo "<h2>1. Controller Files</h2>";
    echo "<table><tr><th>File</th><th>Status</th></tr>";

    $controllers = [
        'Partnerreports.php' => 'C:\xampp\htdocs\rhemazimbabwe\application\controllers\admin\Partnerreports.php',
    ];

    foreach ($controllers as $name => $path) {
        $exists = file_exists($path);
        echo "<tr>";
        echo "<td>{$name}</td>";
        echo "<td>" . ($exists ? "<span class='success'>✓ Exists</span>" : "<span class='error'>✗ Missing</span>") . "</td>";
        echo "</tr>";
        if (!$exists) $issues[] = "Controller {$name} missing";
    }
    echo "</table>";

    // 2. Check if views exist
    echo "<h2>2. View Files</h2>";
    echo "<table><tr><th>View</th><th>Status</th></tr>";

    $views = [
        'index.php' => 'C:\xampp\htdocs\rhemazimbabwe\application\views\admin\partnerreports\index.php',
        'partner_information.php' => 'C:\xampp\htdocs\rhemazimbabwe\application\views\admin\partnerreports\partner_information.php',
        'giving_collection_by_type.php' => 'C:\xampp\htdocs\rhemazimbabwe\application\views\admin\partnerreports\giving_collection_by_type.php',
        'partner_statement.php' => 'C:\xampp\htdocs\rhemazimbabwe\application\views\admin\partnerreports\partner_statement.php',
        'balance_giving_report.php' => 'C:\xampp\htdocs\rhemazimbabwe\application\views\admin\partnerreports\balance_giving_report.php',
    ];

    foreach ($views as $name => $path) {
        $exists = file_exists($path);
        echo "<tr>";
        echo "<td>{$name}</td>";
        echo "<td>" . ($exists ? "<span class='success'>✓ Exists</span>" : "<span class='error'>✗ Missing</span>") . "</td>";
        echo "</tr>";
        if (!$exists) $issues[] = "View {$name} missing";
    }
    echo "</table>";

    // 3. Check database tables
    echo "<h2>3. Database Tables</h2>";
    echo "<table><tr><th>Table</th><th>Records</th><th>Status</th></tr>";

    $tables = ['partners', 'partner_contributions', 'giving_types', 'giving_frequencies'];

    foreach ($tables as $table) {
        $result = $conn->query("SELECT COUNT(*) as count FROM {$table}");
        if ($result) {
            $row = $result->fetch_assoc();
            $count = $row['count'];
            echo "<tr>";
            echo "<td>{$table}</td>";
            echo "<td>{$count}</td>";
            echo "<td>";
            if ($count > 0) {
                echo "<span class='success'>✓ Has Data</span>";
            } else {
                echo "<span class='warning'>⚠ Empty</span>";
                $warnings[] = "Table {$table} has no data";
            }
            echo "</td>";
            echo "</tr>";
        } else {
            echo "<tr>";
            echo "<td>{$table}</td>";
            echo "<td>-</td>";
            echo "<td><span class='error'>✗ Table Missing</span></td>";
            echo "</tr>";
            $issues[] = "Table {$table} doesn't exist";
        }
    }
    echo "</table>";

    // 4. Check menu configuration
    echo "<h2>4. Reports Menu</h2>";
    $menu_result = $conn->query("SELECT * FROM sidebar_sub_menus WHERE `key` = 'partner_reports'");

    if ($menu_result && $menu_result->num_rows > 0) {
        $menu = $menu_result->fetch_assoc();
        echo "<table>";
        echo "<tr><th>Property</th><th>Value</th></tr>";
        echo "<tr><td>Menu Name</td><td>{$menu['menu']}</td></tr>";
        echo "<tr><td>URL</td><td>{$menu['url']}</td></tr>";
        echo "<tr><td>Active</td><td>" . ($menu['is_active'] ? "<span class='success'>✓ Yes</span>" : "<span class='error'>✗ No</span>") . "</td></tr>";
        echo "</table>";

        if (!$menu['is_active']) {
            $warnings[] = "Partner Reports menu is inactive";
        }
    } else {
        echo "<p class='error'>✗ Partner Reports menu not found in sidebar</p>";
        $issues[] = "Reports menu missing from sidebar_sub_menus";
    }

    // 5. Check permissions
    echo "<h2>5. Permissions</h2>";
    $perm_result = $conn->query("SELECT * FROM permission_category WHERE short_code = 'partner_reports'");

    if ($perm_result && $perm_result->num_rows > 0) {
        $perm = $perm_result->fetch_assoc();
        echo "<table>";
        echo "<tr><th>Permission</th><th>Status</th></tr>";
        echo "<tr><td>View</td><td>" . ($perm['enable_view'] ? "<span class='success'>✓ Enabled</span>" : "<span class='error'>✗ Disabled</span>") . "</td></tr>";
        echo "</table>";
    } else {
        echo "<p class='error'>✗ Permission 'partner_reports' not found</p>";
        $issues[] = "partner_reports permission missing";
    }

    // 6. Test data check
    echo "<h2>6. Sample Data Check</h2>";

    // Check partners with contributions
    $sql = "SELECT
                COUNT(DISTINCT p.id) as partners_with_contributions,
                COUNT(pc.id) as total_contributions,
                SUM(pc.amount) as total_amount
            FROM partners p
            LEFT JOIN partner_contributions pc ON pc.partner_id = p.id";
    $result = $conn->query($sql);

    if ($result) {
        $data = $result->fetch_assoc();
        echo "<table>";
        echo "<tr><th>Metric</th><th>Value</th></tr>";
        echo "<tr><td>Partners with Contributions</td><td>{$data['partners_with_contributions']}</td></tr>";
        echo "<tr><td>Total Contributions</td><td>{$data['total_contributions']}</td></tr>";
        echo "<tr><td>Total Amount</td><td>USD " . number_format($data['total_amount'], 2) . "</td></tr>";
        echo "</table>";

        if ($data['total_contributions'] == 0) {
            $warnings[] = "No contribution data - reports will show empty tables";
        }
    }

    // Summary
    echo "<h2>✅ Summary</h2>";

    if (empty($issues) && empty($warnings)) {
        echo "<div class='info status-ok'>";
        echo "<h3 style='margin-top:0;'>✓ All Checks Passed!</h3>";
        echo "<p>The Partner Reports system is properly configured and ready to use.</p>";
        echo "<a href='admin/partnerreports' class='btn'>Go to Reports</a>";
        echo "</div>";
    } else {
        if (!empty($issues)) {
            echo "<div class='info status-error'>";
            echo "<h3 style='margin-top:0;'>✗ Critical Issues Found</h3>";
            echo "<ul>";
            foreach ($issues as $issue) {
                echo "<li>{$issue}</li>";
            }
            echo "</ul>";
            echo "</div>";
        }

        if (!empty($warnings)) {
            echo "<div class='info' style='background:#fff3cd;border-color:#ffc107;'>";
            echo "<h3 style='margin-top:0;'>⚠ Warnings</h3>";
            echo "<ul>";
            foreach ($warnings as $warning) {
                echo "<li>{$warning}</li>";
            }
            echo "</ul>";
            echo "</div>";
        }
    }

    // Quick Actions
    echo "<h2>🔧 Quick Actions</h2>";
    echo "<p>If issues were found, try these actions:</p>";

    echo "<form method='POST' style='margin:20px 0;'>";

    if (isset($issues) && in_array("Reports menu missing from sidebar_sub_menus", $issues)) {
        echo "<button type='submit' name='add_menu' class='btn'>Add Reports Menu</button>";
    }

    if (isset($warnings) && strpos(implode(' ', $warnings), 'Empty') !== false) {
        echo "<button type='submit' name='add_test_data' class='btn' style='background:#28a745;'>Add Test Data</button>";
    }

    echo "</form>";

    // Handle quick actions
    if (isset($_POST['add_menu'])) {
        $partners_menu_id = $conn->query("SELECT id FROM sidebar_menus WHERE menu = 'Partners' LIMIT 1")->fetch_assoc()['id'];

        $sql = "INSERT INTO sidebar_sub_menus
                (sidebar_menu_id, menu, `key`, lang_key, url, level, access_permissions, permission_group_id, activate_controller, activate_methods, addon_permission, is_active)
                VALUES
                ({$partners_menu_id}, 'Partner Reports', 'partner_reports', 'partner_reports', 'admin/partnerreports', 6, '(''partner_reports'', ''can_view'')', NULL, 'partnerreports', 'index', '', 1)";

        if ($conn->query($sql)) {
            echo "<div class='info status-ok'>✓ Reports menu added successfully! Refresh page to see changes.</div>";
        }
    }

    if (isset($_POST['add_test_data'])) {
        // Add sample partners
        $conn->query("INSERT IGNORE INTO partners (partner_code, firstname, lastname, email, mobileno, giving_type_id, giving_frequency_id, contribution_amount, currency, start_date, status, is_active)
                     VALUES
                     ('PTR-2024-TEST1', 'Test', 'Partner', 'test1@example.com', '0771234567', 1, 1, 500.00, 'USD', '2024-01-01', 'active', 1)");

        // Add sample contribution
        $partner_id = $conn->insert_id;
        if ($partner_id) {
            $conn->query("INSERT INTO partner_contributions (partner_id, giving_type_id, amount, currency, contribution_date, payment_method, receipt_no, status, recorded_by)
                         VALUES
                         ({$partner_id}, 1, 500.00, 'USD', CURDATE(), 'cash', 'RCT-TEST-001', 'completed', 1)");

            echo "<div class='info status-ok'>✓ Test data added successfully! Refresh page to see changes.</div>";
        }
    }

    // Links
    echo "<hr style='margin:40px 0;'>";
    echo "<h3>📚 Resources</h3>";
    echo "<p>";
    echo "<a href='PARTNER_REPORTS_TESTING_GUIDE.md' class='btn'>Testing Guide</a> ";
    echo "<a href='admin/partnerreports' class='btn'>View Reports</a> ";
    echo "<a href='admin/partners' class='btn'>Manage Partners</a>";
    echo "</p>";

    echo "<p style='color:#666;font-size:12px;margin-top:40px;'>Debug Tool - Delete this file after testing</p>";
    ?>
</div>
</body>
</html>
<?php
$conn->close();
?>
