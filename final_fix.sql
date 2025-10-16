-- Final fix for student session
-- Run this in phpMyAdmin

-- Get current session (or create one)
SET @session_id = (SELECT id FROM sessions WHERE is_active = 'yes' LIMIT 1);

-- If no session, create one
INSERT INTO sessions (session, is_active)
SELECT CONCAT(YEAR(NOW()), '-', YEAR(NOW())+1), 'yes'
WHERE NOT EXISTS (SELECT 1 FROM sessions WHERE is_active = 'yes');

-- Update session_id if it was just created
SET @session_id = (SELECT id FROM sessions WHERE is_active = 'yes' LIMIT 1);

-- Get or create a class
SET @class_id = (SELECT id FROM classes LIMIT 1);

-- Get or create a section
SET @section_id = (SELECT id FROM sections LIMIT 1);

-- If no section exists, create one
INSERT INTO sections (section, is_active)
SELECT 'A', 'yes'
WHERE NOT EXISTS (SELECT 1 FROM sections);

-- Update section_id
SET @section_id = (SELECT id FROM sections LIMIT 1);

-- Check if student_session exists for student 1
DELETE FROM student_session WHERE student_id = 1;

-- Insert student_session
INSERT INTO student_session (session_id, student_id, class_id, section_id)
VALUES (@session_id, 1, @class_id, @section_id);

-- Verify
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
WHERE ss.student_id = 1;


