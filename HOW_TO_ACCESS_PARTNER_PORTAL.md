# 🔐 How to Access Partner Portal

## Important: Partner Portal Access Method

**Partners DO NOT have a separate login!** 

The partner portal is accessed through:
- 👨‍🎓 **Student Portal**
- 👪 **Parent Portal**  
- 👨‍💼 **Staff Portal**

When a student, parent, or staff member is **linked to a partner record**, they can access partner features.

---

## 🔗 How It Works

### Method 1: Linked Student
```
Student Login → Student Portal → Partners Menu → Settings
```

### Method 2: Linked Parent
```
Parent Login → Parent Portal → Partners Menu → Settings
```

### Method 3: Linked Staff
```
Staff Login → Staff Portal → Partners Menu → Settings
```

---

## 🚀 Quick Setup for Testing

### Option A: Use Existing Student Login

1. **Find a student login** in the database:
```sql
SELECT id, firstname, lastname, email, mobileno 
FROM students 
WHERE is_active = 'yes' 
LIMIT 5;
```

2. **Link partner to that student**:
```sql
-- Update partner to link with student
UPDATE partners 
SET student_id = 1  -- Replace with actual student ID
WHERE id = 1;       -- Your partner ID
```

3. **Login as that student** and access partner settings

### Option B: Create Test Student with Partner Link

Run this SQL:

```sql
-- Create a test student (if you don't have one)
INSERT INTO students (
    firstname, lastname, email, mobileno, 
    password, role, is_active
) VALUES (
    'Test', 'Student', 'teststudent@example.com', '+263771234567',
    '$2y$10$vQ7QKZ9YKZ9YKZ9YKZ9YKe', 'student', 'yes'
);

SET @student_id = LAST_INSERT_ID();

-- Link partner to this student
UPDATE partners 
SET student_id = @student_id 
WHERE partner_code = 'PTR-TEST-0001';
```

**Login Credentials**:
- Email: `teststudent@example.com`
- Password: `test123` (or whatever is set for test students)

---

## 📋 Step-by-Step Testing Access

### Step 1: Setup Test Data with Linked Student

Run this updated script:

```bash
php setup_partner_test_access.php
```

This will:
- Find or create a test student
- Link partner to that student
- Show you the login credentials

### Step 2: Login to Student Portal

1. Go to: `http://localhost/rhemazimbabwe/site/userlogin`
2. Login with student credentials
3. You should see **"Partners"** in the menu

### Step 3: Access Partner Settings

1. Click **"Partners"** in the top menu
2. You'll see your linked partner(s)
3. Click **"Settings"** or **"Giving Settings"**
4. OR directly: `http://localhost/rhemazimbabwe/user/partner/settings?partner_id=1`

---

## 🔍 Verification Queries

### Check if Partner is Linked

```sql
-- Check partner linkage
SELECT 
    p.id as partner_id,
    p.partner_code,
    p.firstname,
    p.lastname,
    p.student_id,
    p.staff_id,
    s.firstname as student_firstname,
    s.lastname as student_lastname,
    s.email as student_email
FROM partners p
LEFT JOIN students s ON s.id = p.student_id
WHERE p.id = 1;
```

**Expected**: Should show student information if linked

### Find Test Login Credentials

```sql
-- Get test student credentials
SELECT 
    id,
    firstname,
    lastname,
    email,
    username,
    'Default password is usually: test123 or check with admin'
FROM students 
WHERE email LIKE '%test%' 
   OR username LIKE '%test%'
LIMIT 5;
```

---

## 🛠️ Common Issues & Solutions

### Issue 1: "Partners" Menu Not Showing

**Cause**: Partner not linked to logged-in user

**Solution**:
```sql
-- Get logged-in student ID from session
-- Then link partner
UPDATE partners 
SET student_id = {your_student_id}
WHERE id = {partner_id};
```

### Issue 2: Page Asks for Login Even When Logged In

**Cause**: You're trying to access as a different user type

**Check**:
1. Are you logged in as admin? (Admin portal is separate)
2. Are you logged in as student/parent/staff?
3. Is the partner linked to your account?

**Solution**:
```sql
-- Verify your login and partner link
SELECT 
    p.*,
    s.email as student_email
FROM partners p
LEFT JOIN students s ON s.id = p.student_id
WHERE p.id = 1;
```

### Issue 3: Access Denied / Unauthorized

**Cause**: Partner belongs to different user

**Solution**: Link partner to your user account:

For Student:
```sql
UPDATE partners SET student_id = {your_student_id} WHERE id = 1;
```

For Staff:
```sql
UPDATE partners SET staff_id = {your_staff_id} WHERE id = 1;
```

For Parent: Partner matches by email/phone automatically

---

## 📝 Partner Linking Logic

The system checks partner ownership by:

### For Students:
- `partner.student_id` = student ID, OR
- `partner.email` = student email, OR  
- `partner.mobileno` = student phone

### For Parents:
- `partner.email` = parent guardian_email, OR
- `partner.mobileno` = parent guardian_phone

### For Staff:
- `partner.staff_id` = staff ID, OR
- `partner.email` = staff email

**If any match is found, they can access that partner's settings.**

---

## 🎯 Quick Test Access Setup

### Fastest Way to Test:

1. **Get admin to create student** (or use existing student)

2. **Run this query** (replace IDs):
```sql
-- Quick link setup
UPDATE partners 
SET student_id = 1,           -- Your student ID
    email = 's1@example.com', -- Match student's email
    mobileno = '1234567890'   -- Match student's phone
WHERE id = 1;
```

3. **Login as that student**

4. **Access**: `user/partner/settings?partner_id=1`

---

## 🔗 Test URLs by Portal Type

### Student Portal:
- Dashboard: `http://localhost/rhemazimbabwe/user/user/dashboard`
- Partners: `http://localhost/rhemazimbabwe/user/partner`
- Settings: `http://localhost/rhemazimbabwe/user/partner/settings?partner_id=1`

### Parent Portal:
- Same URLs as student (uses same controller)

### Staff Portal:
- Same URLs as student (uses same controller)

### Admin Portal (Different):
- Admin Dashboard: `http://localhost/rhemazimbabwe/admin/admin/dashboard`
- Partner Contributions: `http://localhost/rhemazimbabwe/admin/partnercontributions`

---

## ✅ Verification Checklist

- [ ] Have student/parent/staff login credentials
- [ ] Partner record exists in database
- [ ] Partner is linked (student_id, staff_id, or matching email/phone)
- [ ] Can login to student/parent/staff portal
- [ ] "Partners" menu appears
- [ ] Can access partner dashboard
- [ ] Can access partner settings

---

## 🚀 Ready-to-Use Test Scenario

**Scenario: Test with Student Portal**

1. Login as student: `student1@example.com` / `password123`
2. See "Partners" in menu
3. Click "Partners"
4. See partner list
5. Click "Settings" on partner
6. Configure giving settings
7. Save successfully

**If this doesn't work**, your partner is not linked to that student!

---

**Key Takeaway**: 
🔑 **Partners access their portal through student/parent/staff login, not a separate login!**






