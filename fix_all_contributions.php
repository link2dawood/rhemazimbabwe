<?php
/**
 * ALL-IN-ONE FIX: Partner Contributions System
 * Run this once: http://localhost/rhemazimbabwe/fix_all_contributions.php
 *
 * This script will:
 * 1. Check and fix database table structure
 * 2. Create/verify upload directories
 * 3. Test database operations
 * 4. Verify menu configuration
 * 5. Run diagnostic checks
 */

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'ssdb';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8mb4");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Partner Contributions - All-in-One Fix</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
        h1 { color: #333; border-bottom: 3px solid #667eea; padding-bottom: 15px; margin-top: 0; }
        h2 { color: #667eea; margin-top: 40px; padding-top: 20px; border-top: 1px solid #e0e0e0; }
        h3 { color: #555; margin-top: 25px; }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        .info { color: #17a2b8; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th { background: #667eea; color: white; padding: 12px; text-align: left; font-weight: 600; }
        td { padding: 10px; border-bottom: 1px solid #e0e0e0; }
        tr:hover { background: #f8f9fa; }
        .info-box { background: #e7f3ff; padding: 20px; border-left: 4px solid #2196F3; margin: 20px 0; border-radius: 4px; }
        .success-box { background: #d4edda; padding: 20px; border-left: 4px solid #28a745; margin: 20px 0; border-radius: 4px; }
        .error-box { background: #f8d7da; padding: 20px; border-left: 4px solid #dc3545; margin: 20px 0; border-radius: 4px; }
        .warning-box { background: #fff3cd; padding: 20px; border-left: 4px solid #ffc107; margin: 20px 0; border-radius: 4px; }
        .btn { display: inline-block; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 5px; border: none; cursor: pointer; font-size: 14px; font-weight: 600; transition: all 0.3s; }
        .btn:hover { background: #5568d3; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102,126,234,0.4); }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        .btn-warning { background: #ffc107; color: #333; }
        .btn-warning:hover { background: #e0a800; }
        pre { background: #f5f5f5; padding: 15px; border-radius: 5px; overflow-x: auto; border-left: 3px solid #667eea; }
        code { background: #f5f5f5; padding: 2px 6px; border-radius: 3px; font-family: 'Courier New', monospace; color: #e83e8c; }
        .progress { width: 100%; height: 30px; background: #f0f0f0; border-radius: 15px; overflow: hidden; margin: 20px 0; }
        .progress-bar { height: 100%; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%); text-align: center; line-height: 30px; color: white; font-weight: bold; transition: width 0.5s; }
        .check-item { padding: 10px; margin: 5px 0; border-radius: 5px; background: #f8f9fa; }
        .check-item.passed { border-left: 4px solid #28a745; }
        .check-item.failed { border-left: 4px solid #dc3545; }
        .check-item.warning { border-left: 4px solid #ffc107; }
        ul { line-height: 1.8; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; margin-left: 8px; }
        .badge-success { background: #28a745; color: white; }
        .badge-error { background: #dc3545; color: white; }
        .badge-warning { background: #ffc107; color: #333; }
        .badge-info { background: #17a2b8; color: white; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔧 Partner Contributions - All-in-One Fix Tool</h1>
    <p style="color: #666; font-size: 14px; margin-top: -10px;">Comprehensive diagnostic and repair tool for the contributions system</p>

    <?php
    $issues = [];
    $warnings = [];
    $fixes_applied = [];
    $checks_passed = 0;
    $total_checks = 10;

    // ========================================
    // STEP 1: Check Database Table Structure
    // ========================================
    echo "<h2>📊 Step 1: Database Table Structure</h2>";

    $table_exists = $conn->query("SHOW TABLES LIKE 'partner_contributions'")->num_rows > 0;

    if (!$table_exists) {
        echo "<div class='error-box'>";
        echo "<p class='error'>✗ CRITICAL: partner_contributions table does not exist!</p>";
        echo "<p>The table needs to be created from migration files.</p>";
        echo "</div>";
        $issues[] = "partner_contributions table missing";
    } else {
        echo "<p class='success'>✓ Table 'partner_contributions' exists</p>";
        $checks_passed++;

        // Check columns
        $required_columns = [
            'id' => 'INT AUTO_INCREMENT',
            'partner_id' => 'INT',
            'giving_type_id' => 'INT',
            'amount' => 'DECIMAL',
            'currency' => 'VARCHAR',
            'contribution_date' => 'DATE',
            'payment_method' => 'VARCHAR',
            'receipt_no' => 'VARCHAR',
            'status' => 'VARCHAR/ENUM',
            'recorded_by' => 'INT',
            'attachment' => 'VARCHAR',
            'created_at' => 'TIMESTAMP',
            'updated_at' => 'TIMESTAMP'
        ];

        $result = $conn->query("DESCRIBE partner_contributions");
        $existing_columns = [];

        while ($row = $result->fetch_assoc()) {
            $existing_columns[$row['Field']] = $row['Type'];
        }

        echo "<h3>Column Verification</h3>";
        echo "<table>";
        echo "<tr><th>Column</th><th>Expected Type</th><th>Status</th></tr>";

        $missing_columns = [];
        foreach ($required_columns as $col => $type) {
            echo "<tr>";
            echo "<td><code>{$col}</code></td>";
            echo "<td>{$type}</td>";

            if (isset($existing_columns[$col])) {
                echo "<td><span class='success'>✓ Exists</span></td>";
            } else {
                echo "<td><span class='error'>✗ Missing</span></td>";
                $missing_columns[] = $col;
            }
            echo "</tr>";
        }
        echo "</table>";

        // Add missing columns
        if (!empty($missing_columns)) {
            echo "<h3>Adding Missing Columns</h3>";

            $column_definitions = [
                'receipt_no' => "ALTER TABLE partner_contributions ADD COLUMN receipt_no VARCHAR(50) NULL AFTER reference_no",
                'recorded_by' => "ALTER TABLE partner_contributions ADD COLUMN recorded_by INT NULL AFTER status",
                'attachment' => "ALTER TABLE partner_contributions ADD COLUMN attachment VARCHAR(255) NULL AFTER notes",
                'created_at' => "ALTER TABLE partner_contributions ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
                'updated_at' => "ALTER TABLE partner_contributions ADD COLUMN updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP"
            ];

            foreach ($missing_columns as $col) {
                if (isset($column_definitions[$col])) {
                    if ($conn->query($column_definitions[$col])) {
                        echo "<p class='success'>✓ Added column: {$col}</p>";
                        $fixes_applied[] = "Added {$col} column";
                    } else {
                        echo "<p class='error'>✗ Failed to add {$col}: " . $conn->error . "</p>";
                        $issues[] = "Could not add {$col} column";
                    }
                }
            }
            $checks_passed++;
        } else {
            echo "<div class='success-box'><p class='success'>✓ All required columns exist</p></div>";
            $checks_passed++;
        }
    }

    // ========================================
    // STEP 2: Check Upload Directories
    // ========================================
    echo "<h2>📁 Step 2: Upload Directories</h2>";

    $base_path = dirname(__FILE__);
    $directories = [
        'uploads',
        'uploads/partner_contributions',
        'uploads/partner_images',
        'uploads/partner_documents'
    ];

    $dir_issues = [];

    foreach ($directories as $dir) {
        $full_path = $base_path . DIRECTORY_SEPARATOR . $dir;

        echo "<div class='check-item";

        if (file_exists($full_path)) {
            if (is_writable($full_path)) {
                echo " passed'>";
                echo "<p><span class='success'>✓</span> <code>{$dir}</code> exists and is writable</p>";
                echo "</div>";
            } else {
                echo " failed'>";
                echo "<p><span class='error'>✗</span> <code>{$dir}</code> exists but is NOT writable</p>";

                // Try to fix
                if (chmod($full_path, 0777)) {
                    echo "<p class='success' style='margin-left:24px;'>✓ Fixed: Made directory writable</p>";
                    $fixes_applied[] = "Made {$dir} writable";
                } else {
                    echo "<p class='error' style='margin-left:24px;'>✗ Could not make writable - set permissions manually</p>";
                    $dir_issues[] = "{$dir} not writable";
                }
                echo "</div>";
            }
        } else {
            echo " warning'>";
            echo "<p><span class='warning'>⚠</span> <code>{$dir}</code> does not exist</p>";

            // Try to create
            if (mkdir($full_path, 0777, true)) {
                echo "<p class='success' style='margin-left:24px;'>✓ Created directory successfully</p>";
                $fixes_applied[] = "Created {$dir}";

                // Create index.html
                $index_path = $full_path . DIRECTORY_SEPARATOR . 'index.html';
                file_put_contents($index_path, '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><h1>Directory access is forbidden.</h1></body></html>');

                echo "</div>";
            } else {
                echo "<p class='error' style='margin-left:24px;'>✗ Failed to create directory</p>";
                echo "<p style='margin-left:24px;'>Create manually: <code>{$full_path}</code></p>";
                $dir_issues[] = "{$dir} could not be created";
                echo "</div>";
            }
        }
    }

    if (empty($dir_issues)) {
        $checks_passed++;
    }

    // Create .htaccess for uploads security
    $htaccess_path = $base_path . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . '.htaccess';
    if (!file_exists($htaccess_path)) {
        $htaccess_content = "# Allow access to uploaded files\nOptions -Indexes\n<Files *>\n    Order allow,deny\n    Allow from all\n</Files>";
        if (file_put_contents($htaccess_path, $htaccess_content)) {
            echo "<p class='success'>✓ Created .htaccess for upload security</p>";
            $fixes_applied[] = "Created uploads .htaccess";
        }
    }

    // ========================================
    // STEP 3: Check Partner Data
    // ========================================
    echo "<h2>👥 Step 3: Partner Data</h2>";

    $partner_result = $conn->query("SELECT COUNT(*) as count FROM partners WHERE is_active = 1");
    $partner_row = $partner_result->fetch_assoc();
    $partner_count = $partner_row['count'];

    if ($partner_count > 0) {
        echo "<div class='check-item passed'>";
        echo "<p><span class='success'>✓</span> Found <strong>{$partner_count}</strong> active partner(s)</p>";
        echo "</div>";
        $checks_passed++;
    } else {
        echo "<div class='check-item failed'>";
        echo "<p><span class='error'>✗</span> No active partners found</p>";
        echo "<p style='margin-left:24px;'>You need to add partners before adding contributions.</p>";
        echo "</div>";
        $issues[] = "No active partners";
    }

    // ========================================
    // STEP 4: Check Giving Types
    // ========================================
    echo "<h2>💰 Step 4: Giving Types</h2>";

    $type_result = $conn->query("SELECT COUNT(*) as count FROM giving_types WHERE is_active = 1");
    $type_row = $type_result->fetch_assoc();
    $type_count = $type_row['count'];

    if ($type_count > 0) {
        echo "<div class='check-item passed'>";
        echo "<p><span class='success'>✓</span> Found <strong>{$type_count}</strong> active giving type(s)</p>";
        echo "</div>";
        $checks_passed++;
    } else {
        echo "<div class='check-item warning'>";
        echo "<p><span class='warning'>⚠</span> No active giving types found</p>";
        echo "<p style='margin-left:24px;'>Contributions can work without types, but it's recommended to add them.</p>";
        echo "</div>";
        $warnings[] = "No giving types configured";
    }

    // ========================================
    // STEP 5: Check Recent Contributions
    // ========================================
    echo "<h2>📝 Step 5: Contribution Data</h2>";

    $contrib_result = $conn->query("SELECT COUNT(*) as count FROM partner_contributions");
    $contrib_row = $contrib_result->fetch_assoc();
    $contrib_count = $contrib_row['count'];

    echo "<p>Total contributions in database: <strong>{$contrib_count}</strong></p>";

    if ($contrib_count > 0) {
        $checks_passed++;

        // Show recent 5
        $recent = $conn->query("SELECT pc.*, p.firstname, p.lastname
                                FROM partner_contributions pc
                                LEFT JOIN partners p ON p.id = pc.partner_id
                                ORDER BY pc.created_at DESC LIMIT 5");

        if ($recent && $recent->num_rows > 0) {
            echo "<h3>Recent Contributions</h3>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Receipt No</th><th>Partner</th><th>Amount</th><th>Date</th><th>Status</th></tr>";

            while ($row = $recent->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['receipt_no']}</td>";
                echo "<td>" . htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) . "</td>";
                echo "<td>{$row['currency']} " . number_format($row['amount'], 2) . "</td>";
                echo "<td>{$row['contribution_date']}</td>";
                echo "<td><span class='badge badge-" . ($row['status'] == 'completed' ? 'success' : 'warning') . "'>{$row['status']}</span></td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } else {
        echo "<div class='info-box'>";
        echo "<p class='info'>ℹ No contributions yet - this is normal for a new setup</p>";
        echo "</div>";
        $checks_passed++;
    }

    // ========================================
    // STEP 6: Test Database Insert
    // ========================================
    echo "<h2>🧪 Step 6: Test Database Operations</h2>";

    if ($partner_count > 0) {
        $test_partner = $conn->query("SELECT id FROM partners WHERE is_active = 1 LIMIT 1")->fetch_assoc();

        echo "<p>Testing contribution insert with partner ID: {$test_partner['id']}</p>";

        $receipt_no = 'TEST-' . date('Ymd-His');
        $test_sql = "INSERT INTO partner_contributions
                     (partner_id, amount, currency, contribution_date, payment_method, receipt_no, status, recorded_by, notes)
                     VALUES
                     ({$test_partner['id']}, 100.00, 'USD', CURDATE(), 'cash', '{$receipt_no}', 'completed', 1, 'Test from fix tool - safe to delete')";

        if ($conn->query($test_sql)) {
            $test_id = $conn->insert_id;
            echo "<div class='check-item passed'>";
            echo "<p><span class='success'>✓</span> Test insert successful!</p>";
            echo "<p style='margin-left:24px;'>Receipt: <code>{$receipt_no}</code> | ID: {$test_id}</p>";
            echo "</div>";

            // Clean up test record
            $conn->query("DELETE FROM partner_contributions WHERE id = {$test_id}");
            echo "<p class='info' style='margin-left:24px;'>✓ Test record cleaned up</p>";

            $checks_passed++;
        } else {
            echo "<div class='check-item failed'>";
            echo "<p><span class='error'>✗</span> Test insert failed!</p>";
            echo "<p style='margin-left:24px;'>Error: " . $conn->error . "</p>";
            echo "</div>";
            $issues[] = "Cannot insert into partner_contributions";
        }
    } else {
        echo "<div class='info-box'>";
        echo "<p class='warning'>⚠ Skipping test - no active partners available</p>";
        echo "</div>";
    }

    // ========================================
    // STEP 7: Check Controller File
    // ========================================
    echo "<h2>📄 Step 7: Controller Files</h2>";

    $controller_path = $base_path . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'Partnercontributions.php';

    if (file_exists($controller_path)) {
        echo "<div class='check-item passed'>";
        echo "<p><span class='success'>✓</span> Controller file exists</p>";
        echo "<p style='margin-left:24px;'><code>Partnercontributions.php</code></p>";
        echo "</div>";
        $checks_passed++;
    } else {
        echo "<div class='check-item failed'>";
        echo "<p><span class='error'>✗</span> Controller file missing!</p>";
        echo "<p style='margin-left:24px;'>Expected: <code>{$controller_path}</code></p>";
        echo "</div>";
        $issues[] = "Partnercontributions controller missing";
    }

    // ========================================
    // STEP 8: Check Model File
    // ========================================
    $model_path = $base_path . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Contribution_model.php';

    if (file_exists($model_path)) {
        echo "<div class='check-item passed'>";
        echo "<p><span class='success'>✓</span> Model file exists</p>";
        echo "<p style='margin-left:24px;'><code>Contribution_model.php</code></p>";

        // Check for generateReceiptNumber method
        $model_content = file_get_contents($model_path);
        if (strpos($model_content, 'function generateReceiptNumber') !== false) {
            echo "<p class='success' style='margin-left:24px;'>✓ generateReceiptNumber() method exists</p>";
        } else {
            echo "<p class='warning' style='margin-left:24px;'>⚠ generateReceiptNumber() method not found</p>";
            $warnings[] = "Receipt generation method might be missing";
        }

        echo "</div>";
        $checks_passed++;
    } else {
        echo "<div class='check-item failed'>";
        echo "<p><span class='error'>✗</span> Model file missing!</p>";
        echo "<p style='margin-left:24px;'>Expected: <code>{$model_path}</code></p>";
        echo "</div>";
        $issues[] = "Contribution_model missing";
    }

    // ========================================
    // STEP 9: Check View File
    // ========================================
    $view_path = $base_path . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'contributions' . DIRECTORY_SEPARATOR . 'contributionadd.php';

    if (file_exists($view_path)) {
        echo "<div class='check-item passed'>";
        echo "<p><span class='success'>✓</span> Add view file exists</p>";
        echo "<p style='margin-left:24px;'><code>contributionadd.php</code></p>";
        echo "</div>";
        $checks_passed++;
    } else {
        echo "<div class='check-item failed'>";
        echo "<p><span class='error'>✗</span> Add view file missing!</p>";
        echo "<p style='margin-left:24px;'>Expected: <code>{$view_path}</code></p>";
        echo "</div>";
        $issues[] = "contributionadd view missing";
    }

    // ========================================
    // STEP 10: Check Menu Configuration
    // ========================================
    echo "<h2>📋 Step 10: Menu Configuration</h2>";

    $menu_check = $conn->query("SELECT * FROM sidebar_sub_menus WHERE url LIKE '%partnercontributions%'");

    if ($menu_check && $menu_check->num_rows > 0) {
        echo "<div class='check-item passed'>";
        echo "<p><span class='success'>✓</span> Contributions menu configured</p>";

        while ($menu = $menu_check->fetch_assoc()) {
            echo "<p style='margin-left:24px;'>• {$menu['menu']} → <code>{$menu['url']}</code></p>";
        }
        echo "</div>";
        $checks_passed++;
    } else {
        echo "<div class='check-item warning'>";
        echo "<p><span class='warning'>⚠</span> No contributions menu found</p>";
        echo "<p style='margin-left:24px;'>Menu might be configured elsewhere or needs to be added.</p>";
        echo "</div>";
        $warnings[] = "Contributions menu not in sidebar_sub_menus";
    }

    // ========================================
    // PROGRESS SUMMARY
    // ========================================
    $progress_percentage = ($checks_passed / $total_checks) * 100;

    echo "<h2>📊 Overall Progress</h2>";
    echo "<div class='progress'>";
    echo "<div class='progress-bar' style='width: {$progress_percentage}%'>";
    echo round($progress_percentage) . "% Complete";
    echo "</div>";
    echo "</div>";

    echo "<p><strong>Checks Passed:</strong> {$checks_passed} / {$total_checks}</p>";

    // ========================================
    // FINAL SUMMARY
    // ========================================
    echo "<h2>📋 Summary & Next Steps</h2>";

    if (empty($issues)) {
        echo "<div class='success-box'>";
        echo "<h3 style='margin-top:0;'>✅ System Ready!</h3>";
        echo "<p>The contributions system appears to be properly configured.</p>";

        if (!empty($fixes_applied)) {
            echo "<p><strong>Fixes Applied:</strong></p>";
            echo "<ul>";
            foreach ($fixes_applied as $fix) {
                echo "<li>{$fix}</li>";
            }
            echo "</ul>";
        }

        if (!empty($warnings)) {
            echo "<p><strong>Minor Warnings:</strong></p>";
            echo "<ul>";
            foreach ($warnings as $warning) {
                echo "<li class='warning'>{$warning}</li>";
            }
            echo "</ul>";
        }

        echo "<h3>Next Steps:</h3>";
        echo "<ol>";
        echo "<li>Test adding a contribution: <a href='admin/partnercontributions/add' class='btn btn-success'>Add Contribution</a></li>";
        echo "<li>View contributions list: <a href='admin/partnercontributions' class='btn'>View Contributions</a></li>";
        echo "<li>Once confirmed working, delete this fix file for security</li>";
        echo "</ol>";
        echo "</div>";
    } else {
        echo "<div class='error-box'>";
        echo "<h3 style='margin-top:0;'>❌ Issues Found</h3>";
        echo "<p>The following issues need attention:</p>";
        echo "<ul>";
        foreach ($issues as $issue) {
            echo "<li class='error'>{$issue}</li>";
        }
        echo "</ul>";

        echo "<h3>Recommended Actions:</h3>";
        echo "<ol>";
        echo "<li>Fix the critical issues listed above</li>";
        echo "<li>Check <code>application/logs/log-" . date('Y-m-d') . ".php</code> for detailed errors</li>";
        echo "<li>Verify database migrations have run successfully</li>";
        echo "<li>Refresh this page after making fixes</li>";
        echo "</ol>";
        echo "</div>";
    }

    // ========================================
    // DIAGNOSTIC INFORMATION
    // ========================================
    echo "<h2>🔍 System Information</h2>";
    echo "<table>";
    echo "<tr><th>Property</th><th>Value</th></tr>";
    echo "<tr><td>PHP Version</td><td>" . phpversion() . "</td></tr>";
    echo "<tr><td>MySQL Version</td><td>" . $conn->server_info . "</td></tr>";
    echo "<tr><td>Database</td><td>{$database}</td></tr>";
    echo "<tr><td>Base Path</td><td>{$base_path}</td></tr>";
    echo "<tr><td>Upload Max Filesize</td><td>" . ini_get('upload_max_filesize') . "</td></tr>";
    echo "<tr><td>Post Max Size</td><td>" . ini_get('post_max_size') . "</td></tr>";
    echo "<tr><td>Memory Limit</td><td>" . ini_get('memory_limit') . "</td></tr>";
    echo "<tr><td>Date/Time</td><td>" . date('Y-m-d H:i:s') . "</td></tr>";
    echo "</table>";

    // ========================================
    // QUICK REFERENCE
    // ========================================
    echo "<h2>📚 Quick Reference</h2>";
    echo "<div class='info-box'>";
    echo "<h3>Understanding HTTP 303 Status</h3>";
    echo "<p><strong>303 'See Other'</strong> is the CORRECT and EXPECTED response when adding a contribution!</p>";
    echo "<p><strong>What happens:</strong></p>";
    echo "<ol>";
    echo "<li>Form submits (POST) to <code>/admin/partnercontributions/add</code></li>";
    echo "<li>Server processes and saves data</li>";
    echo "<li>Server sends 303 redirect to <code>/admin/partnercontributions/show/{id}</code></li>";
    echo "<li>Browser automatically follows redirect (GET)</li>";
    echo "<li>You see the contribution details page</li>";
    echo "</ol>";
    echo "<p><strong>This prevents duplicate submissions if you refresh the page!</strong></p>";
    echo "</div>";

    echo "<div class='info-box'>";
    echo "<h3>Common Issues & Solutions</h3>";
    echo "<table>";
    echo "<tr><th>Issue</th><th>Solution</th></tr>";
    echo "<tr><td>Form submits but no data saved</td><td>Check logs in <code>application/logs/</code></td></tr>";
    echo "<tr><td>Receipt number not generated</td><td>Verify Contribution_model has generateReceiptNumber() method</td></tr>";
    echo "<tr><td>File upload fails</td><td>Ensure uploads/partner_contributions directory exists and is writable (777)</td></tr>";
    echo "<tr><td>'See Other' redirect but error</td><td>Check if contribution was actually saved in database</td></tr>";
    echo "</table>";
    echo "</div>";

    ?>

    <hr style="margin:40px 0;">
    <p style="color:#666;font-size:12px;text-align:center;">
        All-in-One Fix Tool v1.0 | Safe to delete after verification<br>
        Generated: <?php echo date('Y-m-d H:i:s'); ?>
    </p>
</div>
</body>
</html>
<?php
$conn->close();
?>
