<style>
    .registersection{
        margin-top: 80px;
    }
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
    cursor: pointer;
}

.account-type-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 5px 30px rgba(0,0,0,0.15);
}

.account-type-card.selected {
    border: 3px solid #3c8dbc;
    background: #f8f9ff;
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

.giving-type-item {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
    transition: all 0.3s ease;
}

.giving-type-item:hover {
    border-color: #3c8dbc;
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

.registration-form {
    display: none;
}

.registration-form.show {
    display: block;
}
</style>

<div class="row registersection ptb-60">
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
        <div class="account-type-card" onclick="selectAccountType('individual')" id="individual-card">
            <div class="card-icon">
                <i class="fa fa-user fa-5x"></i>
            </div>
            <h2>Individual Partner</h2>
            <p class="description">
                Partner with us as an individual to support our mission and students.
            </p>
            <ul class="features">
                <li><i class="fa fa-check text-success"></i> Flexible contribution options</li>
                <li><i class="fa fa-check text-success"></i> Personal giving preferences</li>
                <li><i class="fa fa-check text-success"></i> Regular updates and reports</li>
                <li><i class="fa fa-check text-success"></i> Access to partner resources</li>
                <li><i class="fa fa-check text-success"></i> Tax receipt for contributions</li>
            </ul>
        </div>
    </div>

    <!-- Organization Partnership -->
    <div class="col-md-6">
        <div class="account-type-card" onclick="selectAccountType('organization')" id="organization-card">
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
        </div>
    </div>
</div>

<!-- Registration Forms -->
<div class="row mt-40">
    <div class="col-md-12">
        <!-- Individual Form -->
        <div id="individual-form" class="registration-form">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-user"></i> Individual Partner Registration
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="<?php echo base_url('partner_registration/process_individual'); ?>" method="post" id="individual-registration-form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstname">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lastname">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mobileno">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="mobileno" name="mobileno" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">Billing Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city">City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="city" name="city" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="state">State/Province</label>
                                    <input type="text" class="form-control" id="state" name="state">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
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

                        <h4>Giving Preferences</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Select Giving Types <span class="text-danger">*</span></label>
                                    <div id="giving-types-individual">
                                        <?php foreach ($giving_types as $type): ?>
                                        <div class="giving-type-item">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="giving_types[]" value="<?php echo $type->id; ?>" 
                                                           onchange="updateTotal()">
                                                    <strong><?php echo $type->name; ?></strong>
                                                    <br><small class="text-muted"><?php echo $type->description; ?></small>
                                                </label>
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
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
                                <div class="form-group">
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

                                <div class="form-group">
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

                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="create_account" name="create_account" onchange="togglePasswordFields()">
                                    Create an Account (Optional)
                                </label>
                            </div>
                            <small class="text-muted">
                                Adding a password will create an account and allow you to access your transaction history as well as manage account details.
                            </small>
                        </div>

                        <div id="password-fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password">
                                            <button type="button" class="btn btn-default" onclick="togglePassword('password', this)" title="Show/Hide Password">
                                                <i class="fa fa-eye" id="password-icon"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="confirm_password">Confirm Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                            <button type="button" class="btn btn-default" onclick="togglePassword('confirm_password', this)" title="Show/Hide Password">
                                                <i class="fa fa-eye" id="confirm_password-icon"></i>
                                            </button>
                                        </div>
                                        <small class="text-danger" id="password-match-error" style="display: none;">Passwords do not match</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes">Additional Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any additional information you'd like to share..."></textarea>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-paper-plane"></i> Submit Registration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Organization Form -->
        <div id="organization-form" class="registration-form">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-building"></i> Organization Partner Registration
                    </h3>
                </div>
                <div class="panel-body">
                    <form action="<?php echo base_url('partner_registration/process_organization'); ?>" method="post" id="organization-registration-form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="organization_name">Organization Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="organization_name" name="organization_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
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

                        <h4>Contact Person Information</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstname">Contact First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lastname">Contact Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mobileno">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="mobileno" name="mobileno" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">Billing Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city">City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="city" name="city" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="state">State/Province</label>
                                    <input type="text" class="form-control" id="state" name="state">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
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

                        <h4>Giving Preferences</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Select Giving Types <span class="text-danger">*</span></label>
                                    <div id="giving-types-organization">
                                        <?php foreach ($giving_types as $type): ?>
                                        <div class="giving-type-item">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="giving_types[]" value="<?php echo $type->id; ?>" 
                                                           onchange="updateTotal()">
                                                    <strong><?php echo $type->name; ?></strong>
                                                    <br><small class="text-muted"><?php echo $type->description; ?></small>
                                                </label>
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
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
                                <div class="form-group">
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

                                <div class="form-group">
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

                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="create_account" name="create_account" onchange="togglePasswordFields()">
                                    Create an Account (Optional)
                                </label>
                            </div>
                            <small class="text-muted">
                                Adding a password will create an account and allow you to access your transaction history as well as manage account details.
                            </small>
                        </div>

                        <div id="password-fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password">
                                            <button type="button" class="btn btn-default" onclick="togglePassword('password', this)" title="Show/Hide Password">
                                                <i class="fa fa-eye" id="password-icon"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="confirm_password">Confirm Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                            <button type="button" class="btn btn-default" onclick="togglePassword('confirm_password', this)" title="Show/Hide Password">
                                                <i class="fa fa-eye" id="confirm_password-icon"></i>
                                            </button>
                                        </div>
                                        <small class="text-danger" id="password-match-error" style="display: none;">Passwords do not match</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes">Additional Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any additional information about your organization..."></textarea>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fa fa-paper-plane"></i> Submit Registration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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

<script>
function selectAccountType(type) {
    // Remove selected class from all cards
    document.querySelectorAll('.account-type-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Add selected class to clicked card
    document.getElementById(type + '-card').classList.add('selected');
    
    // Show/hide forms
    document.getElementById('individual-form').classList.toggle('show', type === 'individual');
    document.getElementById('organization-form').classList.toggle('show', type === 'organization');
}

function updateTotal() {
    let total = 0;
    document.querySelectorAll('input[name="giving_types[]"]:checked').forEach(checkbox => {
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
    
    // Add form submission validation
    const forms = document.querySelectorAll('#individual-registration-form, #organization-registration-form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const createAccountCheckbox = form.querySelector('#create_account');
            if (createAccountCheckbox && createAccountCheckbox.checked) {
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirm_password').value;
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    document.getElementById('password-match-error').style.display = 'block';
                    document.getElementById('password-match-error').textContent = 'The password and confirm password fields must match.';
                    document.getElementById('confirm_password').focus();
                    return false;
                }
            }
        });
    });
});
</script>
