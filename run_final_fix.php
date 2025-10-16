<?php
/**
 * Final Fix - Execute directly
 */

$conn = new mysqli('localhost', 'root', '', 'ssdb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "===========================================\n";
echo "FIXING STUDENT SESSION - FINAL FIX\n";
echo "===========================================\n\n";

// Step 1: Get or create active session
echo "Step 1: Checking session...\n";
$session = $conn->query("SELECT id, session FROM sessions WHERE is_active = 'yes' LIMIT 1");

if ($session && $session->num_rows > 0) {
    $sess = $session->fetch_assoc();
    $session_id = $sess['id'];
    echo "  ✓ Found session: {$sess['session']} (ID: {$session_id})\n";
} else {
    echo "  Creating new session...\n";
    $year = date('Y');
    $next = $year + 1;
    $conn->query("INSERT INTO sessions (session, is_active) VALUES ('{$year}-{$next}', 'yes')");
    $session_id = $conn->insert_id;
    echo "  ✓ Created session: {$year}-{$next} (ID: {$session_id})\n";
}

// Step 2: Get class
echo "\nStep 2: Checking class...\n";
$class = $conn->query("SELECT id, class FROM classes LIMIT 1");

if ($class && $class->num_rows > 0) {
    $cls = $class->fetch_assoc();
    $class_id = $cls['id'];
    echo "  ✓ Found class: {$cls['class']} (ID: {$class_id})\n";
} else {
    echo "  ERROR: No classes found in database!\n";
    echo "  Please create a class through admin panel first.\n";
    exit;
}

// Step 3: Get or create section
echo "\nStep 3: Checking section...\n";
$section = $conn->query("SELECT id, section FROM sections LIMIT 1");

if ($section && $section->num_rows > 0) {
    $sec = $section->fetch_assoc();
    $section_id = $sec['id'];
    echo "  ✓ Found section: {$sec['section']} (ID: {$section_id})\n";
} else {
    echo "  Creating section...\n";
    $conn->query("INSERT INTO sections (section, is_active) VALUES ('A', 'yes')");
    $section_id = $conn->insert_id;
    echo "  ✓ Created section: A (ID: {$section_id})\n";
}

// Step 4: Delete existing student_session
echo "\nStep 4: Clearing old student sessions...\n";
$delete = $conn->query("DELETE FROM student_session WHERE student_id = 1");
echo "  ✓ Cleared existing sessions\n";

// Step 5: Insert new student_session
echo "\nStep 5: Creating student session...\n";
$insert = $conn->query("
    INSERT INTO student_session (session_id, student_id, class_id, section_id) 
    VALUES ($session_id, 1, $class_id, $section_id)
");

if ($insert) {
    echo "  ✓ Student session created successfully!\n";
} else {
    echo "  ✗ Error: " . $conn->error . "\n";
    exit;
}

// Step 6: Verify
echo "\n===========================================\n";
echo "VERIFICATION\n";
echo "===========================================\n";

$verify = $conn->query("
    SELECT 
        ss.*,
        s.firstname,
        s.lastname,
        s.email,
        c.class,
        sec.section,
        sess.session
    FROM student_session ss
    JOIN students s ON s.id = ss.student_id
    LEFT JOIN classes c ON c.id = ss.class_id
    LEFT JOIN sections sec ON sec.id = ss.section_id
    LEFT JOIN sessions sess ON sess.id = ss.session_id
    WHERE ss.student_id = 1
");

if ($verify && $row = $verify->fetch_assoc()) {
    echo "\n✅ Student Session Details:\n";
    echo "  Student: {$row['firstname']} {$row['lastname']}\n";
    echo "  Email: {$row['email']}\n";
    echo "  Session: {$row['session']}\n";
    echo "  Class: {$row['class']}\n";
    echo "  Section: {$row['section']}\n";
    echo "\n";
} else {
    echo "\n❌ Could not verify student session!\n";
}

echo "===========================================\n";
echo "✅ FIX COMPLETE!\n";
echo "===========================================\n\n";

echo "📋 NOW DO THIS:\n";
echo "-------------------------------------------\n";
echo "1. LOGOUT if you're currently logged in\n";
echo "2. Clear browser cache/cookies (Ctrl+Shift+Delete)\n";
echo "3. Close all browser tabs\n";
echo "4. Open fresh browser window\n";
echo "5. Login again:\n";
echo "   URL: http://localhost/rhemazimbabwe/site/userlogin\n";
echo "   Email: kuda@virtual.co.zw\n";
echo "   Password: [ask admin or try: test123]\n\n";

echo "6. After login, you should see the DASHBOARD\n";
echo "7. Look for 'Partners' in the menu\n";
echo "8. Or go directly to:\n";
echo "   http://localhost/rhemazimbabwe/user/partner/settings?partner_id=10\n\n";

echo "===========================================\n";
echo "⚠️ IMPORTANT:\n";
echo "===========================================\n";
echo "• Must LOGOUT and LOGIN again for changes to take effect\n";
echo "• Clear browser cache if still having issues\n";
echo "• Session data is cached, so fresh login is required\n\n";

$conn->close();
?>

