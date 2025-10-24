<?php
/**
 * Test Partner Registration Error Handling
 *
 * This script tests various error scenarios in partner registration to ensure
 * proper error messages are displayed to users.
 *
 * Usage: Access via browser at /test_partner_registration_errors.php
 */

// Load CodeIgniter
define('BASEPATH', TRUE);
require_once __DIR__ . '/index.php';

// Get CI instance
$CI =& get_instance();
$CI->load->model('Partner_model');
$CI->load->database();

echo "<!DOCTYPE html>";
echo "<html><head><title>Partner Registration Error Testing</title>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    .test { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #007bff; }
    .success { border-left-color: #28a745; }
    .error { border-left-color: #dc3545; }
    .warning { border-left-color: #ffc107; }
    h1 { color: #333; }
    h2 { color: #666; margin-top: 0; }
    pre { background: #f8f9fa; padding: 10px; border-radius: 3px; overflow-x: auto; }
    .result { margin-top: 10px; }
</style></head><body>";

echo "<h1>Partner Registration Error Handling Tests</h1>";
echo "<p>Testing various error scenarios to ensure proper error messages are returned.</p>";

// Test 1: Missing required fields
echo "<div class='test'>";
echo "<h2>Test 1: Missing Required Field (Email)</h2>";
$test_data_1 = [
    'firstname' => 'John',
    'lastname' => 'Doe',
    'mobileno' => '0712345678',
    'account_type' => 'individual',
    'status' => 'pending',
    'is_active' => 1,
    'created_at' => date('Y-m-d H:i:s')
];
$result_1 = $CI->Partner_model->add($test_data_1);
echo "<div class='result " . (is_array($result_1) && $result_1['error'] ? 'success' : 'error') . "'>";
echo "<strong>Result:</strong> ";
if (is_array($result_1) && isset($result_1['error'])) {
    echo "ERROR (Expected): " . $result_1['message'];
} else {
    echo "FAILED - Should have returned an error for missing email";
}
echo "</div>";
echo "</div>";

// Test 2: Duplicate email (if existing partner exists)
echo "<div class='test'>";
echo "<h2>Test 2: Duplicate Email Address</h2>";
$existing_partner = $CI->db->select('email')->from('partners')->limit(1)->get()->row();
if ($existing_partner) {
    $test_data_2 = [
        'firstname' => 'Jane',
        'lastname' => 'Smith',
        'email' => $existing_partner->email,
        'mobileno' => '0787654321',
        'account_type' => 'individual',
        'status' => 'pending',
        'is_active' => 1,
        'created_at' => date('Y-m-d H:i:s')
    ];
    $result_2 = $CI->Partner_model->add($test_data_2);
    echo "<div class='result " . (is_array($result_2) && $result_2['error'] ? 'success' : 'error') . "'>";
    echo "<strong>Result:</strong> ";
    if (is_array($result_2) && isset($result_2['error'])) {
        echo "ERROR (Expected): " . $result_2['message'];
    } else {
        echo "FAILED - Should have returned an error for duplicate email";
    }
    echo "</div>";
} else {
    echo "<div class='result warning'>SKIPPED - No existing partners in database to test duplicate email</div>";
}
echo "</div>";

// Test 3: Invalid giving type
echo "<div class='test'>";
echo "<h2>Test 3: Invalid Giving Type Reference</h2>";
$test_data_3 = [
    'firstname' => 'Test',
    'lastname' => 'User',
    'email' => 'test_invalid_type_' . time() . '@example.com',
    'mobileno' => '071' . rand(1000000, 9999999),
    'giving_type_id' => 99999, // Non-existent ID
    'account_type' => 'individual',
    'status' => 'pending',
    'is_active' => 1,
    'created_at' => date('Y-m-d H:i:s')
];
$result_3 = $CI->Partner_model->add($test_data_3);
echo "<div class='result " . (is_array($result_3) && $result_3['error'] ? 'success' : 'error') . "'>";
echo "<strong>Result:</strong> ";
if (is_array($result_3) && isset($result_3['error'])) {
    echo "ERROR (Expected): " . $result_3['message'];
} else {
    echo "FAILED - Should have returned an error for invalid giving type";
}
echo "</div>";
echo "</div>";

// Test 4: Invalid giving frequency
echo "<div class='test'>";
echo "<h2>Test 4: Invalid Giving Frequency Reference</h2>";
$test_data_4 = [
    'firstname' => 'Test',
    'lastname' => 'User',
    'email' => 'test_invalid_freq_' . time() . '@example.com',
    'mobileno' => '071' . rand(1000000, 9999999),
    'giving_frequency_id' => 99999, // Non-existent ID
    'account_type' => 'individual',
    'status' => 'pending',
    'is_active' => 1,
    'created_at' => date('Y-m-d H:i:s')
];
$result_4 = $CI->Partner_model->add($test_data_4);
echo "<div class='result " . (is_array($result_4) && $result_4['error'] ? 'success' : 'error') . "'>";
echo "<strong>Result:</strong> ";
if (is_array($result_4) && isset($result_4['error'])) {
    echo "ERROR (Expected): " . $result_4['message'];
} else {
    echo "FAILED - Should have returned an error for invalid giving frequency";
}
echo "</div>";
echo "</div>";

// Test 5: Duplicate username
echo "<div class='test'>";
echo "<h2>Test 5: Duplicate Username</h2>";
$existing_username = $CI->db->select('username')->from('partners')->where('username IS NOT NULL')->limit(1)->get()->row();
if ($existing_username && $existing_username->username) {
    $test_data_5 = [
        'firstname' => 'Test',
        'lastname' => 'User',
        'email' => 'test_dup_username_' . time() . '@example.com',
        'mobileno' => '071' . rand(1000000, 9999999),
        'username' => $existing_username->username,
        'account_type' => 'individual',
        'status' => 'pending',
        'is_active' => 1,
        'created_at' => date('Y-m-d H:i:s')
    ];
    $result_5 = $CI->Partner_model->add($test_data_5);
    echo "<div class='result " . (is_array($result_5) && $result_5['error'] ? 'success' : 'error') . "'>";
    echo "<strong>Result:</strong> ";
    if (is_array($result_5) && isset($result_5['error'])) {
        echo "ERROR (Expected): " . $result_5['message'];
    } else {
        echo "FAILED - Should have returned an error for duplicate username";
    }
    echo "</div>";
} else {
    echo "<div class='result warning'>SKIPPED - No existing partners with usernames in database</div>";
}
echo "</div>";

// Test 6: Successful registration (with auto-rollback)
echo "<div class='test'>";
echo "<h2>Test 6: Successful Registration</h2>";
$CI->db->trans_start();
$test_data_6 = [
    'firstname' => 'Valid',
    'lastname' => 'User',
    'email' => 'valid_test_' . time() . '@example.com',
    'mobileno' => '071' . rand(1000000, 9999999),
    'account_type' => 'individual',
    'status' => 'pending',
    'is_active' => 1,
    'created_at' => date('Y-m-d H:i:s')
];
$result_6 = $CI->Partner_model->add($test_data_6);
$CI->db->trans_rollback(); // Rollback to avoid cluttering database
echo "<div class='result " . (is_numeric($result_6) && $result_6 > 0 ? 'success' : 'error') . "'>";
echo "<strong>Result:</strong> ";
if (is_numeric($result_6) && $result_6 > 0) {
    echo "SUCCESS - Partner ID: " . $result_6 . " (rolled back, not saved)";
} else {
    echo "FAILED - Should have returned a partner ID. Got: " . print_r($result_6, true);
}
echo "</div>";
echo "</div>";

// Test 7: Check giving types and frequencies exist
echo "<div class='test'>";
echo "<h2>Test 7: Database Configuration Check</h2>";
$giving_types = $CI->db->count_all('giving_types');
$giving_frequencies = $CI->db->count_all('giving_frequencies');
echo "<div class='result " . ($giving_types > 0 && $giving_frequencies > 0 ? 'success' : 'warning') . "'>";
echo "<strong>Giving Types:</strong> " . $giving_types . " found<br>";
echo "<strong>Giving Frequencies:</strong> " . $giving_frequencies . " found<br>";
if ($giving_types == 0 || $giving_frequencies == 0) {
    echo "<br><strong>WARNING:</strong> Missing configuration data. Partner registration may fail.";
}
echo "</div>";
echo "</div>";

// Summary
echo "<div class='test'>";
echo "<h2>Test Summary</h2>";
echo "<p>All tests completed. The error handling system is working correctly if:</p>";
echo "<ul>";
echo "<li>Tests 1-5 show ERROR messages (expected errors)</li>";
echo "<li>Test 6 shows SUCCESS</li>";
echo "<li>Test 7 shows configuration data exists</li>";
echo "</ul>";
echo "</div>";

echo "</body></html>";
