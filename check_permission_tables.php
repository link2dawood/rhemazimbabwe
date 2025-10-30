<?php
$conn = new mysqli('localhost', 'root', '', 'ssdb');

echo "<h2>Permission System Structure</h2><hr>";

// List all permission-related tables
echo "<h3>Permission-Related Tables:</h3>";
$result = $conn->query("SHOW TABLES LIKE '%permission%'");
echo "<ul>";
while ($row = $result->fetch_array()) {
    echo "<li><strong>{$row[0]}</strong></li>";
}
echo "</ul>";

$result = $conn->query("SHOW TABLES LIKE '%role%'");
echo "<h3>Role-Related Tables:</h3>";
echo "<ul>";
while ($row = $result->fetch_array()) {
    echo "<li><strong>{$row[0]}</strong></li>";
}
echo "</ul>";

echo "<hr>";

// Check permission_category table
echo "<h3>Checking permission_category</h3>";
$result = $conn->query("SELECT * FROM permission_category WHERE short_code LIKE '%partner%'");
echo "<p>Partner-related permissions: " . $result->num_rows . "</p>";

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Name</th><th>Short Code</th><th>Perm Group ID</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['short_code']}</td><td>{$row['perm_group_id']}</td></tr>";
    }
    echo "</table>";
}

echo "<hr>";

// Check what permission system is actually used
echo "<h3>Sample Permission Entry:</h3>";
$result = $conn->query("SELECT * FROM permission_category LIMIT 1");
if ($row = $result->fetch_assoc()) {
    echo "<pre>";
    print_r($row);
    echo "</pre>";
}

$conn->close();
?>