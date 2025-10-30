<?php
/**
 * Direct AJAX Endpoint Test
 * This simulates what the DataTable does
 */

// Set up CodeIgniter environment
define('BASEPATH', TRUE);
$_SERVER['REQUEST_METHOD'] = 'POST';

// Include the bootstrap
require_once('index.php');

// Simulate logged-in session
$_SESSION['admin'] = [
    'id' => 1,
    'username' => 'admin',
    'role_id' => 1
];

echo "<h2>Testing Partner Reports AJAX Endpoint</h2>";
echo "<hr>";

// Test the getPartnerInformationData endpoint
echo "<h3>Calling: admin/partnerreports/getPartnerInformationData</h3>";

// Simulate POST data
$_POST['status'] = '';
$_POST['giving_type_id'] = '';
$_POST['giving_frequency_id'] = '';
$_POST['date_from'] = '';
$_POST['date_to'] = '';

// Buffer output
ob_start();

try {
    // Load CodeIgniter
    $CI =& get_instance();

    // Load required models
    $CI->load->model('Partner_model');
    $CI->load->model('Contribution_model');

    echo "Models loaded...<br>";

    // Test Partner_model->getAll()
    $filters = [
        'status' => '',
        'giving_type_id' => '',
        'giving_frequency_id' => '',
        'date_from' => '',
        'date_to' => ''
    ];

    echo "Calling Partner_model->getAll()...<br>";
    $partners = $CI->Partner_model->getAll($filters);

    echo "Partners returned: " . count($partners) . "<br>";

    if (count($partners) > 0) {
        echo "<pre>";
        print_r($partners);
        echo "</pre>";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}

$output = ob_get_clean();
echo $output;
?>
