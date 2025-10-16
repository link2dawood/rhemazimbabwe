<?php
/**
 * Fix Student Class Assignment
 * Assigns a class to the test student so they can access the portal
 */

$conn = new mysqli('localhost', 'root', '', 'ssdb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "===========================================\n";
echo "FIXING STUDENT CLASS ASSIGNMENT\n";
echo "===========================================\n\n";

// Get the student
$student = $conn->query("SELECT * FROM students WHERE email = 'kuda@virtual.co.zw' LIMIT 1")->fetch_assoc();

if (!$student) {
    echo "❌ Student not found!\n";
    exit;
}

echo "Found Student:\n";
echo "  ID: {$student['id']}\n";
echo "  Name: {$student['firstname']} {$student['lastname']}\n";
echo "  Email: {$student['email']}\n\n";

// Check current class assignment
echo "Current Assignment:\n";
echo "  Class ID: " . ($student['class_id'] ?: 'None') . "\n";
echo "  Section ID: " . ($student['section_id'] ?: 'None') . "\n";
echo "  Session ID: " . ($student['session_id'] ?: 'None') . "\n\n";

// Get available classes
$classes = $conn->query("SELECT * FROM classes WHERE is_active = 'yes' LIMIT 1");

if ($classes && $class = $classes->fetch_assoc()) {
    echo "✓ Found active class:\n";
    echo "  Class ID: {$class['id']}\n";
    echo "  Class Name: {$class['class']}\n\n";
    
    // Get sections for this class
    $sections = $conn->query("SELECT * FROM sections WHERE class_id = {$class['id']} LIMIT 1");
    
    if ($sections && $section = $sections->fetch_assoc()) {
        echo "✓ Found section:\n";
        echo "  Section ID: {$section['id']}\n";
        echo "  Section Name: {$section['section']}\n\n";
        
        // Get current session
        $sessions = $conn->query("SELECT * FROM sessions WHERE is_active = 'yes' LIMIT 1");
        
        if ($sessions && $session = $sessions->fetch_assoc()) {
            echo "✓ Found active session:\n";
            echo "  Session ID: {$session['id']}\n";
            echo "  Session: {$session['session']}\n\n";
            
            // Update student with class, section, and session
            $update = $conn->query("
                UPDATE students 
                SET class_id = {$class['id']},
                    section_id = {$section['id']},
                    session_id = {$session['id']}
                WHERE id = {$student['id']}
            ");
            
            if ($update) {
                echo "✅ STUDENT UPDATED SUCCESSFULLY!\n\n";
                
                echo "===========================================\n";
                echo "UPDATED STUDENT INFORMATION\n";
                echo "===========================================\n";
                echo "Student: {$student['firstname']} {$student['lastname']}\n";
                echo "Email: {$student['email']}\n";
                echo "Class: {$class['class']}\n";
                echo "Section: {$section['section']}\n";
                echo "Session: {$session['session']}\n\n";
                
                echo "===========================================\n";
                echo "✅ FIX COMPLETE - TRY LOGGING IN NOW!\n";
                echo "===========================================\n\n";
                
                echo "Login URL: http://localhost/rhemazimbabwe/site/userlogin\n";
                echo "Email: {$student['email']}\n";
                echo "Password: [Try: test123, password, or ask admin]\n\n";
                
                echo "After login, you should see the dashboard instead of the class selection screen.\n";
            } else {
                echo "❌ Failed to update student: " . $conn->error . "\n";
            }
        } else {
            echo "⚠️ No active session found. Creating one...\n";
            
            // Create a test session
            $current_year = date('Y');
            $next_year = $current_year + 1;
            $session_name = $current_year . '-' . $next_year;
            
            $conn->query("
                INSERT INTO sessions (session, is_active, created_at) 
                VALUES ('$session_name', 'yes', NOW())
            ");
            
            $session_id = $conn->insert_id;
            
            // Update student
            $conn->query("
                UPDATE students 
                SET class_id = {$class['id']},
                    section_id = {$section['id']},
                    session_id = $session_id
                WHERE id = {$student['id']}
            ");
            
            echo "✅ Created session and updated student!\n";
            echo "Session: $session_name\n";
        }
    } else {
        echo "⚠️ No sections found for this class. Creating one...\n";
        
        // Create a section
        $conn->query("
            INSERT INTO sections (section, class_id, is_active, created_at) 
            VALUES ('A', {$class['id']}, 'yes', NOW())
        ");
        
        $section_id = $conn->insert_id;
        
        // Get or create session
        $sessions = $conn->query("SELECT * FROM sessions WHERE is_active = 'yes' LIMIT 1");
        if ($sessions && $session = $sessions->fetch_assoc()) {
            $session_id = $session['id'];
        } else {
            $current_year = date('Y');
            $next_year = $current_year + 1;
            $session_name = $current_year . '-' . $next_year;
            
            $conn->query("
                INSERT INTO sessions (session, is_active, created_at) 
                VALUES ('$session_name', 'yes', NOW())
            ");
            
            $session_id = $conn->insert_id;
        }
        
        // Update student
        $conn->query("
            UPDATE students 
            SET class_id = {$class['id']},
                section_id = $section_id,
                session_id = $session_id
            WHERE id = {$student['id']}
        ");
        
        echo "✅ Created section and updated student!\n";
    }
} else {
    echo "⚠️ No classes found in the database!\n";
    echo "Creating a test class...\n\n";
    
    // Create test class
    $conn->query("
        INSERT INTO classes (class, is_active, created_at) 
        VALUES ('Test Class', 'yes', NOW())
    ");
    $class_id = $conn->insert_id;
    
    // Create section
    $conn->query("
        INSERT INTO sections (section, class_id, is_active, created_at) 
        VALUES ('A', $class_id, 'yes', NOW())
    ");
    $section_id = $conn->insert_id;
    
    // Get or create session
    $sessions = $conn->query("SELECT * FROM sessions WHERE is_active = 'yes' LIMIT 1");
    if ($sessions && $session = $sessions->fetch_assoc()) {
        $session_id = $session['id'];
    } else {
        $current_year = date('Y');
        $next_year = $current_year + 1;
        $session_name = $current_year . '-' . $next_year;
        
        $conn->query("
            INSERT INTO sessions (session, is_active, created_at) 
            VALUES ('$session_name', 'yes', NOW())
        ");
        $session_id = $conn->insert_id;
    }
    
    // Update student
    $conn->query("
        UPDATE students 
        SET class_id = $class_id,
            section_id = $section_id,
            session_id = $session_id
        WHERE id = {$student['id']}
    ");
    
    echo "✅ Created class, section, and updated student!\n";
}

echo "\n===========================================\n";
echo "VERIFICATION\n";
echo "===========================================\n";

// Verify the update
$updated_student = $conn->query("
    SELECT 
        s.*,
        c.class,
        sec.section,
        sess.session
    FROM students s
    LEFT JOIN classes c ON c.id = s.class_id
    LEFT JOIN sections sec ON sec.id = s.section_id
    LEFT JOIN sessions sess ON sess.id = s.session_id
    WHERE s.id = {$student['id']}
")->fetch_assoc();

echo "\nStudent Status:\n";
echo "  ✓ Name: {$updated_student['firstname']} {$updated_student['lastname']}\n";
echo "  ✓ Email: {$updated_student['email']}\n";
echo "  ✓ Class: " . ($updated_student['class'] ?: 'Not assigned') . "\n";
echo "  ✓ Section: " . ($updated_student['section'] ?: 'Not assigned') . "\n";
echo "  ✓ Session: " . ($updated_student['session'] ?: 'Not assigned') . "\n";

echo "\n===========================================\n";
echo "🎉 ALL DONE!\n";
echo "===========================================\n";
echo "You can now login and access the partner portal!\n\n";

$conn->close();
?>


