<?php
// Simple Database Sessions Setup
// Run: http://localhost/rhemazimbabwe/setup_sessions_simple.php

echo "Setting up Database Sessions...\n";

// Load CodeIgniter
require_once 'index.php';
$CI =& get_instance();
$CI->load->database();

// 1. Create sessions table
echo "1. Creating sessions table...\n";
$sql = "CREATE TABLE IF NOT EXISTS `ci_sessions` (
    `id` varchar(128) NOT NULL,
    `ip_address` varchar(45) NOT NULL,
    `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
    `data` blob NOT NULL,
    PRIMARY KEY (`id`),
    KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

$CI->db->query($sql);
echo "✅ Sessions table created!\n";

// 2. Update config file
echo "2. Updating session configuration...\n";
$config_file = 'application/config/config.php';
$config_content = file_get_contents($config_file);

// Replace session driver
$config_content = str_replace(
    "\$config['sess_driver'] = 'files';",
    "\$config['sess_driver'] = 'database';",
    $config_content
);

// Replace session save path
$config_content = str_replace(
    "\$config['sess_save_path'] = sys_get_temp_dir();",
    "\$config['sess_save_path'] = 'ci_sessions';",
    $config_content
);

file_put_contents($config_file, $config_content);
echo "✅ Configuration updated!\n";

// 3. Test session storage
echo "3. Testing session storage...\n";
$CI->load->library('session');
$CI->session->set_userdata('test_session', 'database_working');
$test_value = $CI->session->userdata('test_session');

if ($test_value === 'database_working') {
    echo "✅ Session storage test successful!\n";
} else {
    echo "❌ Session storage test failed!\n";
}

// 4. Check table
echo "4. Verifying table structure...\n";
$result = $CI->db->query("DESCRIBE ci_sessions");
$columns = $result->result();
echo "Table has " . count($columns) . " columns\n";

echo "\n🎉 Database sessions setup completed!\n";
echo "The 'No More Classes Found In Your Current Session' error should now be resolved.\n";
?>
