<style>
.success-section {
    margin-top: 80px;
    padding: 60px 0;
}

.success-icon {
    font-size: 4rem;
    color: #28a745;
    margin-bottom: 1rem;
}

.next-steps {
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 2rem;
    margin: 2rem 0;
}

.step-item {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.step-number {
    background: #3c8dbc;
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-weight: bold;
}

.contact-card {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    text-align: center;
}

.contact-card i {
    font-size: 2rem;
    margin-bottom: 15px;
}

.contact-card h5 {
    font-weight: bold;
    margin-bottom: 10px;
}
</style>

<div class="row success-section">
    <div class="col-md-12 text-center">
        <i class="fa fa-check-circle success-icon"></i>
        <h1 class="page-title">Registration Successful!</h1>
        <p class="lead">
            Thank you for becoming a partner with Rhema Zimbabwe School. 
            Your registration has been submitted and is being reviewed.
        </p>
    </div>
</div>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-envelope"></i> What Happens Next?
                </h3>
            </div>
            <div class="panel-body">
                <div class="next-steps">
                    <div class="step-item">
                        <div class="step-number">1</div>
                        <div>
                            <h6>Confirmation Email</h6>
                            <p class="mb-0 text-muted">You will receive a confirmation email with your partner details and next steps.</p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">2</div>
                        <div>
                            <h6>Review Process</h6>
                            <p class="mb-0 text-muted">Our team will review your registration and verify your information.</p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">3</div>
                        <div>
                            <h6>Account Activation</h6>
                            <p class="mb-0 text-muted">Once approved, your partner account will be activated and you'll receive login credentials.</p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">4</div>
                        <div>
                            <h6>Start Contributing</h6>
                            <p class="mb-0 text-muted">You can begin making contributions and track your impact through your partner portal.</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="contact-card">
                            <i class="fa fa-phone text-primary"></i>
                            <h5>Need Help?</h5>
                            <p class="text-muted">Contact our support team for assistance</p>
                            <a href="tel:+263771234567" class="btn btn-outline-primary">
                                <i class="fa fa-phone"></i> Call Us
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="contact-card">
                            <i class="fa fa-envelope text-success"></i>
                            <h5>Email Support</h5>
                            <p class="text-muted">Send us an email for detailed assistance</p>
                            <a href="mailto:partners@rhemazimbabwe.edu" class="btn btn-outline-success">
                                <i class="fa fa-envelope"></i> Email Us
                            </a>
                        </div>
                    </div>
                </div>

                <div class="text-center" style="margin-top: 30px;">
                    <a href="<?php echo base_url(); ?>" class="btn btn-primary btn-lg">
                        <i class="fa fa-home"></i> Return Home
                    </a>
                    <a href="<?php echo base_url('partner_registration'); ?>" class="btn btn-default btn-lg">
                        <i class="fa fa-user-plus"></i> Register Another Partner
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
