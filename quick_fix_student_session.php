<?php
/**
 * Quick Fix - Add Student Session
 */

$conn = new mysqli('localhost', 'root', '', 'ssdb');

echo "===========================================\n";
echo "QUICK FIX - STUDENT SESSION\n";
echo "===========================================\n\n";

// Get student
$student_id = 1;
$student = $conn->query("SELECT * FROM students WHERE id = $student_id")->fetch_assoc();

echo "Student: {$student['firstname']} {$student['lastname']}\n";
echo "Email: {$student['email']}\n\n";

// Get active session
$session = $conn->query("SELECT * FROM sessions WHERE is_active = 'yes' LIMIT 1")->fetch_assoc();

if (!$session) {
    echo "Creating session...\n";
    $year = date('Y');
    $next_year = $year + 1;
    $conn->query("INSERT INTO sessions (session, is_active) VALUES ('$year-$next_year', 'yes')");
    $session_id = $conn->insert_id;
} else {
    $session_id = $session['id'];
    echo "Using session: {$session['session']}\n";
}

// Get or create class
$class = $conn->query("SELECT * FROM classes LIMIT 1")->fetch_assoc();

if (!$class) {
    echo "Creating class...\n";
    $conn->query("INSERT INTO classes (class, is_active) VALUES ('General', 'yes')");
    $class_id = $conn->insert_id;
} else {
    $class_id = $class['id'];
    echo "Using class: {$class['class']}\n";
}

// Get or create section
$section = $conn->query("SELECT * FROM sections WHERE id = 1 LIMIT 1")->fetch_assoc();
$section_id = $section ? $section['id'] : 1;

// Check if student_session exists
$existing = $conn->query("SELECT * FROM student_session WHERE student_id = $student_id AND session_id = $session_id");

if ($existing && $existing->num_rows > 0) {
    echo "\n✓ Student session already exists\n";
} else {
    // Insert student_session
    $insert = $conn->query("
        INSERT INTO student_session (
            session_id, student_id, class_id, section_id
        ) VALUES (
            $session_id, $student_id, $class_id, $section_id
        )
    ");
    
    if ($insert) {
        echo "\n✅ Student session created!\n";
    } else {
        echo "\n❌ Error: " . $conn->error . "\n";
    }
}

echo "\n===========================================\n";
echo "✅ FIXED! TRY LOGGING IN NOW\n";
echo "===========================================\n";
echo "URL: http://localhost/rhemazimbabwe/site/userlogin\n";
echo "Email: {$student['email']}\n";
echo "Password: [Try: test123 or ask admin]\n\n";

$conn->close();
?>


