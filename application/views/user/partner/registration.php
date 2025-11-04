<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
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
        .user-info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- User Info Card -->
                <div class="user-info-card">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4>
                                <i class="fas fa-user-circle"></i>
                                Welcome, <?php echo ucfirst($user_type); ?>!
                            </h4>
                            <p class="mb-0">Register as a partner to support our school and track your contributions.</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <i class="fas fa-graduation-cap fa-3x opacity-75"></i>
                        </div>
                    </div>
                </div>

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

                        <!-- Registration Form -->
                        <div id="registration-form">
                            <form action="<?php echo base_url('user/partner_registration/process_' . $user_type); ?>" method="post" id="partner-registration-form">
                                <input type="hidden" name="user_type" value="<?php echo $user_type; ?>">
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                
                                <!-- Individual Form -->
                                <div id="individual-form" style="display: none;">
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
                                </div>

                                <!-- Organization Form -->
                                <div id="organization-form" style="display: none;">
                                    <h4 class="mb-4">
                                        <i class="fas fa-building text-success"></i>
                                        Organization Registration
                                    </h4>
                                    
                                    <!-- Organization Information -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="organization_name">Organization Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="organization_name" name="organization_name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="organization_type">Organization Type <span class="text-danger">*</span></label>
                                                <select class="form-control" id="organization_type" name="organization_type">
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
                                                <input type="text" class="form-control" id="firstname" name="firstname">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="lastname">Contact Last Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="lastname" name="lastname">
                                            </div>
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
                                                <option value="">Select Country</option>
                                                <option value="Zimbabwe" selected>Zimbabwe</option>
                                                <optgroup label="African Countries">
                                                    <option value="South Africa">South Africa</option>
                                                    <option value="Botswana">Botswana</option>
                                                    <option value="Zambia">Zambia</option>
                                                    <option value="Mozambique">Mozambique</option>
                                                    <option value="Malawi">Malawi</option>
                                                    <option value="Namibia">Namibia</option>
                                                    <option value="Tanzania">Tanzania</option>
                                                    <option value="Kenya">Kenya</option>
                                                    <option value="Uganda">Uganda</option>
                                                    <option value="Rwanda">Rwanda</option>
                                                    <option value="Nigeria">Nigeria</option>
                                                    <option value="Ghana">Ghana</option>
                                                    <option value="Ethiopia">Ethiopia</option>
                                                    <option value="Egypt">Egypt</option>
                                                    <option value="Angola">Angola</option>
                                                    <option value="Democratic Republic of Congo">Democratic Republic of Congo</option>
                                                </optgroup>
                                                <optgroup label="International">
                                                    <option value="United Kingdom">United Kingdom</option>
                                                    <option value="United States">United States</option>
                                                    <option value="Canada">Canada</option>
                                                    <option value="Australia">Australia</option>
                                                    <option value="New Zealand">New Zealand</option>
                                                    <option value="Germany">Germany</option>
                                                    <option value="France">France</option>
                                                    <option value="Netherlands">Netherlands</option>
                                                    <option value="Switzerland">Switzerland</option>
                                                    <option value="Sweden">Sweden</option>
                                                    <option value="Norway">Norway</option>
                                                    <option value="Denmark">Denmark</option>
                                                    <option value="China">China</option>
                                                    <option value="India">India</option>
                                                    <option value="Brazil">Brazil</option>
                                                    <option value="Other">Other</option>
                                                </optgroup>
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
                                            <div id="giving-types">
                                                <?php foreach ($giving_types as $type): ?>
                                                <div class="giving-type-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input giving-type-checkbox" type="checkbox" 
                                                               name="giving_types[]" value="<?php echo $type->id; ?>" 
                                                               id="type_<?php echo $type->id; ?>"
                                                               onchange="updateTotal()">
                                                        <label class="form-check-label" for="type_<?php echo $type->id; ?>">
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
                                    <a href="<?php echo base_url('user/partner'); ?>" class="btn btn-outline-secondary btn-lg ms-3">
                                        <i class="fas fa-arrow-left"></i>
                                        Back to Partner Portal
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
            
            // Update required fields
            updateRequiredFields(type);
        }

        function updateRequiredFields(type) {
            const individualFields = ['firstname', 'lastname'];
            const organizationFields = ['organization_name', 'organization_type', 'firstname', 'lastname'];
            
            // Reset all fields
            [...individualFields, ...organizationFields].forEach(field => {
                const element = document.getElementById(field);
                if (element) {
                    element.required = false;
                }
            });
            
            // Set required fields based on type
            const requiredFields = type === 'individual' ? individualFields : organizationFields;
            requiredFields.forEach(field => {
                const element = document.getElementById(field);
                if (element) {
                    element.required = true;
                }
            });
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

        // Initialize form validation
        document.addEventListener('DOMContentLoaded', function() {
            // Add form validation
            const form = document.getElementById('partner-registration-form');
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });
    </script>
</body>
</html>
