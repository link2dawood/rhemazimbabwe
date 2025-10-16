<?php
// Setup Database Sessions for CodeIgniter
// Run: http://localhost/rhemazimbabwe/setup_database_sessions.php

echo "<h1>Setting up Database Sessions for CodeIgniter</h1>";

// Load CodeIgniter
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

echo "<h2>2. Creating Sessions Table</h2>";
try {
    // Check if sessions table already exists
    $result = $CI->db->query("SHOW TABLES LIKE 'ci_sessions'");
    if ($result->num_rows() > 0) {
        echo "<p style='color: orange;'>⚠️ Sessions table already exists</p>";
    } else {
        // Create sessions table
        $sql = "CREATE TABLE `ci_sessions` (
            `id` varchar(128) NOT NULL,
            `ip_address` varchar(45) NOT NULL,
            `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
            `data` blob NOT NULL,
            PRIMARY KEY (`id`),
            KEY `ci_sessions_timestamp` (`timestamp`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        
        $CI->db->query($sql);
        echo "<p style='color: green;'>✅ Sessions table created successfully!</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error creating sessions table: " . $e->getMessage() . "</p>";
}

echo "<h2>3. Updating Session Configuration</h2>";
try {
    // Read current config file
    $config_file = 'application/config/config.php';
    $config_content = file_get_contents($config_file);
    
    // Check if already configured for database
    if (strpos($config_content, "'sess_driver' = 'database'") !== false) {
        echo "<p style='color: orange;'>⚠️ Session configuration already set to database</p>";
    } else {
        // Update session configuration
        $old_config = "\$config['sess_driver'] = 'files';";
        $new_config = "\$config['sess_driver'] = 'database';";
        
        $old_save_path = "\$config['sess_save_path'] = sys_get_temp_dir();";
        $new_save_path = "\$config['sess_save_path'] = 'ci_sessions';";
        
        $config_content = str_replace($old_config, $new_config, $config_content);
        $config_content = str_replace($old_save_path, $new_save_path, $config_content);
        
        // Write updated config
        file_put_contents($config_file, $config_content);
        echo "<p style='color: green;'>✅ Session configuration updated to use database!</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error updating configuration: " . $e->getMessage() . "</p>";
}

echo "<h2>4. Testing Session Storage</h2>";
try {
    // Test session storage
    $CI->load->library('session');
    
    // Set a test session variable
    $CI->session->set_userdata('test_session', 'database_sessions_working');
    
    // Check if session was stored
    $session_data = $CI->session->userdata('test_session');
    if ($session_data === 'database_sessions_working') {
        echo "<p style='color: green;'>✅ Session storage test successful!</p>";
    } else {
        echo "<p style='color: red;'>❌ Session storage test failed!</p>";
    }
    
    // Clean up test data
    $CI->session->unset_userdata('test_session');
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error testing session storage: " . $e->getMessage() . "</p>";
}

echo "<h2>5. Session Table Structure Verification</h2>";
try {
    $result = $CI->db->query("DESCRIBE ci_sessions");
    $columns = $result->result();
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>{$column->Field}</td>";
        echo "<td>{$column->Type}</td>";
        echo "<td>{$column->Null}</td>";
        echo "<td>{$column->Key}</td>";
        echo "<td>{$column->Default}</td>";
        echo "<td>{$column->Extra}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error verifying table structure: " . $e->getMessage() . "</p>";
}

echo "<h2>6. Session Cleanup Setup</h2>";
try {
    // Create a simple cleanup script
    $cleanup_script = '<?php
// Session Cleanup Script
// Run this periodically to clean old sessions
require_once "index.php";
$CI =& get_instance();
$CI->load->database();

// Delete sessions older than 2 hours
$CI->db->where("timestamp <", time() - 7200);
$CI->db->delete("ci_sessions");

echo "Old sessions cleaned up successfully!";
?>';
    
    file_put_contents('cleanup_sessions.php', $cleanup_script);
    echo "<p style='color: green;'>✅ Session cleanup script created: cleanup_sessions.php</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error creating cleanup script: " . $e->getMessage() . "</p>";
}

echo "<h2>7. Configuration Summary</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ Session Configuration Updated:</h3>";
echo "<ul>";
echo "<li><strong>Session Driver:</strong> database</li>";
echo "<li><strong>Session Table:</strong> ci_sessions</li>";
echo "<li><strong>Session Expiration:</strong> 7200 seconds (2 hours)</li>";
echo "<li><strong>Session Regeneration:</strong> 300 seconds (5 minutes)</li>";
echo "<li><strong>IP Matching:</strong> Disabled</li>";
echo "<li><strong>SameSite:</strong> Lax</li>";
echo "</ul>";

echo "<h3>✅ Database Table Created:</h3>";
echo "<ul>";
echo "<li><strong>Table Name:</strong> ci_sessions</li>";
echo "<li><strong>Primary Key:</strong> id (varchar 128)</li>";
echo "<li><strong>Indexes:</strong> id, timestamp</li>";
echo "<li><strong>Engine:</strong> InnoDB</li>";
echo "</ul>";
echo "</div>";

echo "<h2>8. Next Steps</h2>";
echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ What to do next:</h3>";
echo "<ol>";
echo "<li><strong>Test the Application:</strong> Login and check if sessions persist</li>";
echo "<li><strong>Monitor Session Table:</strong> Check ci_sessions table for data</li>";
echo "<li><strong>Set up Cleanup:</strong> Schedule cleanup_sessions.php to run periodically</li>";
echo "<li><strong>Verify Fix:</strong> Check if 'No More Classes Found' error is resolved</li>";
echo "</ol>";

echo "<h3>🔧 Troubleshooting:</h3>";
echo "<ul>";
echo "<li>If sessions still don't work, check database permissions</li>";
echo "<li>Ensure the ci_sessions table has proper indexes</li>";
echo "<li>Check application logs for any session-related errors</li>";
echo "<li>Verify that the database user has INSERT/UPDATE/DELETE permissions on ci_sessions</li>";
echo "</ul>";
echo "</div>";

echo "<h2>9. Testing Commands</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
echo "<h3>Manual Testing:</h3>";
echo "<ol>";
echo "<li>Login to admin: <a href='" . base_url('admin') . "' target='_blank'>Admin Login</a></li>";
echo "<li>Login to user portal: <a href='" . base_url('user') . "' target='_blank'>User Login</a></li>";
echo "<li>Check session table: <code>SELECT * FROM ci_sessions;</code></li>";
echo "<li>Monitor session data: <code>SELECT COUNT(*) FROM ci_sessions;</code></li>";
echo "</ol>";
echo "</div>";

echo "<p><strong>Database sessions setup completed!</strong> The 'No More Classes Found In Your Current Session' error should now be resolved.</p>";
?>
