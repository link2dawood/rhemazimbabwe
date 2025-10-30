<?php
// Quick Status Check
$conn = new mysqli('localhost', 'root', '', 'ssdb');

if ($conn->connect_error) {
    die("Connection failed");
}

$status = [];

// Check table structure
$columns = [];
$result = $conn->query("DESCRIBE partner_contributions");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
}

$required = ['id', 'partner_id', 'amount', 'currency', 'contribution_date', 'payment_method', 'receipt_no', 'status', 'recorded_by', 'attachment', 'created_at', 'updated_at'];
$missing = array_diff($required, $columns);

$status['table_exists'] = $result ? 'YES' : 'NO';
$status['all_columns_exist'] = empty($missing) ? 'YES' : 'NO';
$status['missing_columns'] = implode(', ', $missing);

// Check data
$result = $conn->query("SELECT COUNT(*) as count FROM partners WHERE is_active = 1");
$row = $result->fetch_assoc();
$status['active_partners'] = $row['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM partner_contributions");
$row = $result->fetch_assoc();
$status['total_contributions'] = $row['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM giving_types WHERE is_active = 1");
$row = $result->fetch_assoc();
$status['active_giving_types'] = $row['count'];

// Check files
$status['controller_exists'] = file_exists('application/controllers/admin/Partnercontributions.php') ? 'YES' : 'NO';
$status['model_exists'] = file_exists('application/models/Contribution_model.php') ? 'YES' : 'NO';
$status['view_exists'] = file_exists('application/views/admin/contributions/contributionadd.php') ? 'YES' : 'NO';
$status['upload_dir_exists'] = is_dir('uploads/partner_contributions') ? 'YES' : 'NO';
$status['upload_dir_writable'] = is_writable('uploads/partner_contributions') ? 'YES' : 'NO';

// Recent contributions
$result = $conn->query("SELECT pc.*, p.firstname, p.lastname FROM partner_contributions pc LEFT JOIN partners p ON p.id = pc.partner_id ORDER BY pc.created_at DESC LIMIT 3");
$recent = [];
while ($row = $result->fetch_assoc()) {
    $recent[] = $row;
}
$status['recent_contributions'] = $recent;

echo json_encode($status, JSON_PRETTY_PRINT);

$conn->close();
