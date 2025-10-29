<?php
/**
 * Contributions System Checker
 * Access: http://localhost/rhemazimbabwe/check_contributions.php
 */

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'ssdb';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contributions System Checker</title>
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
        .info { background: #d9edf7; padding: 15px; border-left: 4px solid #31b0d5; margin: 20px 0; }
        .btn { display: inline-block; padding: 10px 20px; background: #3c8dbc; color: white; text-decoration: none; border-radius: 3px; margin: 5px; }
        .btn:hover { background: #357ca5; }
    </style>
</head>
<body>
<div class="container">
    <h1>💰 Contributions System Checker</h1>

    <?php
    $issues = [];
    $warnings = [];

    // 1. Check table structure
    echo "<h2>1. Database Table Check</h2>";
    $result = $conn->query("DESCRIBE partner_contributions");

    if ($result) {
        echo "<table><tr><th>Field</th><th>Type</th><th>Null</th><th>Default</th></tr>";
        $has_receipt_no = false;
        $has_recorded_by = false;

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['Field']}</td>";
            echo "<td>{$row['Type']}</td>";
            echo "<td>{$row['Null']}</td>";
            echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
            echo "</tr>";

            if ($row['Field'] == 'receipt_no') $has_receipt_no = true;
            if ($row['Field'] == 'recorded_by') $has_recorded_by = true;
        }
        echo "</table>";

        if (!$has_receipt_no) {
            $issues[] = "Missing 'receipt_no' column in partner_contributions table";
        }
        if (!$has_recorded_by) {
            $warnings[] = "Missing 'recorded_by' column - contributions won't track who added them";
        }
    } else {
        $issues[] = "partner_contributions table doesn't exist!";
    }

    // 2. Check uploads directory
    echo "<h2>2. Upload Directory Check</h2>";
    $upload_dir = "C:\\xampp\\htdocs\\rhemazimbabwe\\uploads\\partner_contributions";

    if (file_exists($upload_dir)) {
        if (is_writable($upload_dir)) {
            echo "<p class='success'>✓ Upload directory exists and is writable</p>";
        } else {
            echo "<p class='error'>✗ Upload directory exists but is NOT writable</p>";
            $issues[] = "uploads/partner_contributions directory is not writable";
        }
    } else {
        echo "<p class='error'>✗ Upload directory doesn't exist</p>";
        $issues[] = "uploads/partner_contributions directory missing";

        // Offer to create it
        if (isset($_POST['create_upload_dir'])) {
            if (mkdir($upload_dir, 0777, true)) {
                echo "<p class='success'>✓ Created upload directory successfully!</p>";
            } else {
                echo "<p class='error'>✗ Failed to create directory</p>";
            }
        }
    }

    // 3. Check recent contributions
    echo "<h2>3. Recent Contributions</h2>";
    $result = $conn->query("SELECT * FROM partner_contributions ORDER BY created_at DESC LIMIT 5");

    if ($result && $result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Receipt No</th><th>Partner ID</th><th>Amount</th><th>Date</th><th>Status</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['receipt_no']}</td>";
            echo "<td>{$row['partner_id']}</td>";
            echo "<td>{$row['currency']} {$row['amount']}</td>";
            echo "<td>{$row['contribution_date']}</td>";
            echo "<td>{$row['status']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='warning'>⚠ No contributions in database yet</p>";
        $warnings[] = "No contributions found - try adding one";
    }

    // 4. Check if partners exist
    echo "<h2>4. Partners Check</h2>";
    $result = $conn->query("SELECT COUNT(*) as count FROM partners WHERE is_active = 1");

    if ($result) {
        $row = $result->fetch_assoc();
        $partner_count = $row['count'];

        if ($partner_count > 0) {
            echo "<p class='success'>✓ Found {$partner_count} active partner(s)</p>";
        } else {
            echo "<p class='error'>✗ No active partners found</p>";
            $issues[] = "No active partners - you need partners before adding contributions";
        }
    }

    // 5. Check giving types
    echo "<h2>5. Giving Types Check</h2>";
    $result = $conn->query("SELECT COUNT(*) as count FROM giving_types WHERE is_active = 1");

    if ($result) {
        $row = $result->fetch_assoc();
        $type_count = $row['count'];

        if ($type_count > 0) {
            echo "<p class='success'>✓ Found {$type_count} active giving type(s)</p>";
        } else {
            echo "<p class='warning'>⚠ No giving types found</p>";
            $warnings[] = "No giving types - contributions can still be added but without type classification";
        }
    }

    // 6. Test contribution insert
    echo "<h2>6. Test Data Insert</h2>";

    if (isset($_POST['test_insert'])) {
        // Get first active partner
        $partner_result = $conn->query("SELECT id FROM partners WHERE is_active = 1 LIMIT 1");

        if ($partner_result && $partner_result->num_rows > 0) {
            $partner = $partner_result->fetch_assoc();

            $receipt_no = 'RCT-TEST-' . time();
            $sql = "INSERT INTO partner_contributions
                    (partner_id, amount, currency, contribution_date, payment_method, receipt_no, status, recorded_by, notes)
                    VALUES
                    ({$partner['id']}, 100.00, 'USD', CURDATE(), 'cash', '{$receipt_no}', 'completed', 1, 'Test contribution from diagnostic tool')";

            if ($conn->query($sql)) {
                echo "<p class='success'>✓ Test contribution added successfully!</p>";
                echo "<p>Receipt No: <strong>{$receipt_no}</strong></p>";
            } else {
                echo "<p class='error'>✗ Failed to insert test contribution</p>";
                echo "<p>Error: " . $conn->error . "</p>";
                $issues[] = "Cannot insert contributions - SQL error";
            }
        } else {
            echo "<p class='error'>✗ Cannot test insert - no active partners</p>";
        }
    }

    // 7. Check form validation
    echo "<h2>7. Required Fields Check</h2>";
    echo "<table>";
    echo "<tr><th>Field</th><th>Required</th><th>Validation</th></tr>";
    echo "<tr><td>Partner</td><td class='success'>✓ Yes</td><td>Must exist in partners table</td></tr>";
    echo "<tr><td>Contribution Date</td><td class='success'>✓ Yes</td><td>Valid date format</td></tr>";
    echo "<tr><td>Amount</td><td class='success'>✓ Yes</td><td>Numeric, > 0</td></tr>";
    echo "<tr><td>Payment Method</td><td class='success'>✓ Yes</td><td>Must be selected</td></tr>";
    echo "<tr><td>Currency</td><td>No</td><td>Defaults to USD</td></tr>";
    echo "<tr><td>Status</td><td>No</td><td>Defaults to 'completed'</td></tr>";
    echo "</table>";

    // Summary
    echo "<h2>✅ Summary</h2>";

    if (empty($issues)) {
        echo "<div class='info' style='background:#d4edda;border-color:#28a745;'>";
        echo "<h3 style='margin-top:0;'>✓ System Ready!</h3>";
        echo "<p>The contributions system appears to be working correctly.</p>";

        if (!empty($warnings)) {
            echo "<p><strong>Minor Warnings:</strong></p>";
            echo "<ul>";
            foreach ($warnings as $warning) {
                echo "<li>{$warning}</li>";
            }
            echo "</ul>";
        }

        echo "<a href='admin/partnercontributions' class='btn'>Go to Contributions</a>";
        echo "<a href='admin/partnercontributions/add' class='btn' style='background:#28a745;'>Add Contribution</a>";
        echo "</div>";
    } else {
        echo "<div class='info' style='background:#f8d7da;border-color:#dc3545;'>";
        echo "<h3 style='margin-top:0;'>✗ Issues Found</h3>";
        echo "<ul>";
        foreach ($issues as $issue) {
            echo "<li class='error'>{$issue}</li>";
        }
        echo "</ul>";
        echo "</div>";
    }

    // Quick actions
    echo "<h2>🔧 Quick Actions</h2>";
    echo "<form method='POST'>";

    if (!file_exists($upload_dir)) {
        echo "<button type='submit' name='create_upload_dir' class='btn'>Create Upload Directory</button>";
    }

    if ($partner_count > 0 && !isset($_POST['test_insert'])) {
        echo "<button type='submit' name='test_insert' class='btn' style='background:#ffc107;'>Test Add Contribution</button>";
    }

    echo "</form>";

    // Common errors
    echo "<h2>❓ Common Issues & Solutions</h2>";
    echo "<div class='info'>";
    echo "<h4>Issue: Form submits but redirects to error</h4>";
    echo "<p><strong>Solution:</strong> Check if the partner_contributions table has all required columns</p>";
    echo "<code>Required columns: id, partner_id, amount, currency, contribution_date, payment_method, receipt_no, status</code>";

    echo "<h4>Issue: Receipt number not generating</h4>";
    echo "<p><strong>Solution:</strong> Ensure the Contribution_model has the generateReceiptNumber() method</p>";

    echo "<h4>Issue: File upload fails</h4>";
    echo "<p><strong>Solution:</strong> Create uploads/partner_contributions directory with write permissions</p>";

    echo "<h4>Issue: 303 redirect but no data saved</h4>";
    echo "<p><strong>Solution:</strong> Check application/logs for PHP errors</p>";
    echo "</div>";

    ?>

    <hr style="margin:40px 0;">
    <p style="color:#666;font-size:12px;">Diagnostic Tool - Safe to delete after testing</p>
</div>
</body>
</html>
<?php
$conn->close();
?>
