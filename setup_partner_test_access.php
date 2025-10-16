<?php
/**
 * Setup Partner Test Access
 * This script links partners to student accounts so you can test the partner portal
 */

// Database connection
$conn = new mysqli('localhost', 'root', '', 'ssdb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "===========================================\n";
echo "PARTNER PORTAL - TEST ACCESS SETUP\n";
echo "===========================================\n\n";

// Step 1: Find or create test student
echo "Step 1: Finding/Creating Test Student...\n";

$test_student = null;

// Check for existing test student
$result = $conn->query("
    SELECT id, firstname, lastname, email, mobileno 
    FROM students 
    WHERE email LIKE '%test%' 
    LIMIT 1
");

if ($result && $result->num_rows > 0) {
    $test_student = $result->fetch_assoc();
    echo "  ✓ Found existing test student:\n";
    echo "    ID: {$test_student['id']}\n";
    echo "    Name: {$test_student['firstname']} {$test_student['lastname']}\n";
    echo "    Email: {$test_student['email']}\n\n";
} else {
    // Try to find ANY student
    $result = $conn->query("
        SELECT id, firstname, lastname, email, mobileno 
        FROM students 
        WHERE is_active = 'yes'
        LIMIT 1
    ");
    
    if ($result && $result->num_rows > 0) {
        $test_student = $result->fetch_assoc();
        echo "  ✓ Found active student:\n";
        echo "    ID: {$test_student['id']}\n";
        echo "    Name: {$test_student['firstname']} {$test_student['lastname']}\n";
        echo "    Email: {$test_student['email']}\n\n";
    } else {
        echo "  ⚠ No students found in database!\n";
        echo "    Please create a student through the admin panel first.\n\n";
        echo "===========================================\n";
        echo "ALTERNATIVE: Access through Admin Panel\n";
        echo "===========================================\n";
        echo "1. Login to admin panel\n";
        echo "2. Create a student\n";
        echo "3. Run this script again\n\n";
        exit;
    }
}

// Step 2: Find partners
echo "Step 2: Finding Partners...\n";

$partners = $conn->query("
    SELECT id, partner_code, firstname, lastname, email, mobileno, student_id, staff_id 
    FROM partners 
    WHERE partner_code LIKE 'PTR-TEST-%'
    ORDER BY id
");

if (!$partners || $partners->num_rows == 0) {
    echo "  ⚠ No test partners found!\n";
    echo "    Run: php insert_test_data.php\n\n";
    exit;
}

$partner_list = [];
while ($row = $partners->fetch_assoc()) {
    $partner_list[] = $row;
}

echo "  ✓ Found " . count($partner_list) . " test partner(s)\n\n";

// Step 3: Link partners to student
echo "Step 3: Linking Partners to Student...\n";

$linked_count = 0;

foreach ($partner_list as $partner) {
    // Link partner to test student
    $stmt = $conn->prepare("
        UPDATE partners 
        SET student_id = ?,
            email = ?,
            mobileno = ?
        WHERE id = ?
    ");
    
    $stmt->bind_param(
        "issi",
        $test_student['id'],
        $test_student['email'],
        $test_student['mobileno'],
        $partner['id']
    );
    
    if ($stmt->execute()) {
        echo "  ✓ Linked: {$partner['partner_code']} → Student #{$test_student['id']}\n";
        $linked_count++;
    } else {
        echo "  ✗ Error linking partner: " . $conn->error . "\n";
    }
}

echo "\n";

// Step 4: Display access information
echo "===========================================\n";
echo "✅ SETUP COMPLETE!\n";
echo "===========================================\n\n";

echo "📋 LOGIN CREDENTIALS:\n";
echo "-------------------------------------------\n";
echo "Portal: Student Portal\n";
echo "URL: http://localhost/rhemazimbabwe/site/userlogin\n";
echo "\n";
echo "Email: {$test_student['email']}\n";
echo "Password: [Check with admin or try: test123, password, or student123]\n";
echo "\n";

// Try to show more login info
echo "💡 Login Information:\n";
echo "-------------------------------------------\n";

$login_info = $conn->query("
    SELECT 
        s.id,
        s.firstname,
        s.lastname,
        s.email,
        s.mobileno,
        CONCAT(c.class, ' - ', sec.section) as class_section
    FROM students s
    LEFT JOIN classes c ON c.id = s.class_id
    LEFT JOIN sections sec ON sec.id = s.section_id
    WHERE s.id = {$test_student['id']}
");

if ($login_info && $row = $login_info->fetch_assoc()) {
    echo "Student ID: {$row['id']}\n";
    echo "Name: {$row['firstname']} {$row['lastname']}\n";
    echo "Email: {$row['email']}\n";
    echo "Phone: {$row['mobileno']}\n";
    if ($row['class_section']) echo "Class: {$row['class_section']}\n";
}

echo "\n";

echo "🔗 LINKED PARTNERS ({$linked_count}):\n";
echo "-------------------------------------------\n";

foreach ($partner_list as $partner) {
    $settings_url = "http://localhost/rhemazimbabwe/user/partner/settings?partner_id={$partner['id']}";
    echo "\n";
    echo "Partner: {$partner['firstname']} {$partner['lastname']}\n";
    echo "Code: {$partner['partner_code']}\n";
    echo "Settings URL: {$settings_url}\n";
}

echo "\n";
echo "===========================================\n";
echo "📝 HOW TO TEST:\n";
echo "===========================================\n";
echo "1. Login to Student Portal:\n";
echo "   http://localhost/rhemazimbabwe/site/userlogin\n";
echo "\n";
echo "2. Use credentials above to login\n";
echo "\n";
echo "3. Look for 'Partners' in the top menu\n";
echo "   (If not visible, check sidebar menu)\n";
echo "\n";
echo "4. Click 'Partners' → See your linked partners\n";
echo "\n";
echo "5. Click 'Settings' to configure giving settings\n";
echo "\n";
echo "6. Or directly access:\n";
echo "   http://localhost/rhemazimbabwe/user/partner\n";
echo "\n";

echo "===========================================\n";
echo "🔍 VERIFICATION:\n";
echo "===========================================\n";

// Verify linkage
$verify = $conn->query("
    SELECT 
        p.id,
        p.partner_code,
        p.firstname as partner_firstname,
        p.lastname as partner_lastname,
        p.student_id,
        s.firstname as student_firstname,
        s.lastname as student_lastname,
        s.email as student_email
    FROM partners p
    LEFT JOIN students s ON s.id = p.student_id
    WHERE p.student_id = {$test_student['id']}
");

echo "\nPartners linked to Student #{$test_student['id']}:\n";
while ($row = $verify->fetch_assoc()) {
    echo "  ✓ {$row['partner_code']} - {$row['partner_firstname']} {$row['partner_lastname']}\n";
}

echo "\n";
echo "===========================================\n";
echo "❗ IMPORTANT NOTES:\n";
echo "===========================================\n";
echo "• Partners DON'T have separate login!\n";
echo "• Access through: Student/Parent/Staff Portal\n";
echo "• Partner must be LINKED to student/staff\n";
echo "• Match by: student_id, email, or phone\n";
echo "\n";

echo "===========================================\n";
echo "🚨 TROUBLESHOOTING:\n";
echo "===========================================\n";
echo "\nIf you can't see Partners menu:\n";
echo "1. Make sure you're logged in as STUDENT (not admin)\n";
echo "2. Check partner is linked (run this script again)\n";
echo "3. Clear browser cache and cookies\n";
echo "4. Try logging out and back in\n";
echo "\n";

echo "If page asks for login when already logged in:\n";
echo "1. You might be logged in as admin (different portal)\n";
echo "2. Session might have expired - login again\n";
echo "3. Use the student login URL above\n";
echo "\n";

echo "===========================================\n";
echo "📖 For more help, see:\n";
echo "   HOW_TO_ACCESS_PARTNER_PORTAL.md\n";
echo "===========================================\n";

$conn->close();
?>
