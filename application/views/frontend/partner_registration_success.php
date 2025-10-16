<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .success-section {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 80px 0;
        }
        .success-card {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 15px;
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
        }
        .step-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        .step-number {
            background: #007bff;
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
    </style>
</head>
<body>
    <!-- Success Section -->
    <div class="success-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <i class="fas fa-check-circle success-icon"></i>
                    <h1 class="display-4 mb-4">Registration Successful!</h1>
                    <p class="lead mb-4">
                        Thank you for becoming a partner with Rhema Zimbabwe School. 
                        Your registration has been submitted and is being reviewed.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Details -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card success-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h3 class="text-success">
                                <i class="fas fa-envelope"></i>
                                What Happens Next?
                            </h3>
                        </div>

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

                        <div class="row mt-5">
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <i class="fas fa-phone fa-2x text-primary mb-3"></i>
                                        <h5>Need Help?</h5>
                                        <p class="text-muted">Contact our support team for assistance</p>
                                        <a href="tel:+263771234567" class="btn btn-outline-primary">
                                            <i class="fas fa-phone"></i> Call Us
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <i class="fas fa-envelope fa-2x text-success mb-3"></i>
                                        <h5>Email Support</h5>
                                        <p class="text-muted">Send us an email for detailed assistance</p>
                                        <a href="mailto:partners@rhemazimbabwe.edu" class="btn btn-outline-success">
                                            <i class="fas fa-envelope"></i> Email Us
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-5">
                            <a href="<?php echo base_url(); ?>" class="btn btn-primary btn-lg me-3">
                                <i class="fas fa-home"></i> Return Home
                            </a>
                            <a href="<?php echo base_url('partner_registration'); ?>" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-user-plus"></i> Register Another Partner
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Rhema Zimbabwe School</h5>
                    <p>Building tomorrow's leaders through quality education.</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>&copy; 2025 Rhema Zimbabwe School. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
