<?php
// Test Partner Reports Data
define('BASEPATH', true);

$conn = new mysqli('localhost', 'root', '', 'ssdb');

if ($conn->connect_error) {
    die("Connection failed");
}

echo "<h2>Testing Partner Reports Data</h2>";
echo "<hr>";

// Test 1: Check partners exist
echo "<h3>1. Partners in Database</h3>";
$result = $conn->query("SELECT * FROM partners WHERE is_active = 1");
echo "<p>Active Partners: " . $result->num_rows . "</p>";
if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Code</th><th>Name</th><th>Email</th><th>Type ID</th><th>Frequency ID</th><th>Status</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['partner_code']}</td>";
        echo "<td>{$row['firstname']} {$row['lastname']}</td>";
        echo "<td>{$row['email']}</td>";
        echo "<td>{$row['giving_type_id']}</td>";
        echo "<td>{$row['giving_frequency_id']}</td>";
        echo "<td>{$row['status']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red;'>No active partners found!</p>";
}

echo "<hr>";

// Test 2: Check contributions
echo "<h3>2. Contributions in Database</h3>";
$result = $conn->query("SELECT COUNT(*) as count FROM partner_contributions");
$row = $result->fetch_assoc();
echo "<p>Total Contributions: {$row['count']}</p>";

echo "<hr>";

// Test 3: Test JOIN query (what Partner_model->getAll() should do)
echo "<h3>3. Partners with JOINs (Like Partner_model->getAll())</h3>";
$query = "
    SELECT
        p.*,
        gt.name as type_name,
        gf.name as frequency_name
    FROM partners p
    LEFT JOIN giving_types gt ON gt.id = p.giving_type_id
    LEFT JOIN giving_frequencies gf ON gf.id = p.giving_frequency_id
    WHERE p.is_active = 1
";

$result = $conn->query($query);
echo "<p>Partners Retrieved: " . $result->num_rows . "</p>";

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Code</th><th>Name</th><th>Email</th><th>Type</th><th>Frequency</th><th>Pledged</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['partner_code']}</td>";
        echo "<td>{$row['firstname']} {$row['lastname']}</td>";
        echo "<td>{$row['email']}</td>";
        echo "<td>" . ($row['type_name'] ?: 'N/A') . "</td>";
        echo "<td>" . ($row['frequency_name'] ?: 'N/A') . "</td>";
        echo "<td>{$row['currency']} {$row['contribution_amount']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<hr>";

// Test 4: Test contribution totals per partner
echo "<h3>4. Contribution Totals Per Partner</h3>";
$result = $conn->query("SELECT id, firstname, lastname FROM partners WHERE is_active = 1");
if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Partner</th><th>Total Contributions</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $partner_id = $row['id'];
        $total_result = $conn->query("
            SELECT SUM(amount) as total
            FROM partner_contributions
            WHERE partner_id = {$partner_id} AND status = 'completed'
        ");
        $total_row = $total_result->fetch_assoc();
        $total = $total_row['total'] ?? 0;

        echo "<tr>";
        echo "<td>{$row['firstname']} {$row['lastname']}</td>";
        echo "<td>" . number_format($total, 2) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<hr>";

// Test 5: Simulate the exact data structure returned by controller
echo "<h3>5. Simulated Controller Response (What DataTable Expects)</h3>";

$partners = [];
$result = $conn->query("
    SELECT
        p.*,
        gt.name as type_name,
        gf.name as frequency_name
    FROM partners p
    LEFT JOIN giving_types gt ON gt.id = p.giving_type_id
    LEFT JOIN giving_frequencies gf ON gf.id = p.giving_frequency_id
    WHERE p.is_active = 1
");

$data = [];
while ($partner = $result->fetch_object()) {
    // Get total contributions
    $total_result = $conn->query("
        SELECT SUM(amount) as total
        FROM partner_contributions
        WHERE partner_id = {$partner->id} AND status = 'completed'
    ");
    $total_row = $total_result->fetch_assoc();
    $total_contributions = $total_row['total'] ?? 0;

    $data[] = [
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

$response = ['data' => $data];
echo "<pre>";
echo json_encode($response, JSON_PRETTY_PRINT);
echo "</pre>";

echo "<p><strong>Number of records in response: " . count($data) . "</strong></p>";

if (count($data) == 0) {
    echo "<p style='color:red;font-weight:bold;'>⚠️ NO DATA RETURNED - This is why DataTable shows 'No data available'!</p>";

    // Additional debugging
    echo "<h4>Debugging Steps:</h4>";
    echo "<ol>";
    echo "<li>Check if partners table has is_active = 1</li>";
    echo "<li>Check if partner_model->getAll() method exists and works</li>";
    echo "<li>Check if there are any PHP errors in logs</li>";
    echo "<li>Verify RBAC permissions allow access</li>";
    echo "</ol>";
} else {
    echo "<p style='color:green;font-weight:bold;'>✓ Data looks good! The query should work.</p>";
}

echo "<hr>";

// Test 6: Check for common issues
echo "<h3>6. Common Issues Check</h3>";

echo "<h4>A. Check giving_types table:</h4>";
$result = $conn->query("SELECT * FROM giving_types WHERE is_active = 1");
echo "<p>Active Giving Types: " . $result->num_rows . "</p>";

echo "<h4>B. Check giving_frequencies table:</h4>";
$result = $conn->query("SELECT * FROM giving_frequencies WHERE is_active = 1");
echo "<p>Active Giving Frequencies: " . $result->num_rows . "</p>";

echo "<h4>C. Check partners.status column:</h4>";
$result = $conn->query("SHOW COLUMNS FROM partners LIKE 'status'");
$col = $result->fetch_assoc();
echo "<p>Status Column Type: {$col['Type']}</p>";

echo "<h4>D. Check partners.is_active column:</h4>";
$result = $conn->query("SHOW COLUMNS FROM partners LIKE 'is_active'");
$col = $result->fetch_assoc();
echo "<p>is_active Column Type: {$col['Type']}</p>";

echo "<h4>E. Partner status values:</h4>";
$result = $conn->query("SELECT status, COUNT(*) as count FROM partners GROUP BY status");
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Status</th><th>Count</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>{$row['status']}</td><td>{$row['count']}</td></tr>";
}
echo "</table>";

echo "<h4>F. Partner is_active values:</h4>";
$result = $conn->query("SELECT is_active, COUNT(*) as count FROM partners GROUP BY is_active");
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>is_active</th><th>Count</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>{$row['is_active']}</td><td>{$row['count']}</td></tr>";
}
echo "</table>";

$conn->close();
?>
