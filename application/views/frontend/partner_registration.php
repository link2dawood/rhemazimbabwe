<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
        }
        .registration-card {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 15px;
        }
        .account-type-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #e9ecef;
        }
        .account-type-card:hover {
            border-color: #007bff;
            transform: translateY(-5px);
        }
        .account-type-card.selected {
            border-color: #007bff;
            background-color: #f8f9ff;
        }
        .giving-type-item {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        .giving-type-item:hover {
            border-color: #007bff;
            background-color: #f8f9ff;
        }
        .total-amount {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 20px;
            border-radius: 10px;
            font-size: 1.2em;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="display-4 mb-4">
                        <i class="fas fa-heart text-warning"></i>
                        Become a Partner
                    </h1>
                    <p class="lead mb-4">
                        Join us in making a difference in education. Support our students and help build a brighter future.
                    </p>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <i class="fas fa-graduation-cap fa-3x mb-3"></i>
                            <h5>Support Education</h5>
                            <p>Help students achieve their dreams</p>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <h5>Join Community</h5>
                            <p>Be part of our growing family</p>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-chart-line fa-3x mb-3"></i>
                            <h5>Track Impact</h5>
                            <p>See how your support makes a difference</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Section -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card registration-card">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="mb-0">
                            <i class="fas fa-user-plus"></i>
                            Partner Registration
                        </h3>
                    </div>
                    <div class="card-body p-5">
                        <!-- Account Type Selection -->
                        <div class="row mb-5">
                            <div class="col-12">
                                <h4 class="text-center mb-4">Choose Your Account Type</h4>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card account-type-card h-100" onclick="selectAccountType('individual')" id="individual-card">
                                    <div class="card-body text-center">
                                        <i class="fas fa-user fa-3x text-primary mb-3"></i>
                                        <h5>Individual Partner</h5>
                                        <p class="text-muted">Register as an individual supporter</p>
                                        <ul class="list-unstyled text-start">
                                            <li><i class="fas fa-check text-success"></i> Personal giving preferences</li>
                                            <li><i class="fas fa-check text-success"></i> Direct impact tracking</li>
                                            <li><i class="fas fa-check text-success"></i> Personal communication</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card account-type-card h-100" onclick="selectAccountType('organization')" id="organization-card">
                                    <div class="card-body text-center">
                                        <i class="fas fa-building fa-3x text-success mb-3"></i>
                                        <h5>Organization Partner</h5>
                                        <p class="text-muted">Register as a business or organization</p>
                                        <ul class="list-unstyled text-start">
                                            <li><i class="fas fa-check text-success"></i> Corporate giving programs</li>
                                            <li><i class="fas fa-check text-success"></i> Tax-deductible contributions</li>
                                            <li><i class="fas fa-check text-success"></i> Partnership recognition</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Registration Forms -->
                        <div id="registration-forms">
                            <!-- Individual Form -->
                            <div id="individual-form" style="display: none;">
                                <form action="<?php echo base_url('partner_registration/process_individual'); ?>" method="post" id="individual-registration-form">
                                    <h4 class="mb-4">
                                        <i class="fas fa-user text-primary"></i>
                                        Individual Registration
                                    </h4>
                                    
                                    <!-- Personal Information -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="firstname">First Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="firstname" name="firstname" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="lastname">Last Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="lastname" name="lastname" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Information -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="email">Email Address <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="email" name="email" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="mobileno">Phone Number <span class="text-danger">*</span></label>
                                                <input type="tel" class="form-control" id="mobileno" name="mobileno" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Address Information -->
                                    <div class="form-group mb-3">
                                        <label for="address">Billing Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="city">City <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="city" name="city" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="state">State/Province</label>
                                                <input type="text" class="form-control" id="state" name="state">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="country">Country <span class="text-danger">*</span></label>
                                                <select class="form-control" id="country" name="country" required>
                                                    <option value="Zimbabwe" selected>Zimbabwe</option>
                                                    <option value="South Africa">South Africa</option>
                                                    <option value="Botswana">Botswana</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Giving Preferences -->
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="mb-3">
                                                <i class="fas fa-gift text-success"></i>
                                                Giving Preferences
                                            </h5>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label>Select Giving Types <span class="text-danger">*</span></label>
                                                <div id="giving-types-individual">
                                                    <?php foreach ($giving_types as $type): ?>
                                                    <div class="giving-type-item">
                                                        <div class="form-check">
                                                            <input class="form-check-input giving-type-checkbox" type="checkbox" 
                                                                   name="giving_types[]" value="<?php echo $type->id; ?>" 
                                                                   id="type_<?php echo $type->id; ?>_individual"
                                                                   onchange="updateTotal()">
                                                            <label class="form-check-label" for="type_<?php echo $type->id; ?>_individual">
                                                                <strong><?php echo $type->name; ?></strong>
                                                                <br><small class="text-muted"><?php echo $type->description; ?></small>
                                                            </label>
                                                        </div>
                                                        <div class="input-group mt-2">
                                                            <span class="input-group-text">$</span>
                                                            <input type="number" class="form-control amount-input" 
                                                                   name="amounts[]" placeholder="0.00" 
                                                                   min="0" step="0.01" onchange="updateTotal()">
                                                        </div>
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="giving_frequency_id">Giving Frequency <span class="text-danger">*</span></label>
                                                <select class="form-control" id="giving_frequency_id" name="giving_frequency_id" required>
                                                    <option value="">Select Frequency</option>
                                                    <?php foreach ($giving_frequencies as $frequency): ?>
                                                    <option value="<?php echo $frequency->id; ?>">
                                                        <?php echo $frequency->name; ?>
                                                        <?php if ($frequency->days_interval): ?>
                                                        (Every <?php echo $frequency->days_interval; ?> days)
                                                        <?php endif; ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="currency">Currency</label>
                                                <select class="form-control" id="currency" name="currency">
                                                    <option value="USD">USD</option>
                                                    <option value="ZWL">ZWL</option>
                                                    <option value="ZAR">ZAR</option>
                                                </select>
                                            </div>

                                            <div class="total-amount text-center">
                                                <div>Total Contribution</div>
                                                <div id="total-amount-display">$0.00</div>
                                                <input type="hidden" id="total_amount" name="total_amount" value="0">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Account Creation -->
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="mb-3">
                                                <i class="fas fa-user-circle text-info"></i>
                                                Create Account (Optional)
                                            </h5>
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle"></i>
                                                Adding a password will create an account and allow you to access your transaction history as well as manage account details. This is an optional step and can be completed at a later date if desired.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="create_account" name="create_account" onchange="togglePasswordFields()">
                                                <label class="form-check-label" for="create_account">
                                                    Create an Account
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="password-fields" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="password">Password</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" id="password" name="password">
                                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', this)" title="Show/Hide Password">
                                                            <i class="fas fa-eye" id="password-icon"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="confirm_password">Confirm Password</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('confirm_password', this)" title="Show/Hide Password">
                                                            <i class="fas fa-eye" id="confirm_password-icon"></i>
                                                        </button>
                                                    </div>
                                                    <small class="text-danger" id="password-match-error" style="display: none;">Passwords do not match</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Additional Notes -->
                                    <div class="form-group mb-4">
                                        <label for="notes">Additional Notes</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any additional information you'd like to share..."></textarea>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-paper-plane"></i>
                                            Submit Registration
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Organization Form -->
                            <div id="organization-form" style="display: none;">
                                <form action="<?php echo base_url('partner_registration/process_organization'); ?>" method="post" id="organization-registration-form">
                                    <h4 class="mb-4">
                                        <i class="fas fa-building text-success"></i>
                                        Organization Registration
                                    </h4>
                                    
                                    <!-- Organization Information -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="organization_name">Organization Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="organization_name" name="organization_name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="organization_type">Organization Type <span class="text-danger">*</span></label>
                                                <select class="form-control" id="organization_type" name="organization_type" required>
                                                    <option value="">Select Type</option>
                                                    <option value="Ministry">Ministry</option>
                                                    <option value="Church">Church</option>
                                                    <option value="Business">Business</option>
                                                    <option value="NGO">NGO</option>
                                                    <option value="Foundation">Foundation</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Person Information -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="firstname">Contact First Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="firstname" name="firstname" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="lastname">Contact Last Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="lastname" name="lastname" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Information -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="email">Email Address <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="email" name="email" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="mobileno">Phone Number <span class="text-danger">*</span></label>
                                                <input type="tel" class="form-control" id="mobileno" name="mobileno" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Address Information -->
                                    <div class="form-group mb-3">
                                        <label for="address">Billing Address <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="city">City <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="city" name="city" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="state">State/Province</label>
                                                <input type="text" class="form-control" id="state" name="state">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="country">Country <span class="text-danger">*</span></label>
                                                <select class="form-control" id="country" name="country" required>
                                                    <option value="Zimbabwe" selected>Zimbabwe</option>
                                                    <option value="South Africa">South Africa</option>
                                                    <option value="Botswana">Botswana</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Giving Preferences -->
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="mb-3">
                                                <i class="fas fa-gift text-success"></i>
                                                Giving Preferences
                                            </h5>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label>Select Giving Types <span class="text-danger">*</span></label>
                                                <div id="giving-types-organization">
                                                    <?php foreach ($giving_types as $type): ?>
                                                    <div class="giving-type-item">
                                                        <div class="form-check">
                                                            <input class="form-check-input giving-type-checkbox" type="checkbox" 
                                                                   name="giving_types[]" value="<?php echo $type->id; ?>" 
                                                                   id="type_<?php echo $type->id; ?>_organization"
                                                                   onchange="updateTotal()">
                                                            <label class="form-check-label" for="type_<?php echo $type->id; ?>_organization">
                                                                <strong><?php echo $type->name; ?></strong>
                                                                <br><small class="text-muted"><?php echo $type->description; ?></small>
                                                            </label>
                                                        </div>
                                                        <div class="input-group mt-2">
                                                            <span class="input-group-text">$</span>
                                                            <input type="number" class="form-control amount-input" 
                                                                   name="amounts[]" placeholder="0.00" 
                                                                   min="0" step="0.01" onchange="updateTotal()">
                                                        </div>
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="giving_frequency_id">Giving Frequency <span class="text-danger">*</span></label>
                                                <select class="form-control" id="giving_frequency_id" name="giving_frequency_id" required>
                                                    <option value="">Select Frequency</option>
                                                    <?php foreach ($giving_frequencies as $frequency): ?>
                                                    <option value="<?php echo $frequency->id; ?>">
                                                        <?php echo $frequency->name; ?>
                                                        <?php if ($frequency->days_interval): ?>
                                                        (Every <?php echo $frequency->days_interval; ?> days)
                                                        <?php endif; ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="currency">Currency</label>
                                                <select class="form-control" id="currency" name="currency">
                                                    <option value="USD">USD</option>
                                                    <option value="ZWL">ZWL</option>
                                                    <option value="ZAR">ZAR</option>
                                                </select>
                                            </div>

                                            <div class="total-amount text-center">
                                                <div>Total Contribution</div>
                                                <div id="total-amount-display">$0.00</div>
                                                <input type="hidden" id="total_amount" name="total_amount" value="0">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Account Creation -->
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="mb-3">
                                                <i class="fas fa-user-circle text-info"></i>
                                                Create Account (Optional)
                                            </h5>
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle"></i>
                                                Adding a password will create an account and allow you to access your transaction history as well as manage account details. This is an optional step and can be completed at a later date if desired.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="create_account" name="create_account" onchange="togglePasswordFields()">
                                                <label class="form-check-label" for="create_account">
                                                    Create an Account
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="password-fields" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="password">Password</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" id="password" name="password">
                                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', this)" title="Show/Hide Password">
                                                            <i class="fas fa-eye" id="password-icon"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="confirm_password">Confirm Password</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('confirm_password', this)" title="Show/Hide Password">
                                                            <i class="fas fa-eye" id="confirm_password-icon"></i>
                                                        </button>
                                                    </div>
                                                    <small class="text-danger" id="password-match-error" style="display: none;">Passwords do not match</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Additional Notes -->
                                    <div class="form-group mb-4">
                                        <label for="notes">Additional Notes</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any additional information about your organization..."></textarea>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="fas fa-paper-plane"></i>
                                            Submit Registration
                                        </button>
                                    </div>
                                </form>
                            </div>
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
    <script>
        function selectAccountType(type) {
            // Remove selected class from all cards
            document.querySelectorAll('.account-type-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selected class to clicked card
            document.getElementById(type + '-card').classList.add('selected');
            
            // Show/hide forms
            document.getElementById('individual-form').style.display = type === 'individual' ? 'block' : 'none';
            document.getElementById('organization-form').style.display = type === 'organization' ? 'block' : 'none';
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.giving-type-checkbox:checked').forEach(checkbox => {
                const amountInput = checkbox.closest('.giving-type-item').querySelector('.amount-input');
                if (amountInput && amountInput.value) {
                    total += parseFloat(amountInput.value) || 0;
                }
            });
            
            document.getElementById('total-amount-display').textContent = '$' + total.toFixed(2);
            document.getElementById('total_amount').value = total;
        }

        function togglePasswordFields() {
            const checkbox = document.getElementById('create_account');
            const passwordFields = document.getElementById('password-fields');
            
            if (checkbox.checked) {
                passwordFields.style.display = 'block';
                document.getElementById('password').required = true;
                document.getElementById('confirm_password').required = true;
            } else {
                passwordFields.style.display = 'none';
                document.getElementById('password').required = false;
                document.getElementById('confirm_password').required = false;
                document.getElementById('password').value = '';
                document.getElementById('confirm_password').value = '';
                document.getElementById('password-match-error').style.display = 'none';
            }
        }

        function togglePassword(fieldId, button) {
            const field = document.getElementById(fieldId);
            const icon = button.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
            
            // Check password match when toggling
            checkPasswordMatch();
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            const errorMsg = document.getElementById('password-match-error');
            
            if (password && confirmPassword && password.value && confirmPassword.value) {
                if (password.value !== confirmPassword.value) {
                    errorMsg.style.display = 'block';
                } else {
                    errorMsg.style.display = 'none';
                }
            } else {
                errorMsg.style.display = 'none';
            }
        }

        // Add event listeners for real-time password matching
        document.addEventListener('DOMContentLoaded', function() {
            const passwordField = document.getElementById('password');
            const confirmPasswordField = document.getElementById('confirm_password');
            
            if (passwordField) {
                passwordField.addEventListener('input', checkPasswordMatch);
            }
            if (confirmPasswordField) {
                confirmPasswordField.addEventListener('input', checkPasswordMatch);
            }
        });

        // Initialize form validation
        document.addEventListener('DOMContentLoaded', function() {
            // Add form validation
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Check password match before submitting
                    const createAccountCheckbox = form.querySelector('#create_account');
                    if (createAccountCheckbox && createAccountCheckbox.checked) {
                        const password = document.getElementById('password').value;
                        const confirmPassword = document.getElementById('confirm_password').value;
                        
                        if (password !== confirmPassword) {
                            e.preventDefault();
                            e.stopPropagation();
                            document.getElementById('password-match-error').style.display = 'block';
                            document.getElementById('password-match-error').textContent = 'The password and confirm password fields must match.';
                            document.getElementById('confirm_password').focus();
                            return false;
                        }
                    }
                    
                    if (!form.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    form.classList.add('was-validated');
                });
            });
        });
    </script>
</body>
</html>
