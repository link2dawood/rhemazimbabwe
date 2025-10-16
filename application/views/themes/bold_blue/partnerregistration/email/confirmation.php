<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner Registration Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">

    <!-- Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="margin: 0; font-size: 28px;">Welcome to Rhema Zimbabwe Partners!</h1>
    </div>

    <!-- Body -->
    <div style="background: #fff; padding: 30px; border: 1px solid #ddd; border-top: none;">
        <p style="font-size: 16px;">Dear <?php echo $partner_data['firstname'] . ' ' . $partner_data['lastname']; ?>,</p>

        <p>Thank you for registering to become a partner with Rhema Zimbabwe! We are excited to have you join our community of supporters.</p>

        <div style="background: #f9f9f9; padding: 20px; border-left: 4px solid #3c8dbc; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #3c8dbc;">Your Registration Details</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; width: 40%;">Partner Code:</td>
                    <td style="padding: 8px 0; color: #3c8dbc; font-weight: bold;"><?php echo $partner_data['partner_code']; ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Name:</td>
                    <td style="padding: 8px 0;"><?php echo $partner_data['firstname'] . ' ' . $partner_data['lastname']; ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Email:</td>
                    <td style="padding: 8px 0;"><?php echo $partner_data['email']; ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Phone:</td>
                    <td style="padding: 8px 0;"><?php echo $partner_data['mobileno']; ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Registration Date:</td>
                    <td style="padding: 8px 0;"><?php echo date('F d, Y'); ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Status:</td>
                    <td style="padding: 8px 0;"><span style="background: #f39c12; color: #fff; padding: 4px 12px; border-radius: 3px; font-size: 12px;">Pending Approval</span></td>
                </tr>
            </table>
        </div>

        <h3 style="color: #3c8dbc; margin-top: 30px;">What Happens Next?</h3>
        <ol style="padding-left: 20px;">
            <li style="margin-bottom: 10px;">
                <strong>Review Process:</strong> Our team will review your registration within 1-2 business days.
            </li>
            <li style="margin-bottom: 10px;">
                <strong>Approval Notification:</strong> You'll receive another email once your registration is approved.
            </li>
            <li style="margin-bottom: 10px;">
                <strong>Partnership Begins:</strong> After approval, you'll receive information on how to make your first contribution.
            </li>
            <li style="margin-bottom: 10px;">
                <strong>Stay Connected:</strong> You'll receive regular updates about the impact of your partnership.
            </li>
        </ol>

        <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="margin: 0;"><strong>⚠️ Important:</strong> Please save your <strong>Partner Code: <?php echo $partner_data['partner_code']; ?></strong> for future reference.</p>
        </div>

        <h3 style="color: #3c8dbc;">Check Your Status</h3>
        <p>You can check your registration status anytime by visiting:</p>
        <div style="text-align: center; margin: 20px 0;">
            <a href="<?php echo $base_url; ?>partnerregistration/checkStatus" style="display: inline-block; background: #3c8dbc; color: #fff; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">Check Registration Status</a>
        </div>

        <h3 style="color: #3c8dbc;">Need Help?</h3>
        <p>If you have any questions or need assistance, please don't hesitate to contact us:</p>
        <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: 8px;">📧 Email: <a href="mailto:partners@rhemazimbabwe.com" style="color: #3c8dbc;">partners@rhemazimbabwe.com</a></li>
            <li style="margin-bottom: 8px;">📞 Phone: +263 XXX XXXX</li>
            <li style="margin-bottom: 8px;">🌐 Website: <a href="<?php echo $base_url; ?>" style="color: #3c8dbc;"><?php echo $base_url; ?></a></li>
        </ul>

        <p style="margin-top: 30px;">Thank you for choosing to partner with us in transforming lives through education!</p>

        <p style="margin-top: 20px;">
            Warm regards,<br>
            <strong>The Rhema Zimbabwe Team</strong>
        </p>
    </div>

    <!-- Footer -->
    <div style="background: #222; color: #fff; padding: 20px; text-align: center; border-radius: 0 0 10px 10px;">
        <p style="margin: 0; font-size: 14px;">
            &copy; <?php echo date('Y'); ?> Rhema Zimbabwe. All rights reserved.
        </p>
        <p style="margin: 10px 0 0 0; font-size: 12px; color: #999;">
            This email was sent to <?php echo $partner_data['email']; ?> regarding your partner registration.
        </p>
    </div>

</body>
</html>