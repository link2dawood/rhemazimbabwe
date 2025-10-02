<?php
/**
 * TEMPORARY FILE - Get test login credentials
 * DELETE AFTER USE
 */

$db = new mysqli('localhost', 'root', '', 'ssdb');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

echo "<h2>ADMIN LOGINS</h2>";
$result = $db->query("SELECT s.employee_id, s.name, s.surname, s.email, r.name as role FROM staff s LEFT JOIN staff_roles sr ON s.id = sr.staff_id LEFT JOIN roles r ON sr.role_id = r.id WHERE r.name IN ('Super Admin', 'Admin') LIMIT 5");
if ($result && $result->num_rows > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Employee ID</th><th>Name</th><th>Email</th><th>Role</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['employee_id']) . "</td>";
        echo "<td>" . htmlspecialchars(($row['name'] ?? '') . ' ' . ($row['surname'] ?? '')) . "</td>";
        echo "<td>" . htmlspecialchars($row['email'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($row['role'] ?? 'N/A') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No admin accounts found</p>";
}

echo "<h2>PARTNER ACCOUNTS</h2>";
$result = $db->query("SELECT partner_code, username, firstname, lastname, email, status, account_type FROM partners LIMIT 5");
if ($result && $result->num_rows > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Partner Code</th><th>Username</th><th>Name</th><th>Email</th><th>Type</th><th>Status</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['partner_code'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($row['username'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars(($row['firstname'] ?? '') . ' ' . ($row['lastname'] ?? '')) . "</td>";
        echo "<td>" . htmlspecialchars($row['email'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($row['account_type'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($row['status'] ?? 'N/A') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No partner accounts found</p>";
}

echo "<h2>STUDENT LOGINS (Sample)</h2>";
$result = $db->query("SELECT admission_no, email, firstname, lastname FROM students WHERE is_active = 'yes' LIMIT 3");
if ($result && $result->num_rows > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Admission No</th><th>Email</th><th>Name</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['admission_no'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($row['email'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars(($row['firstname'] ?? '') . ' ' . ($row['lastname'] ?? '')) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No student accounts found</p>";
}

echo "<h2>PARENT/GUARDIAN INFO (Sample)</h2>";
$result = $db->query("SELECT admission_no, guardian_name, guardian_email, guardian_phone FROM students WHERE is_active = 'yes' AND guardian_name IS NOT NULL LIMIT 3");
if ($result && $result->num_rows > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Student Admission No</th><th>Guardian Name</th><th>Email</th><th>Phone</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['admission_no'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($row['guardian_name'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($row['guardian_email'] ?? 'N/A') . "</td>";
        echo "<td>" . htmlspecialchars($row['guardian_phone'] ?? 'N/A') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No guardian info found</p>";
}

$db->close();

echo "<br><br><p style='color:red;'><strong>⚠️ DELETE THIS FILE AFTER VIEWING!</strong></p>";
?>
