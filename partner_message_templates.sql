-- =============================================
-- PARTNER MODULE - MESSAGE TEMPLATES
-- =============================================
-- This file adds default email and SMS templates for the Partners Module
-- Run this after completing Phase 6.1 and 6.2

-- Add template_type column to email_template if it doesn't exist
ALTER TABLE `email_template`
ADD COLUMN `template_type` VARCHAR(50) DEFAULT 'general' AFTER `template`;

-- Add template_type column to sms_template if it doesn't exist
ALTER TABLE `sms_template`
ADD COLUMN `template_type` VARCHAR(50) DEFAULT 'general' AFTER `template`;

-- =============================================
-- PARTNER EMAIL TEMPLATES
-- =============================================

-- 1. Welcome Email Template
INSERT INTO `email_template` (`template`, `subject`, `message`, `template_type`, `created_at`)
VALUES (
    'Partner Welcome',
    'Welcome to {school_name} Partnership Program',
    '<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; }
        .content { padding: 30px; background: #f9f9f9; }
        .info-box { background: white; padding: 20px; margin: 20px 0; border-left: 4px solid #667eea; }
        .partner-code { font-size: 24px; font-weight: bold; color: #667eea; text-align: center; padding: 15px; background: #f0f0f0; border-radius: 8px; margin: 20px 0; }
        .button { display: inline-block; padding: 12px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #999; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to Partnership!</h1>
    </div>
    <div class="content">
        <h2>Dear {partner_firstname} {partner_lastname},</h2>

        <p>Thank you for joining the <strong>{school_name}</strong> Partnership Program! We are honored to have you as a partner in our mission.</p>

        <div class="info-box">
            <h3>Your Partnership Details:</h3>
            <ul>
                <li><strong>Partner Code:</strong> {partner_code}</li>
                <li><strong>Account Type:</strong> {account_type}</li>
                <li><strong>Giving Type:</strong> {giving_type}</li>
                <li><strong>Giving Frequency:</strong> {giving_frequency}</li>
                <li><strong>Contribution Amount:</strong> {currency} {contribution_amount}</li>
            </ul>
        </div>

        <div class="partner-code">
            Your Partner Code: {partner_code}
        </div>

        <p><strong>What Happens Next?</strong></p>
        <ul>
            <li>Your partnership is currently under review</li>
            <li>Our admin team will activate your account within 1-2 business days</li>
            <li>You will receive a confirmation email once activated</li>
            <li>You can then access your partner dashboard and start contributing</li>
        </ul>

        <p style="text-align: center;">
            <a href="{portal_url}" class="button">Access Partner Portal</a>
        </p>

        <p><strong>Need Help?</strong><br>
        Contact us at <a href="mailto:{school_email}">{school_email}</a> or call {school_phone}</p>

        <p>Thank you for your commitment to making a difference!</p>

        <p>Best regards,<br>
        <strong>{school_name} Team</strong></p>
    </div>
    <div class="footer">
        <p>&copy; {current_year} {school_name}. All rights reserved.</p>
        <p>{school_address}</p>
    </div>
</body>
</html>',
    'partner',
    NOW()
);

-- 2. Activation Confirmation Email
INSERT INTO `email_template` (`template`, `subject`, `message`, `template_type`, `created_at`)
VALUES (
    'Partner Activated',
    'Your Partnership Has Been Activated - {school_name}',
    '<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .header { background: linear-gradient(135deg, #00a65a 0%, #00c77a 100%); color: white; padding: 20px; text-align: center; }
        .content { padding: 30px; background: #f9f9f9; }
        .success-box { background: #d4edda; border: 2px solid #00a65a; padding: 20px; border-radius: 8px; text-align: center; margin: 20px 0; }
        .button { display: inline-block; padding: 12px 30px; background: #00a65a; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #999; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>✅ Partnership Activated!</h1>
    </div>
    <div class="content">
        <h2>Congratulations {partner_firstname}!</h2>

        <div class="success-box">
            <h3>🎉 Your partnership with {school_name} is now active!</h3>
        </div>

        <p>You can now access all partner benefits and start making contributions through your partner portal.</p>

        <p><strong>Your Partner Benefits:</strong></p>
        <ul>
            <li>Access to exclusive partner dashboard</li>
            <li>View contribution history and receipts</li>
            <li>Download annual tax statements</li>
            <li>Manage giving preferences</li>
            <li>Access to partner resources (if enabled)</li>
        </ul>

        <p style="text-align: center;">
            <a href="{portal_url}" class="button">Access Your Dashboard</a>
        </p>

        <p><strong>Remember Your Partner Code:</strong> {partner_code}</p>

        <p>We look forward to a great partnership with you!</p>

        <p>Best regards,<br>
        <strong>{school_name} Team</strong></p>
    </div>
    <div class="footer">
        <p>&copy; {current_year} {school_name}. All rights reserved.</p>
    </div>
</body>
</html>',
    'partner',
    NOW()
);

-- 3. Contribution Thank You Email
INSERT INTO `email_template` (`template`, `subject`, `message`, `template_type`, `created_at`)
VALUES (
    'Partner Contribution Thank You',
    'Thank You for Your Contribution - {school_name}',
    '<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .header { background: linear-gradient(135deg, #3c8dbc 0%, #5fa3d0 100%); color: white; padding: 20px; text-align: center; }
        .content { padding: 30px; background: #f9f9f9; }
        .amount-box { background: white; border: 3px solid #3c8dbc; padding: 30px; text-align: center; margin: 20px 0; border-radius: 8px; }
        .amount { font-size: 36px; font-weight: bold; color: #3c8dbc; }
        .receipt-table { width: 100%; background: white; margin: 20px 0; }
        .receipt-table th { background: #3c8dbc; color: white; padding: 10px; text-align: left; }
        .receipt-table td { padding: 10px; border-bottom: 1px solid #ddd; }
        .button { display: inline-block; padding: 12px 30px; background: #3c8dbc; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #999; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>❤️ Thank You!</h1>
    </div>
    <div class="content">
        <h2>Dear {partner_firstname} {partner_lastname},</h2>

        <p>Thank you for your generous contribution to <strong>{school_name}</strong>!</p>

        <div class="amount-box">
            <div class="amount">{currency} {contribution_amount}</div>
            <p>Received on {contribution_date}</p>
        </div>

        <p><strong>Contribution Details:</strong></p>
        <table class="receipt-table">
            <tr>
                <th>Receipt Number</th>
                <td>{receipt_number}</td>
            </tr>
            <tr>
                <th>Giving Type</th>
                <td>{giving_type}</td>
            </tr>
            <tr>
                <th>Payment Method</th>
                <td>{payment_method}</td>
            </tr>
            <tr>
                <th>Transaction ID</th>
                <td>{transaction_id}</td>
            </tr>
        </table>

        <p>Your contribution helps us continue our mission of providing quality education. Your support makes a real difference in the lives of our students.</p>

        <p style="text-align: center;">
            <a href="{receipt_url}" class="button">Download Receipt</a>
        </p>

        <p><strong>Total Contributions This Year:</strong> {currency} {year_total}</p>

        <p>With heartfelt gratitude,</p>

        <p>Best regards,<br>
        <strong>{school_name} Team</strong></p>
    </div>
    <div class="footer">
        <p>&copy; {current_year} {school_name}. All rights reserved.</p>
        <p>This is a computer-generated receipt. No signature required.</p>
    </div>
</body>
</html>',
    'partner',
    NOW()
);

-- 4. Contribution Reminder Email
INSERT INTO `email_template` (`template`, `subject`, `message`, `template_type`, `created_at`)
VALUES (
    'Partner Contribution Reminder',
    'Friendly Reminder: {giving_frequency} Contribution - {school_name}',
    '<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .header { background: linear-gradient(135deg, #f39c12 0%, #f8b739 100%); color: white; padding: 20px; text-align: center; }
        .content { padding: 30px; background: #f9f9f9; }
        .reminder-box { background: #fff3cd; border-left: 4px solid #f39c12; padding: 20px; margin: 20px 0; }
        .button { display: inline-block; padding: 12px 30px; background: #f39c12; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #999; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>⏰ Contribution Reminder</h1>
    </div>
    <div class="content">
        <h2>Hi {partner_firstname},</h2>

        <p>This is a friendly reminder about your {giving_frequency} contribution to <strong>{school_name}</strong>.</p>

        <div class="reminder-box">
            <h3>Your Contribution Details:</h3>
            <ul>
                <li><strong>Giving Frequency:</strong> {giving_frequency}</li>
                <li><strong>Contribution Amount:</strong> {currency} {contribution_amount}</li>
                <li><strong>Giving Type:</strong> {giving_type}</li>
                <li><strong>Last Contribution:</strong> {last_contribution_date}</li>
            </ul>
        </div>

        <p>Your continued support helps us provide quality education and maintain our programs.</p>

        <p style="text-align: center;">
            <a href="{payment_url}" class="button">Make Contribution</a>
        </p>

        <p><strong>Need to Update Your Giving Preferences?</strong><br>
        You can manage your settings anytime through your partner dashboard.</p>

        <p>Thank you for being a valued partner!</p>

        <p>Best regards,<br>
        <strong>{school_name} Team</strong></p>

        <p style="font-size: 12px; color: #999;"><em>If you have already made your contribution or need to adjust your giving schedule, please contact us or update your preferences in the partner portal.</em></p>
    </div>
    <div class="footer">
        <p>&copy; {current_year} {school_name}. All rights reserved.</p>
    </div>
</body>
</html>',
    'partner',
    NOW()
);

-- =============================================
-- PARTNER SMS TEMPLATES
-- =============================================

-- 1. Welcome SMS Template
INSERT INTO `sms_template` (`template`, `message`, `template_type`, `created_at`)
VALUES (
    'Partner Welcome SMS',
    'Welcome {partner_firstname}! Thank you for joining {school_name} Partnership. Your Partner Code: {partner_code}. Your account is under review and will be activated soon.',
    'partner',
    NOW()
);

-- 2. Activation SMS Template
INSERT INTO `sms_template` (`template`, `message`, `template_type`, `created_at`)
VALUES (
    'Partner Activated SMS',
    'Good news {partner_firstname}! Your partnership with {school_name} is now active. Access your dashboard: {portal_url}. Partner Code: {partner_code}',
    'partner',
    NOW()
);

-- 3. Contribution Thank You SMS
INSERT INTO `sms_template` (`template`, `message`, `template_type`, `created_at`)
VALUES (
    'Partner Contribution Thank You SMS',
    'Thank you {partner_firstname}! Your contribution of {currency} {contribution_amount} has been received. Receipt #{receipt_number}. God bless you! - {school_name}',
    'partner',
    NOW()
);

-- 4. Contribution Reminder SMS
INSERT INTO `sms_template` (`template`, `message`, `template_type`, `created_at`)
VALUES (
    'Partner Contribution Reminder SMS',
    'Hi {partner_firstname}, friendly reminder: Your {giving_frequency} contribution of {currency} {contribution_amount} to {school_name} is due. Thank you for your continued support!',
    'partner',
    NOW()
);

-- =============================================
-- AVAILABLE TEMPLATE VARIABLES
-- =============================================
/*
Email & SMS Templates can use these variables:

PARTNER INFO:
{partner_id}
{partner_code}
{partner_firstname}
{partner_lastname}
{partner_email}
{partner_phone}
{account_type}
{organization_name}
{status}

GIVING INFO:
{giving_type}
{giving_frequency}
{contribution_amount}
{currency}
{start_date}
{last_contribution_date}

CONTRIBUTION INFO:
{receipt_number}
{contribution_date}
{payment_method}
{transaction_id}
{year_total}

SCHOOL INFO:
{school_name}
{school_email}
{school_phone}
{school_address}
{current_year}

URLS:
{portal_url}
{receipt_url}
{payment_url}
*/

-- =============================================
-- VERIFICATION
-- =============================================
-- Check inserted templates
SELECT id, template, subject, template_type, created_at
FROM email_template
WHERE template_type = 'partner'
ORDER BY id DESC;

SELECT id, template, template_type, created_at
FROM sms_template
WHERE template_type = 'partner'
ORDER BY id DESC;
