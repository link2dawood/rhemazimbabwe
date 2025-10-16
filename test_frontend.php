<?php
// Test frontend setup
// Run: http://localhost/rhemazimbabwe/test_frontend.php

echo "<h2>Frontend Setup Test</h2>";

// Check if files exist
$files_to_check = [
    'application/views/frontend/header.php',
    'application/views/frontend/footer.php',
    'application/views/frontend/home.php',
    'application/views/frontend/partner_registration.php',
    'application/views/frontend/partner_registration_success.php',
    'application/controllers/Frontend.php',
    'application/controllers/Partner_registration.php'
];

echo "<h3>File Check:</h3>";
foreach ($files_to_check as $file) {
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
echo "</ul>";

echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li>Run the database test: <a href='test_database.php'>test_database.php</a></li>";
echo "<li>Run the model test: <a href='test_models.php'>test_models.php</a></li>";
echo "<li>Try the partner registration: <a href='partner_registration'>partner_registration</a></li>";
echo "</ol>";
?>
