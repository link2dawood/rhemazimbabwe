<?php
// Test Partner Reminders Table Structure
// Run: http://localhost/rhemazimbabwe/test_partner_reminders_table.php

echo "<h1>Partner Reminders Table Structure Test</h1>";

// Test database connection
require_once 'index.php';
$CI =& get_instance();
$CI->load->database();

echo "<h2>1. Database Connection Test</h2>";
if ($CI->db->conn_id) {
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
} else {
    echo "<p style='color: red;'>❌ Database connection failed!</p>";
    exit;
}

echo "<h2>2. Table Existence Check</h2>";
try {
    $result = $CI->db->query("SHOW TABLES LIKE 'partner_reminders'");
    if ($result->num_rows() > 0) {
        echo "<p style='color: green;'>✅ Table 'partner_reminders' exists</p>";
    } else {
        echo "<p style='color: red;'>❌ Table 'partner_reminders' does not exist</p>";
        echo "<p style='color: orange;'>⚠️ Need to run migration or create table manually</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error checking table existence: " . $e->getMessage() . "</p>";
}

echo "<h2>3. Table Structure Check</h2>";
try {
    $result = $CI->db->query("DESCRIBE partner_reminders");
    $columns = $result->result_array();
    
    echo "<p style='color: green;'>✅ Table structure retrieved successfully</p>";
    echo "<h3>Current Columns:</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    $required_columns = ['id', 'partner_id', 'reminder_type', 'reminder_date', 'reminder_time', 'title', 'message', 'send_via', 'status', 'is_active', 'created_by', 'created_at', 'updated_at'];
    $existing_columns = array();
    
    foreach ($columns as $column) {
        $existing_columns[] = $column['Field'];
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "<td>" . $column['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>Missing Columns Check:</h3>";
    $missing_columns = array_diff($required_columns, $existing_columns);
    if (empty($missing_columns)) {
        echo "<p style='color: green;'>✅ All required columns exist</p>";
    } else {
        echo "<p style='color: red;'>❌ Missing columns: " . implode(', ', $missing_columns) . "</p>";
    }
    
    // Check specifically for message column
    if (in_array('message', $existing_columns)) {
        echo "<p style='color: green;'>✅ 'message' column exists</p>";
    } else {
        echo "<p style='color: red;'>❌ 'message' column missing</p>";
    }
    
    // Check for is_active column
    if (in_array('is_active', $existing_columns)) {
        echo "<p style='color: green;'>✅ 'is_active' column exists</p>";
    } else {
        echo "<p style='color: red;'>❌ 'is_active' column missing</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error checking table structure: " . $e->getMessage() . "</p>";
}

echo "<h2>4. Migration Status Check</h2>";
try {
    $result = $CI->db->query("SELECT * FROM migrations WHERE version = '131'");
    if ($result->num_rows() > 0) {
        echo "<p style='color: green;'>✅ Migration 131 has been run</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Migration 131 has not been run</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error checking migration status: " . $e->getMessage() . "</p>";
}

echo "<h2>5. Fix Recommendations</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
echo "<h3>If table doesn't exist:</h3>";
echo "<ol>";
echo "<li>Run migration: <code>php index.php migrate</code></li>";
echo "<li>Or manually create table using the migration file</li>";
echo "</ol>";

echo "<h3>If table exists but missing columns:</h3>";
echo "<ol>";
echo "<li>Add missing columns manually</li>";
echo "<li>Or drop and recreate table</li>";
echo "</ol>";

echo "<h3>If migration needs to be run:</h3>";
echo "<ol>";
echo "<li>Check migration file: <code>application/migrations/131_create_partner_reminders_table.php</code></li>";
echo "<li>Run migration from command line or admin panel</li>";
echo "</ol>";
echo "</div>";

echo "<h2>6. SQL Fix Commands</h2>";
echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
echo "<h3>Add missing columns (if needed):</h3>";
echo "<pre>";
echo "-- Add message column if missing\n";
echo "ALTER TABLE partner_reminders ADD COLUMN message TEXT NULL AFTER title;\n\n";
echo "-- Add is_active column if missing\n";
echo "ALTER TABLE partner_reminders ADD COLUMN is_active TINYINT(1) DEFAULT 1 AFTER status;\n\n";
echo "-- Add created_by column if missing\n";
echo "ALTER TABLE partner_reminders ADD COLUMN created_by INT(11) UNSIGNED NULL AFTER next_reminder_date;\n\n";
echo "-- Add created_at column if missing\n";
echo "ALTER TABLE partner_reminders ADD COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER created_by;\n\n";
echo "-- Add updated_at column if missing\n";
echo "ALTER TABLE partner_reminders ADD COLUMN updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at;";
echo "</pre>";
echo "</div>";

echo "<p><strong>Test completed!</strong> Check the results above to determine what needs to be fixed.</p>";
?>
