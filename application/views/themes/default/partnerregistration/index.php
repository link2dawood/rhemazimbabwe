<div class="container-fluid ptb-60">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center mb-40">
                    <h1 class="page-title">Become a Partner</h1>
                    <p class="lead">Join us in making a difference! Choose your partnership type below.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Individual Partnership -->
            <div class="col-md-6">
                <div class="account-type-card">
                    <div class="card-icon">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                    <h2>Individual Partner</h2>
                    <p class="description">
                        Partner with us as an individual to support our mission and students.
                    </p>
                    <ul class="features">
                        <li><i class="fa fa-check text-success"></i> Flexible contribution options</li>
                        <li><i class="fa fa-check text-success"></i> Link to specific student (optional)</li>
                        <li><i class="fa fa-check text-success"></i> Regular updates and reports</li>
                        <li><i class="fa fa-check text-success"></i> Access to partner resources</li>
                        <li><i class="fa fa-check text-success"></i> Tax receipt for contributions</li>
                    </ul>
                    <a href="<?php echo base_url('partnerregistration/register/individual'); ?>" class="btn btn-primary btn-lg btn-block">
                        <i class="fa fa-arrow-right"></i> Register as Individual
                    </a>
                </div>
            </div>

            <!-- Organization Partnership -->
            <div class="col-md-6">
                <div class="account-type-card">
                    <div class="card-icon">
                        <i class="fa fa-building fa-5x"></i>
                    </div>
                    <h2>Organization Partner</h2>
                    <p class="description">
                        Partner with us as an organization, church, or company to make a bigger impact.
                    </p>
                    <ul class="features">
                        <li><i class="fa fa-check text-success"></i> Corporate giving programs</li>
                        <li><i class="fa fa-check text-success"></i> Multiple contact persons</li>
                        <li><i class="fa fa-check text-success"></i> Branded recognition</li>
                        <li><i class="fa fa-check text-success"></i> Special partnership benefits</li>
                        <li><i class="fa fa-check text-success"></i> Annual partnership reports</li>
                    </ul>
                    <a href="<?php echo base_url('partnerregistration/register/organization'); ?>" class="btn btn-success btn-lg btn-block">
                        <i class="fa fa-arrow-right"></i> Register as Organization
                    </a>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="row mt-40">
            <div class="col-md-12">
                <div class="info-section">
                    <h3>Why Partner With Us?</h3>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="benefit-box">
                                <i class="fa fa-heart text-danger fa-3x"></i>
                                <h4>Make a Difference</h4>
                                <p>Your support directly impacts students' lives and educational journeys.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="benefit-box">
                                <i class="fa fa-shield text-primary fa-3x"></i>
                                <h4>Transparent Reporting</h4>
                                <p>Receive regular updates on how your contributions are making an impact.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="benefit-box">
                                <i class="fa fa-users text-success fa-3x"></i>
                                <h4>Join a Community</h4>
                                <p>Connect with like-minded partners committed to education excellence.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="row mt-40">
            <div class="col-md-12 text-center">
                <div class="cta-section">
                    <h3>Already Registered?</h3>
                    <p>Check your registration status and track your contributions.</p>
                    <a href="<?php echo base_url('partnerregistration/checkStatus'); ?>" class="btn btn-info btn-lg">
                        <i class="fa fa-search"></i> Check Registration Status
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.ptb-60 {
    padding: 60px 0;
}

.mb-40 {
    margin-bottom: 40px;
}

.mt-40 {
    margin-top: 40px;
}

.page-title {
    font-size: 42px;
    font-weight: bold;
    color: #333;
    margin-bottom: 15px;
}

.account-type-card {
    background: #fff;
    border-radius: 10px;
    padding: 40px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    text-align: center;
    margin-bottom: 30px;
    transition: transform 0.3s ease;
}

.account-type-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 5px 30px rgba(0,0,0,0.15);
}

.card-icon {
    color: #3c8dbc;
    margin-bottom: 20px;
}

.account-type-card h2 {
    font-size: 28px;
    font-weight: bold;
    color: #333;
    margin-bottom: 15px;
}

.account-type-card .description {
    font-size: 16px;
    color: #666;
    margin-bottom: 25px;
}

.account-type-card .features {
    list-style: none;
    padding: 0;
    margin-bottom: 30px;
    text-align: left;
}

.account-type-card .features li {
    padding: 10px 0;
    font-size: 15px;
    color: #555;
}

.account-type-card .features li i {
    margin-right: 10px;
}

.info-section {
    background: #f9f9f9;
    padding: 40px;
    border-radius: 10px;
}

.info-section h3 {
    text-align: center;
    font-size: 32px;
    font-weight: bold;
    margin-bottom: 40px;
    color: #333;
}

.benefit-box {
    text-align: center;
    padding: 20px;
}

.benefit-box i {
    margin-bottom: 15px;
}

.benefit-box h4 {
    font-size: 20px;
    font-weight: bold;
    color: #333;
    margin-bottom: 10px;
}

.benefit-box p {
    color: #666;
    font-size: 14px;
}

.cta-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    padding: 40px;
    border-radius: 10px;
}

.cta-section h3 {
    font-size: 28px;
    margin-bottom: 15px;
}

.cta-section p {
    font-size: 16px;
    margin-bottom: 25px;
}

.btn-lg {
    padding: 15px 40px;
    font-size: 18px;
    border-radius: 5px;
}
</style>