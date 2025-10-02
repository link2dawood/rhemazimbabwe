<div class="container-fluid ptb-60">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="success-container">
                    <div class="success-icon">
                        <i class="fa fa-check-circle fa-5x text-success"></i>
                    </div>

                    <h1 class="success-title">Registration Successful!</h1>

                    <p class="lead text-center">
                        Thank you for registering as a partner with Rhema Zimbabwe.
                    </p>

                    <div class="partner-info-box">
                        <h3>Your Partner Information</h3>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Partner Code:</th>
                                <td><strong class="text-primary"><?php echo $partner->partner_code; ?></strong></td>
                            </tr>
                            <tr>
                                <th>Name:</th>
                                <td><?php echo $partner->firstname . ' ' . $partner->lastname; ?></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><?php echo $partner->email; ?></td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td><?php echo $partner->mobileno; ?></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="label label-warning">Pending Approval</span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="alert alert-info">
                        <h4><i class="fa fa-info-circle"></i> What's Next?</h4>
                        <ol class="next-steps">
                            <li>
                                <strong>Verification:</strong> Our team will review your registration within 1-2 business days.
                            </li>
                            <li>
                                <strong>Confirmation Email:</strong> You'll receive a confirmation email at <strong><?php echo $partner->email; ?></strong>
                            </li>
                            <li>
                                <strong>Approval Notification:</strong> Once approved, you'll receive your partnership details and instructions.
                            </li>
                            <?php if ($partner->student_id): ?>
                            <li>
                                <strong>Student Account Linked:</strong> Your partnership has been linked to your student account.
                            </li>
                            <?php endif; ?>
                        </ol>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle"></i>
                        <strong>Important:</strong> Please save your partner code (<strong><?php echo $partner->partner_code; ?></strong>) for future reference.
                    </div>

                    <div class="action-buttons text-center">
                        <a href="<?php echo base_url(); ?>" class="btn btn-default btn-lg">
                            <i class="fa fa-home"></i> Back to Homepage
                        </a>
                        <a href="<?php echo base_url('partnerregistration/checkStatus'); ?>" class="btn btn-primary btn-lg">
                            <i class="fa fa-search"></i> Check Status
                        </a>
                        <button class="btn btn-info btn-lg" onclick="window.print()">
                            <i class="fa fa-print"></i> Print Details
                        </button>
                    </div>

                    <div class="contact-info text-center mt-40">
                        <h4>Need Help?</h4>
                        <p>
                            Contact us at: <a href="mailto:partners@rhemazimbabwe.com">partners@rhemazimbabwe.com</a><br>
                            Or call: <a href="tel:+263XXXXXXXXX">+263 XXX XXXX</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.ptb-60 {
    padding: 60px 0;
}

.mt-40 {
    margin-top: 40px;
}

.success-container {
    background: #fff;
    padding: 50px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.success-icon {
    text-align: center;
    margin-bottom: 30px;
    animation: scaleIn 0.5s ease-out;
}

@keyframes scaleIn {
    0% { transform: scale(0); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.success-title {
    text-align: center;
    font-size: 36px;
    font-weight: bold;
    color: #00a65a;
    margin-bottom: 20px;
}

.partner-info-box {
    background: #f9f9f9;
    padding: 25px;
    border-radius: 8px;
    margin: 30px 0;
}

.partner-info-box h3 {
    margin-top: 0;
    margin-bottom: 20px;
    color: #333;
}

.next-steps {
    margin: 15px 0;
    padding-left: 25px;
}

.next-steps li {
    margin: 10px 0;
    line-height: 1.6;
}

.action-buttons {
    margin: 30px 0;
}

.action-buttons .btn {
    margin: 5px;
}

.contact-info {
    padding: 20px;
    background: #f5f5f5;
    border-radius: 8px;
}

@media print {
    .action-buttons,
    .contact-info {
        display: none;
    }
}
</style>