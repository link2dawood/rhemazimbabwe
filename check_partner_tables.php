<?php
$conn = new mysqli('localhost', 'root', '', 'ssdb');

echo "<h2>Partner-Related Tables</h2>";
$result = $conn->query("SHOW TABLES LIKE '%partner%'");
while ($row = $result->fetch_array()) {
    echo "<h3>{$row[0]}</h3>";
    $cols = $conn->query("DESCRIBE {$row[0]}");
    echo "<table border='1'><tr><th>Field</th><th>Type</th></tr>";
    while ($col = $cols->fetch_assoc()) {
        echo "<tr><td>{$col['Field']}</td><td>{$col['Type']}</td></tr>";
    }
    echo "</table>";
}

echo "<h2>Permission-Related Tables</h2>";
$result = $conn->query("SHOW TABLES LIKE '%permission%'");
while ($row = $result->fetch_array()) {
    echo "<p><strong>{$row[0]}</strong></p>";
}
