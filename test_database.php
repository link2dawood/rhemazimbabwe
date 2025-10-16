<?php
// Simple database test script
// Place this in your root directory and run: http://localhost/rhemazimbabwe/test_database.php

// Load CodeIgniter
require_once 'index.php';

// Get CodeIgniter instance
$CI =& get_instance();

// Load database
$CI->load->database();

echo "<h2>Database Connection Test</h2>";

// Test database connection
if ($CI->db->conn_id) {
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
} else {
    echo "<p style='color: red;'>❌ Database connection failed!</p>";
    exit;
}

// Check if giving_types table exists
echo "<h3>Checking Tables:</h3>";

$tables_to_check = [
    'giving_types',
    'giving_frequencies', 
    'partners',
    'partner_contributions',
    'partner_giving_settings'
];

foreach ($tables_to_check as $table) {
    if ($CI->db->table_exists($table)) {
        $count = $CI->db->count_all($table);
        echo "<p style='color: green;'>✅ Table '{$table}' exists ({$count} records)</p>";
    } else {
        echo "<p style='color: red;'>❌ Table '{$table}' does not exist</p>";
    }
}

// Test giving_types data
echo "<h3>Testing giving_types data:</h3>";
$query = $CI->db->get('giving_types');
$types = $query->result();

if (empty($types)) {
    echo "<p style='color: orange;'>⚠️ No giving types found. Let's create some test data...</p>";
    
    // Insert test data
    $test_types = [
        ['name' => 'General Fund', 'code' => 'general', 'description' => 'General school fund', 'is_active' => 1],
        ['name' => 'Scholarship Fund', 'code' => 'scholarship', 'description' => 'Student scholarships', 'is_active' => 1],
        ['name' => 'Infrastructure', 'code' => 'infrastructure', 'description' => 'Building and facilities', 'is_active' => 1]
    ];
    
    foreach ($test_types as $type) {
        $CI->db->insert('giving_types', $type);
    }
    
    echo "<p style='color: green;'>✅ Test giving types created!</p>";
} else {
    echo "<p style='color: green;'>✅ Found " . count($types) . " giving types:</p>";
    echo "<ul>";
    foreach ($types as $type) {
        echo "<li>{$type->name} ({$type->code})</li>";
    }
    echo "</ul>";
}

// Test giving_frequencies data
echo "<h3>Testing giving_frequencies data:</h3>";
$query = $CI->db->get('giving_frequencies');
$frequencies = $query->result();

if (empty($frequencies)) {
    echo "<p style='color: orange;'>⚠️ No giving frequencies found. Let's create some test data...</p>";
    
    // Insert test data
    $test_frequencies = [
        ['name' => 'Once-Off', 'code' => 'once_off', 'days_interval' => null, 'description' => 'One time contribution', 'is_active' => 1],
        ['name' => 'Weekly', 'code' => 'weekly', 'days_interval' => 7, 'description' => 'Weekly contributions', 'is_active' => 1],
        ['name' => 'Monthly', 'code' => 'monthly', 'days_interval' => 30, 'description' => 'Monthly contributions', 'is_active' => 1],
        ['name' => 'Quarterly', 'code' => 'quarterly', 'days_interval' => 90, 'description' => 'Quarterly contributions', 'is_active' => 1],
        ['name' => 'Annually', 'code' => 'annually', 'days_interval' => 365, 'description' => 'Annual contributions', 'is_active' => 1]
    ];
    
    foreach ($test_frequencies as $frequency) {
        $CI->db->insert('giving_frequencies', $frequency);
    }
    
    echo "<p style='color: green;'>✅ Test giving frequencies created!</p>";
} else {
    echo "<p style='color: green;'>✅ Found " . count($frequencies) . " giving frequencies:</p>";
    echo "<ul>";
    foreach ($frequencies as $frequency) {
        echo "<li>{$frequency->name} ({$frequency->code})</li>";
    }
    echo "</ul>";
}

echo "<h3>Test Complete!</h3>";
echo "<p><a href='partner_registration'>Try Partner Registration Now</a></p>";
?>
