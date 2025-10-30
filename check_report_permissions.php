<?php
// Check Report Permissions
$conn = new mysqli('localhost', 'root', '', 'ssdb');

if ($conn->connect_error) {
    die("Connection failed");
}

echo "<h2>Partner Reports Permission Check</h2>";
echo "<hr>";

// Check if partner_reports permission exists
echo "<h3>1. Check permission_category for 'partner_reports'</h3>";
$result = $conn->query("SELECT * FROM permission_category WHERE short_code = 'partner_reports'");

if ($result->num_rows > 0) {
    echo "<p style='color:green;'>✓ Permission 'partner_reports' EXISTS</p>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Name</th><th>Short Code</th><th>Enable View</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['short_code']}</td>";
        echo "<td>{$row['enable_view']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red;'>✗ Permission 'partner_reports' DOES NOT EXIST!</p>";
    echo "<p><strong>This is the problem! The permission needs to be created.</strong></p>";
}

echo "<hr>";

// Check role_permissions for partner_reports
echo "<h3>2. Check role_permissions for 'partner_reports'</h3>";
$result = $conn->query("SELECT rp.*, r.name as role_name
                        FROM role_permissions rp
                        JOIN roles r ON r.id = rp.role_id
                        WHERE rp.feature_name = 'partner_reports'");

if ($result->num_rows > 0) {
    echo "<p style='color:green;'>✓ Role permissions for 'partner_reports' exist</p>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Role</th><th>Can View</th><th>Can Add</th><th>Can Edit</th><th>Can Delete</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['role_name']}</td>";
        echo "<td>" . ($row['can_view'] ? '✓' : '✗') . "</td>";
        echo "<td>" . ($row['can_add'] ? '✓' : '✗') . "</td>";
        echo "<td>" . ($row['can_edit'] ? '✓' : '✗') . "</td>";
        echo "<td>" . ($row['can_delete'] ? '✓' : '✗') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red;'>✗ NO role permissions for 'partner_reports'</p>";
    echo "<p><strong>Roles don't have permission to view partner reports!</strong></p>";
}

echo "<hr>";

// Check 'partners' permission (might be using this instead)
echo "<h3>3. Check 'partners' permission (fallback)</h3>";
$result = $conn->query("SELECT * FROM permission_category WHERE short_code = 'partners'");

if ($result->num_rows > 0) {
    echo "<p style='color:green;'>✓ Permission 'partners' EXISTS</p>";
    $row = $result->fetch_assoc();
    $perm_group_id = $row['perm_group_id'];
    echo "<p>Permission Group ID: {$perm_group_id}</p>";
} else {
    echo "<p style='color:red;'>✗ Permission 'partners' also missing</p>";
}

echo "<hr>";

// Check all partner-related permissions
echo "<h3>4. All Partner-Related Permissions</h3>";
$result = $conn->query("SELECT * FROM permission_category WHERE short_code LIKE '%partner%' OR name LIKE '%Partner%'");

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Name</th><th>Short Code</th><th>Perm Group ID</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['short_code']}</td>";
        echo "<td>{$row['perm_group_id']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No partner-related permissions found.</p>";
}

echo "<hr>";
echo "<h3>5. FIX: SQL Commands</h3>";

// Check if fix is needed
$result = $conn->query("SELECT * FROM permission_category WHERE short_code = 'partner_reports'");
if ($result->num_rows == 0) {
    echo "<p><strong>Run this SQL to fix the permission issue:</strong></p>";
    echo "<pre>";
    echo "-- First, get the permission group ID for partners\n";
    echo "SET @perm_group_id = (SELECT perm_group_id FROM permission_category WHERE short_code = 'partners' LIMIT 1);\n\n";

    echo "-- Add partner_reports permission\n";
    echo "INSERT INTO permission_category (perm_group_id, name, short_code, enable_view, enable_add, enable_edit, enable_delete, created_at)\n";
    echo "VALUES (@perm_group_id, 'Partner Reports', 'partner_reports', 1, 0, 0, 0, NOW());\n\n";

    echo "-- Grant permission to Admin role\n";
    echo "INSERT INTO role_permissions (role_id, feature_name, can_view, can_add, can_edit, can_delete)\n";
    echo "SELECT id, 'partner_reports', 1, 0, 0, 0 FROM roles WHERE name = 'Admin'\n";
    echo "ON DUPLICATE KEY UPDATE can_view = 1;\n\n";

    echo "-- Grant permission to all existing roles that have partners permission\n";
    echo "INSERT INTO role_permissions (role_id, feature_name, can_view, can_add, can_edit, can_delete)\n";
    echo "SELECT role_id, 'partner_reports', can_view, 0, 0, 0\n";
    echo "FROM role_permissions\n";
    echo "WHERE feature_name = 'partners'\n";
    echo "ON DUPLICATE KEY UPDATE can_view = VALUES(can_view);\n";
    echo "</pre>";

    // Offer to run the fix
    if (isset($_POST['run_fix'])) {
        echo "<h4>Running Fix...</h4>";

        $result = $conn->query("SELECT perm_group_id FROM permission_category WHERE short_code = 'partners' LIMIT 1");
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $perm_group_id = $row['perm_group_id'];

            // Insert permission_category
            $sql = "INSERT INTO permission_category (perm_group_id, name, short_code, enable_view, enable_add, enable_edit, enable_delete, created_at)
                    VALUES ({$perm_group_id}, 'Partner Reports', 'partner_reports', 1, 0, 0, 0, NOW())";

            if ($conn->query($sql)) {
                echo "<p style='color:green;'>✓ Created permission_category entry</p>";
            } else {
                echo "<p style='color:red;'>✗ Error: " . $conn->error . "</p>";
            }

            // Grant to Admin role
            $sql = "INSERT INTO role_permissions (role_id, feature_name, can_view, can_add, can_edit, can_delete)
                    SELECT id, 'partner_reports', 1, 0, 0, 0 FROM roles WHERE name = 'Admin'
                    ON DUPLICATE KEY UPDATE can_view = 1";

            if ($conn->query($sql)) {
                echo "<p style='color:green;'>✓ Granted permission to Admin role</p>";
            } else {
                echo "<p style='color:red;'>✗ Error: " . $conn->error . "</p>";
            }

            // Copy from partners permission
            $sql = "INSERT INTO role_permissions (role_id, feature_name, can_view, can_add, can_edit, can_delete)
                    SELECT role_id, 'partner_reports', can_view, 0, 0, 0
                    FROM role_permissions
                    WHERE feature_name = 'partners'
                    ON DUPLICATE KEY UPDATE can_view = VALUES(can_view)";

            if ($conn->query($sql)) {
                echo "<p style='color:green;'>✓ Copied permissions from 'partners' to 'partner_reports'</p>";
            } else {
                echo "<p style='color:red;'>✗ Error: " . $conn->error . "</p>";
            }

            echo "<p><strong style='color:green;'>FIX COMPLETED! Now logout and login again, then try the reports.</strong></p>";
        }
    } else {
        echo "<form method='POST'>";
        echo "<button type='submit' name='run_fix' style='padding:10px 20px; background:#28a745; color:white; border:none; border-radius:5px; cursor:pointer; font-size:16px;'>";
        echo "Click Here to Run Fix Automatically";
        echo "</button>";
        echo "</form>";
    }
} else {
    echo "<p style='color:green;'>✓ Permission already exists. The issue might be with role assignments.</p>";

    // Check if user's role has permission
    echo "<h4>Check Your Role Permissions:</h4>";
    echo "<p>To verify your account has permission, run this in SQL:</p>";
    echo "<pre>";
    echo "SELECT rp.*, r.name as role_name\n";
    echo "FROM role_permissions rp\n";
    echo "JOIN user_roles ur ON ur.role_id = rp.role_id\n";
    echo "JOIN roles r ON r.id = rp.role_id\n";
    echo "WHERE ur.user_id = YOUR_USER_ID\n";
    echo "AND rp.feature_name = 'partner_reports';\n";
    echo "</pre>";
}

$conn->close();
?>
