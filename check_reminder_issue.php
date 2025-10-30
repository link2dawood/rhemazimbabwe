<?php
$conn = new mysqli('localhost', 'root', '', 'ssdb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Partner Reminders Table Structure</h2>";
$result = $conn->query("DESCRIBE partner_reminders");
echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$row['Field']}</td>";
    echo "<td>{$row['Type']}</td>";
    echo "<td>{$row['Null']}</td>";
    echo "<td>{$row['Key']}</td>";
    echo "<td>{$row['Default']}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h2>Test Reminder Insert</h2>";

// Test insert
$test_data = array(
    'partner_id' => 4,
    'title' => 'Test Reminder',
    'reminder_type' => 'custom',
    'reminder_date' => date('Y-m-d'),
    'reminder_time' => '10:00:00',
    'message' => 'Test message',
    'send_via' => 'email',
    'status' => 'pending',
    'is_active' => 1,
    'created_by' => 1,
    'created_at' => date('Y-m-d H:i:s')
);

$fields = array();
$values = array();
foreach ($test_data as $key => $value) {
    $fields[] = "`$key`";
    $values[] = "'" . $conn->real_escape_string($value) . "'";
}

$sql = "INSERT INTO partner_reminders (" . implode(", ", $fields) . ") VALUES (" . implode(", ", $values) . ")";
echo "<p><strong>SQL Query:</strong></p>";
echo "<pre>" . htmlspecialchars($sql) . "</pre>";

if ($conn->query($sql)) {
    echo "<p style='color: green;'>✓ Test insert successful! ID: " . $conn->insert_id . "</p>";

    // Clean up test data
    $conn->query("DELETE FROM partner_reminders WHERE id = " . $conn->insert_id);
    echo "<p><small>Test data cleaned up.</small></p>";
} else {
    echo "<p style='color: red;'>✗ Insert failed: " . $conn->error . "</p>";
}

$conn->close();
?>
