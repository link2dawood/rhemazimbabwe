<?php
// Test models script
// Place this in your root directory and run: http://localhost/rhemazimbabwe/test_models.php

// Load CodeIgniter
require_once 'index.php';

// Get CodeIgniter instance
$CI =& get_instance();

echo "<h2>Model Testing</h2>";

try {
    // Load database
    $CI->load->database();
    echo "<p style='color: green;'>✅ Database loaded successfully</p>";
    
    // Test Type_model
    $CI->load->model('type_model');
    echo "<p style='color: green;'>✅ Type_model loaded successfully</p>";
    
    // Test getting giving types
    $types = $CI->type_model->getAll();
    echo "<p style='color: green;'>✅ Type_model->getAll() works - Found " . count($types) . " types</p>";
    
    // Test Frequency_model
    $CI->load->model('frequency_model');
    echo "<p style='color: green;'>✅ Frequency_model loaded successfully</p>";
    
    // Test getting giving frequencies
    $frequencies = $CI->frequency_model->getAll();
    echo "<p style='color: green;'>✅ Frequency_model->getAll() works - Found " . count($frequencies) . " frequencies</p>";
    
    echo "<h3>Test Results:</h3>";
    echo "<p style='color: green;'>✅ All models working correctly!</p>";
    
    echo "<h3>Sample Data:</h3>";
    echo "<h4>Giving Types:</h4>";
    echo "<ul>";
    foreach ($types as $type) {
        echo "<li>{$type->name} ({$type->code})</li>";
    }
    echo "</ul>";
    
    echo "<h4>Giving Frequencies:</h4>";
    echo "<ul>";
    foreach ($frequencies as $frequency) {
        echo "<li>{$frequency->name} ({$frequency->code})</li>";
    }
    echo "</ul>";
    
    echo "<p><a href='partner_registration'>Try Partner Registration Now</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
