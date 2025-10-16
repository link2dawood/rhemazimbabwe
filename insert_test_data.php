<?php
/**
 * Test Data Generator for Partner Giving Settings
 * This script creates sample partners and giving settings for testing
 */

// Database connection
$conn = new mysqli('localhost', 'root', '', 'ssdb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "===========================================\n";
echo "PARTNER GIVING SETTINGS - TEST DATA SETUP\n";
echo "===========================================\n\n";

// Check if giving types exist
$types_check = $conn->query("SELECT COUNT(*) as count FROM giving_types");
$types_count = $types_check->fetch_assoc()['count'];

if ($types_count == 0) {
    echo "⚠ No giving types found. Creating default types...\n";
    
    $types = [
        ['Tuition Support', 'tuition', 'Support for student tuition fees'],
        ['Scholarship Fund', 'scholarship', 'Contributions to scholarship fund'],
        ['Building Fund', 'building', 'Support for infrastructure development'],
        ['General Donation', 'general', 'General purpose donations'],
        ['Sponsorship', 'sponsorship', 'Student sponsorship program']
    ];
    
    foreach ($types as $type) {
        $stmt = $conn->prepare("INSERT INTO giving_types (name, code, description, is_active) VALUES (?, ?, ?, 1)");
        $stmt->bind_param("sss", $type[0], $type[1], $type[2]);
        $stmt->execute();
        echo "  ✓ Created: {$type[0]}\n";
    }
    echo "\n";
}

// Check if giving frequencies exist
$freq_check = $conn->query("SELECT COUNT(*) as count FROM giving_frequencies");
$freq_count = $freq_check->fetch_assoc()['count'];

if ($freq_count == 0) {
    echo "⚠ No giving frequencies found. Creating default frequencies...\n";
    
    $frequencies = [
        ['Once-Off', 'once_off', NULL, 'One time contribution'],
        ['Weekly', 'weekly', 7, 'Weekly contributions'],
        ['Monthly', 'monthly', 30, 'Monthly contributions'],
        ['Quarterly', 'quarterly', 90, 'Quarterly contributions'],
        ['Annually', 'annually', 365, 'Annual contributions']
    ];
    
    foreach ($frequencies as $freq) {
        $stmt = $conn->prepare("INSERT INTO giving_frequencies (name, code, days_interval, description, is_active) VALUES (?, ?, ?, ?, 1)");
        $stmt->bind_param("ssis", $freq[0], $freq[1], $freq[2], $freq[3]);
        $stmt->execute();
        echo "  ✓ Created: {$freq[0]}\n";
    }
    echo "\n";
}

// Create test partners
echo "Creating test partners...\n";

$test_partners = [
    [
        'code' => 'PTR-TEST-0001',
        'type' => 'individual',
        'org_name' => NULL,
        'org_type' => NULL,
        'firstname' => 'John',
        'lastname' => 'Doe',
        'email' => 'john.doe@testpartner.com',
        'phone' => '+263771234567',
        'address' => '123 Main Street',
        'city' => 'Harare',
        'currency' => 'USD',
        'status' => 'active'
    ],
    [
        'code' => 'PTR-TEST-0002',
        'type' => 'individual',
        'org_name' => NULL,
        'org_type' => NULL,
        'firstname' => 'Mary',
        'lastname' => 'Smith',
        'email' => 'mary.smith@testpartner.com',
        'phone' => '+263777654321',
        'address' => '456 Church Road',
        'city' => 'Bulawayo',
        'currency' => 'USD',
        'status' => 'active'
    ],
    [
        'code' => 'PTR-TEST-0003',
        'type' => 'organization',
        'org_name' => 'Grace Foundation',
        'org_type' => 'Foundation',
        'firstname' => 'David',
        'lastname' => 'Wilson',
        'email' => 'contact@gracefoundation.org',
        'phone' => '+263772345678',
        'address' => '789 Charity Lane',
        'city' => 'Harare',
        'currency' => 'USD',
        'status' => 'active'
    ]
];

$partner_ids = [];

foreach ($test_partners as $partner) {
    // Check if partner exists
    $check = $conn->prepare("SELECT id FROM partners WHERE email = ?");
    $check->bind_param("s", $partner['email']);
    $check->execute();
    $result = $check->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $partner_ids[] = $row['id'];
        echo "  • Partner already exists: {$partner['firstname']} {$partner['lastname']} (ID: {$row['id']})\n";
    } else {
        $stmt = $conn->prepare("
            INSERT INTO partners (
                partner_code, account_type, organization_name, organization_type,
                firstname, lastname, email, mobileno, address, city, country,
                currency, status, is_active, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Zimbabwe', ?, ?, 1, NOW())
        ");
        
        $stmt->bind_param(
            "ssssssssssss",
            $partner['code'],
            $partner['type'],
            $partner['org_name'],
            $partner['org_type'],
            $partner['firstname'],
            $partner['lastname'],
            $partner['email'],
            $partner['phone'],
            $partner['address'],
            $partner['city'],
            $partner['currency'],
            $partner['status']
        );
        
        if ($stmt->execute()) {
            $partner_id = $conn->insert_id;
            $partner_ids[] = $partner_id;
            echo "  ✓ Created: {$partner['firstname']} {$partner['lastname']} (ID: {$partner_id})\n";
        } else {
            echo "  ✗ Error creating partner: " . $conn->error . "\n";
        }
    }
}

echo "\n";

// Create sample giving settings
echo "Creating sample giving settings...\n";

// Get giving type IDs
$types_result = $conn->query("SELECT id, name FROM giving_types ORDER BY id LIMIT 5");
$giving_types = [];
while ($row = $types_result->fetch_assoc()) {
    $giving_types[$row['name']] = $row['id'];
}

// Sample settings for each partner
$sample_settings = [
    // Partner 1: Multiple types
    [
        'partner_idx' => 0,
        'settings' => [
            ['type' => 'Tuition Support', 'amount' => 100.00],
            ['type' => 'Building Fund', 'amount' => 50.00]
        ]
    ],
    // Partner 2: Single type
    [
        'partner_idx' => 1,
        'settings' => [
            ['type' => 'Scholarship Fund', 'amount' => 200.00]
        ]
    ],
    // Partner 3: All types
    [
        'partner_idx' => 2,
        'settings' => [
            ['type' => 'Tuition Support', 'amount' => 150.00],
            ['type' => 'Scholarship Fund', 'amount' => 100.00],
            ['type' => 'Building Fund', 'amount' => 75.00],
            ['type' => 'General Donation', 'amount' => 50.00]
        ]
    ]
];

foreach ($sample_settings as $partner_setting) {
    if (!isset($partner_ids[$partner_setting['partner_idx']])) {
        continue;
    }
    
    $partner_id = $partner_ids[$partner_setting['partner_idx']];
    
    foreach ($partner_setting['settings'] as $setting) {
        if (!isset($giving_types[$setting['type']])) {
            continue;
        }
        
        $type_id = $giving_types[$setting['type']];
        
        // Check if setting exists
        $check = $conn->prepare("SELECT id FROM partner_giving_settings WHERE partner_id = ? AND giving_type_id = ?");
        $check->bind_param("ii", $partner_id, $type_id);
        $check->execute();
        
        if ($check->get_result()->num_rows == 0) {
            $stmt = $conn->prepare("
                INSERT INTO partner_giving_settings (
                    partner_id, giving_type_id, amount, currency, is_active, created_at
                ) VALUES (?, ?, ?, 'USD', 1, NOW())
            ");
            
            $stmt->bind_param("iid", $partner_id, $type_id, $setting['amount']);
            
            if ($stmt->execute()) {
                echo "  ✓ Partner {$partner_id}: {$setting['type']} - \${$setting['amount']}\n";
            } else {
                echo "  ✗ Error: " . $conn->error . "\n";
            }
        } else {
            echo "  • Partner {$partner_id}: {$setting['type']} - Already exists\n";
        }
    }
}

echo "\n";

// Update partner contribution amounts
echo "Updating partner totals...\n";

foreach ($partner_ids as $partner_id) {
    $total_query = $conn->query("
        SELECT SUM(amount) as total 
        FROM partner_giving_settings 
        WHERE partner_id = {$partner_id} AND is_active = 1
    ");
    
    $total_row = $total_query->fetch_assoc();
    $total = $total_row['total'] ?? 0;
    
    $conn->query("
        UPDATE partners 
        SET contribution_amount = {$total}, 
            giving_frequency_id = 3 
        WHERE id = {$partner_id}
    ");
    
    echo "  ✓ Partner {$partner_id}: Total = \${$total}\n";
}

echo "\n";

// Display summary
echo "===========================================\n";
echo "TEST DATA SUMMARY\n";
echo "===========================================\n";

$partners_query = $conn->query("
    SELECT 
        p.id,
        p.partner_code,
        CONCAT(p.firstname, ' ', p.lastname) as name,
        p.email,
        p.contribution_amount,
        p.currency,
        COUNT(pgs.id) as types_count
    FROM partners p
    LEFT JOIN partner_giving_settings pgs ON pgs.partner_id = p.id AND pgs.is_active = 1
    WHERE p.partner_code LIKE 'PTR-TEST-%'
    GROUP BY p.id
");

while ($row = $partners_query->fetch_assoc()) {
    echo "\n📋 Partner: {$row['name']}\n";
    echo "   Code: {$row['partner_code']}\n";
    echo "   Email: {$row['email']}\n";
    echo "   Total Amount: {$row['currency']} {$row['contribution_amount']}\n";
    echo "   Giving Types: {$row['types_count']}\n";
    
    // Show giving settings
    $settings_query = $conn->query("
        SELECT gt.name, pgs.amount 
        FROM partner_giving_settings pgs
        JOIN giving_types gt ON gt.id = pgs.giving_type_id
        WHERE pgs.partner_id = {$row['id']} AND pgs.is_active = 1
    ");
    
    echo "   Settings:\n";
    while ($setting = $settings_query->fetch_assoc()) {
        echo "     - {$setting['name']}: \${$setting['amount']}\n";
    }
}

echo "\n===========================================\n";
echo "✅ TEST DATA CREATED SUCCESSFULLY!\n";
echo "===========================================\n\n";

echo "📝 LOGIN CREDENTIALS (If linked to students/staff):\n";
echo "   Email: Any email from above\n";
echo "   Or link to existing student/staff records\n\n";

echo "🔗 TEST URLs:\n";
echo "   Partner Settings: http://localhost/rhemazimbabwe/user/partner/settings?partner_id=1\n";
echo "   Admin Contributions: http://localhost/rhemazimbabwe/admin/partnercontributions\n\n";

echo "💡 Next Steps:\n";
echo "   1. Login to Partner Portal\n";
echo "   2. Navigate to Partners → Settings\n";
echo "   3. Test the giving settings functionality\n";
echo "   4. Verify in Admin Panel → Partner Contributions\n\n";

$conn->close();
?>



