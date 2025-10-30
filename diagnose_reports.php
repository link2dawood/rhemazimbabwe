<?php
/**
 * Complete Reports Diagnostic
 */
$conn = new mysqli('localhost', 'root', '', 'ssdb');

if ($conn->connect_error) {
    die("Connection failed");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reports Diagnostic</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 3px solid #3c8dbc; padding-bottom: 10px; }
        h2 { color: #3c8dbc; margin-top: 30px; }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { background: #3c8dbc; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        .code-box { background: #f8f9fa; padding: 15px; border-left: 4px solid #3c8dbc; margin: 15px 0; font-family: monospace; }
        .check { display: inline-block; width: 30px; }
    </style>
</head>
<body>
<div class="container">
    <h1>📊 Partner Reports - Complete Diagnostic</h1>

    <?php
    $issues = [];
    $checks_passed = 0;
    $total_checks = 8;

    // ========================================
    // CHECK 1: Permission Exists
    // ========================================
    echo "<h2>✓ Check 1: Permission in Database</h2>";
    $result = $conn->query("SELECT * FROM permission_category WHERE short_code = 'partner_reports'");

    if ($result->num_rows > 0) {
        echo "<p class='success'><span class='check'>✓</span> partner_reports permission EXISTS</p>";
        $perm = $result->fetch_assoc();
        echo "<p>Permission ID: {$perm['id']}, Name: {$perm['name']}</p>";
        $checks_passed++;
        $partner_reports_perm_id = $perm['id'];
    } else {
        echo "<p class='error'><span class='check'>✗</span> partner_reports permission MISSING</p>";
        echo "<p><strong>ACTION: Run fix_reports_now.php first!</strong></p>";
        $issues[] = "Permission missing";
    }

    // ========================================
    // CHECK 2: Role Permissions
    // ========================================
    echo "<h2>✓ Check 2: Role Permissions</h2>";

    if (isset($partner_reports_perm_id)) {
        $result = $conn->query("
            SELECT r.id, r.name, rp.can_view
            FROM roles_permissions rp
            JOIN roles r ON r.id = rp.role_id
            WHERE rp.perm_cat_id = {$partner_reports_perm_id}
        ");

        if ($result->num_rows > 0) {
            echo "<p class='success'><span class='check'>✓</span> Roles have partner_reports permission</p>";
            echo "<table>";
            echo "<tr><th>Role</th><th>Can View</th></tr>";
            $has_view_permission = false;
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>{$row['name']}</td><td>";
                if ($row['can_view']) {
                    echo "<span class='success'>✓ Yes</span>";
                    $has_view_permission = true;
                } else {
                    echo "<span class='error'>✗ No</span>";
                }
                echo "</td></tr>";
            }
            echo "</table>";

            if ($has_view_permission) {
                $checks_passed++;
            } else {
                $issues[] = "No roles have can_view permission";
            }
        } else {
            echo "<p class='error'><span class='check'>✗</span> No roles have partner_reports permission</p>";
            $issues[] = "Roles not assigned";
        }
    }

    // ========================================
    // CHECK 3: Partners Data
    // ========================================
    echo "<h2>✓ Check 3: Partners in Database</h2>";

    // Test with NO filters (what report does initially)
    $result = $conn->query("SELECT * FROM partners");
    $total_partners = $result->num_rows;

    $result = $conn->query("SELECT * FROM partners WHERE is_active = 1");
    $active_partners = $result->num_rows;

    echo "<p>Total partners: <strong>{$total_partners}</strong></p>";
    echo "<p>Active partners: <strong>{$active_partners}</strong></p>";

    if ($active_partners > 0) {
        echo "<p class='success'><span class='check'>✓</span> Active partners exist</p>";
        $checks_passed++;
    } else {
        echo "<p class='error'><span class='check'>✗</span> No active partners</p>";
        $issues[] = "No active partners";
    }

    // ========================================
    // CHECK 4: Test Exact Query from Partner_model
    // ========================================
    echo "<h2>✓ Check 4: Simulate Partner_model->getAll()</h2>";

    // This is EXACTLY what Partner_model->getAll() does
    $query = "
        SELECT partners.*,
               giving_frequencies.name as frequency_name,
               giving_types.name as type_name,
               students.firstname as student_firstname,
               students.lastname as student_lastname
        FROM partners
        LEFT JOIN giving_frequencies ON giving_frequencies.id = partners.giving_frequency_id
        LEFT JOIN giving_types ON giving_types.id = partners.giving_type_id
        LEFT JOIN students ON students.id = partners.student_id
        ORDER BY partners.created_at DESC
    ";

    $result = $conn->query($query);
    $partners_from_model = $result->num_rows;

    echo "<p>Partners returned by model query: <strong>{$partners_from_model}</strong></p>";

    if ($partners_from_model > 0) {
        echo "<p class='success'><span class='check'>✓</span> Model query returns data</p>";
        $checks_passed++;

        echo "<h4>Sample Partner Data:</h4>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Code</th><th>Name</th><th>Status</th><th>is_active</th><th>Type</th></tr>";

        $result->data_seek(0); // Reset pointer
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['partner_code']}</td>";
            echo "<td>{$row['firstname']} {$row['lastname']}</td>";
            echo "<td>{$row['status']}</td>";
            echo "<td>" . ($row['is_active'] ? 'Yes' : 'No') . "</td>";
            echo "<td>" . ($row['type_name'] ?: 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='error'><span class='check'>✗</span> Model query returns NO data</p>";
        $issues[] = "Model query returns empty result";
    }

    // ========================================
    // CHECK 5: Test with is_active filter
    // ========================================
    echo "<h2>✓ Check 5: Filter by is_active = 1</h2>";

    $query = "
        SELECT partners.*,
               giving_frequencies.name as frequency_name,
               giving_types.name as type_name
        FROM partners
        LEFT JOIN giving_frequencies ON giving_frequencies.id = partners.giving_frequency_id
        LEFT JOIN giving_types ON giving_types.id = partners.giving_type_id
        WHERE partners.is_active = 1
        ORDER BY partners.created_at DESC
    ";

    $result = $conn->query($query);
    echo "<p>Active partners from filtered query: <strong>{$result->num_rows}</strong></p>";

    if ($result->num_rows > 0) {
        echo "<p class='success'><span class='check'>✓</span> Active partners query works</p>";
        $checks_passed++;
    } else {
        echo "<p class='error'><span class='check'>✗</span> No active partners in filtered query</p>";
    }

    // ========================================
    // CHECK 6: Test Controller Response Format
    // ========================================
    echo "<h2>✓ Check 6: Simulate Controller Response</h2>";

    $result = $conn->query("
        SELECT partners.*,
               giving_frequencies.name as frequency_name,
               giving_types.name as type_name
        FROM partners
        LEFT JOIN giving_frequencies ON giving_frequencies.id = partners.giving_frequency_id
        LEFT JOIN giving_types ON giving_types.id = partners.giving_type_id
        ORDER BY partners.created_at DESC
    ");

    $response_data = [];
    while ($partner = $result->fetch_object()) {
        // Get contribution total
        $total_result = $conn->query("
            SELECT SUM(amount) as total
            FROM partner_contributions
            WHERE partner_id = {$partner->id} AND status = 'completed'
        ");
        $total_row = $total_result->fetch_assoc();
        $total_contributions = $total_row['total'] ?? 0;

        $response_data[] = [
            $partner->partner_code,
            $partner->firstname . ' ' . $partner->lastname,
            $partner->email,
            $partner->mobileno,
            $partner->type_name ? $partner->type_name : '-',
            $partner->frequency_name ? $partner->frequency_name : '-',
            $partner->currency . ' ' . number_format($partner->contribution_amount, 2),
            $partner->currency . ' ' . number_format($total_contributions, 2),
            $partner->start_date ? date('Y-m-d', strtotime($partner->start_date)) : '-',
            ucfirst($partner->status)
        ];
    }

    $json_response = json_encode(['data' => $response_data]);

    echo "<p>Records in response: <strong>" . count($response_data) . "</strong></p>";

    if (count($response_data) > 0) {
        echo "<p class='success'><span class='check'>✓</span> Controller would return data</p>";
        $checks_passed++;

        echo "<h4>Sample JSON Response (what DataTable expects):</h4>";
        echo "<div class='code-box'>";
        echo htmlspecialchars(json_encode(['data' => array_slice($response_data, 0, 2)], JSON_PRETTY_PRINT));
        echo "</div>";
    } else {
        echo "<p class='error'><span class='check'>✗</span> Controller would return EMPTY data</p>";
        $issues[] = "Controller returns empty array";
    }

    // ========================================
    // CHECK 7: Check Contribution Data
    // ========================================
    echo "<h2>✓ Check 7: Contributions Data</h2>";

    $result = $conn->query("SELECT COUNT(*) as count FROM partner_contributions");
    $row = $result->fetch_assoc();
    $total_contributions = $row['count'];

    $result = $conn->query("
        SELECT COUNT(*) as count
        FROM partner_contributions
        WHERE status = 'completed'
    ");
    $row = $result->fetch_assoc();
    $completed_contributions = $row['count'];

    echo "<p>Total contributions: <strong>{$total_contributions}</strong></p>";
    echo "<p>Completed contributions: <strong>{$completed_contributions}</strong></p>";

    if ($total_contributions > 0) {
        echo "<p class='success'><span class='check'>✓</span> Contributions exist</p>";
        $checks_passed++;
    } else {
        echo "<p class='warning'><span class='check'>⚠</span> No contributions yet</p>";
    }

    // ========================================
    // CHECK 8: Test Direct URL Access
    // ========================================
    echo "<h2>✓ Check 8: AJAX Endpoint Accessibility</h2>";

    $ajax_url = "admin/partnerreports/getPartnerInformationData";
    $full_url = "http://localhost/rhemazimbabwe/" . $ajax_url;

    echo "<p>AJAX endpoint: <code>{$ajax_url}</code></p>";
    echo "<p><strong>Testing this requires actual login session</strong></p>";

    echo "<div class='code-box'>";
    echo "To test manually:<br><br>";
    echo "1. Open browser DevTools (F12)<br>";
    echo "2. Go to Network tab<br>";
    echo "3. Load report page<br>";
    echo "4. Look for POST request to: {$ajax_url}<br>";
    echo "5. Check Response tab to see actual data returned<br>";
    echo "</div>";

    $checks_passed++;

    // ========================================
    // SUMMARY
    // ========================================
    $progress = ($checks_passed / $total_checks) * 100;

    echo "<h2>📊 Summary</h2>";
    echo "<p><strong>Checks Passed: {$checks_passed} / {$total_checks}</strong> (" . round($progress) . "%)</p>";

    if (empty($issues)) {
        echo "<div style='background:#d4edda; padding:20px; border-left:4px solid #28a745; border-radius:4px;'>";
        echo "<h3 style='color:#28a745; margin-top:0;'>✓ All Checks Passed!</h3>";
        echo "<p>The database and queries are working correctly.</p>";

        echo "<h4>If reports still show 'no data', the issue is likely:</h4>";
        echo "<ol>";
        echo "<li><strong>Session/Permission:</strong> Your logged-in user might not have the permission</li>";
        echo "<li><strong>JavaScript Error:</strong> Check browser console (F12) for errors</li>";
        echo "<li><strong>AJAX Failure:</strong> The AJAX request might be failing silently</li>";
        echo "<li><strong>Model Filter Issue:</strong> Partner_model->getAll() might have a default filter that's too restrictive</li>";
        echo "</ol>";

        echo "<h4>Immediate Actions:</h4>";
        echo "<ol>";
        echo "<li><strong>Logout and Login again</strong> (to refresh permissions)</li>";
        echo "<li><strong>Clear browser cache</strong> (Ctrl + Shift + Delete)</li>";
        echo "<li><strong>Open browser console</strong> (F12) and check for errors</li>";
        echo "<li><strong>Check Network tab</strong> to see AJAX response</li>";
        echo "</ol>";
        echo "</div>";

        // Show the fix for Partner_model
        echo "<h3>🔧 Potential Fix: Update Partner_model->getAll()</h3>";
        echo "<p>The Partner_model might need to default to active partners. Check if this line exists:</p>";
        echo "<div class='code-box'>";
        echo "// In Partner_model.php getAll() method<br><br>";
        echo "// If is_active filter not set, default to active<br>";
        echo "if (!isset(\$filters['is_active'])) {<br>";
        echo "&nbsp;&nbsp;&nbsp;&nbsp;\$filters['is_active'] = 1;<br>";
        echo "}<br>";
        echo "</div>";

    } else {
        echo "<div style='background:#f8d7da; padding:20px; border-left:4px solid #dc3545; border-radius:4px;'>";
        echo "<h3 style='color:#dc3545; margin-top:0;'>✗ Issues Found</h3>";
        echo "<ul>";
        foreach ($issues as $issue) {
            echo "<li class='error'>{$issue}</li>";
        }
        echo "</ul>";
        echo "<p><strong>Fix these issues first, then re-run this diagnostic.</strong></p>";
        echo "</div>";
    }

    // ========================================
    // Data Analysis
    // ========================================
    echo "<h2>📈 Data Analysis</h2>";

    echo "<h3>Partner Status Breakdown:</h3>";
    $result = $conn->query("
        SELECT status, is_active, COUNT(*) as count
        FROM partners
        GROUP BY status, is_active
    ");
    echo "<table>";
    echo "<tr><th>Status</th><th>is_active</th><th>Count</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['status']}</td>";
        echo "<td>" . ($row['is_active'] ? 'Yes' : 'No') . "</td>";
        echo "<td>{$row['count']}</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<h3>Contributions by Partner:</h3>";
    $result = $conn->query("
        SELECT p.id, p.firstname, p.lastname, p.is_active,
               COUNT(pc.id) as contribution_count,
               SUM(pc.amount) as total_amount
        FROM partners p
        LEFT JOIN partner_contributions pc ON pc.partner_id = p.id AND pc.status = 'completed'
        GROUP BY p.id
    ");

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Partner</th><th>Active</th><th>Contributions</th><th>Total Amount</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['firstname']} {$row['lastname']}</td>";
            echo "<td>" . ($row['is_active'] ? 'Yes' : 'No') . "</td>";
            echo "<td>{$row['contribution_count']}</td>";
            echo "<td>" . number_format($row['total_amount'] ?? 0, 2) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    $conn->close();
    ?>

    <hr style="margin: 40px 0;">
    <p style="text-align:center; color:#666;">Diagnostic completed at <?php echo date('Y-m-d H:i:s'); ?></p>
</div>
</body>
</html>
