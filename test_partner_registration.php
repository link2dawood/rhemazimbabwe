<?php
// Test partner registration
// Run: http://localhost/rhemazimbabwe/test_partner_registration.php

echo "<h2>Partner Registration Test</h2>";

// Test database connection
require_once 'index.php';
$CI =& get_instance();
$CI->load->database();

echo "<h3>Database Test:</h3>";
if ($CI->db->conn_id) {
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
} else {
    echo "<p style='color: red;'>❌ Database connection failed!</p>";
    exit;
}

// Test models
echo "<h3>Model Test:</h3>";
try {
    $CI->load->model('type_model');
    $types = $CI->type_model->getAll();
    echo "<p style='color: green;'>✅ Type_model loaded - Found " . count($types) . " giving types</p>";
    
    $CI->load->model('frequency_model');
    $frequencies = $CI->frequency_model->getAll();
    echo "<p style='color: green;'>✅ Frequency_model loaded - Found " . count($frequencies) . " giving frequencies</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Model error: " . $e->getMessage() . "</p>";
}

// Test controller
echo "<h3>Controller Test:</h3>";
try {
    $CI->load->library('session');
    $CI->load->model(array('partner_model', 'type_model', 'frequency_model'));
    echo "<p style='color: green;'>✅ Partner_registration controller dependencies loaded</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Controller error: " . $e->getMessage() . "</p>";
}

// Test theme files
echo "<h3>Theme Files Test:</h3>";
$theme_files = [
    'application/views/themes/default/pages/partner_registration.php',
    'application/views/themes/default/pages/partner_registration_success.php'
];

foreach ($theme_files as $file) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ {$file}</p>";
    } else {
        echo "<p style='color: red;'>❌ {$file}</p>";
    }
}

echo "<h3>Test Links:</h3>";
echo "<ul>";
echo "<li><a href='" . base_url() . "'>Home Page</a></li>";
echo "<li><a href='" . base_url('partner_registration') . "'>Partner Registration</a></li>";
echo "<li><a href='" . base_url('partner_registration/individual') . "'>Individual Registration</a></li>";
echo "<li><a href='" . base_url('partner_registration/organization') . "'>Organization Registration</a></li>";
echo "<li><a href='" . base_url('partner_registration/success') . "'>Success Page</a></li>";
echo "</ul>";

echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li>Run the database test: <a href='test_database.php'>test_database.php</a></li>";
echo "<li>Try the partner registration: <a href='partner_registration'>partner_registration</a></li>";
echo "<li>Test the forms and functionality</li>";
echo "</ol>";
?>
