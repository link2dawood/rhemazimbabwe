<div class="container-fluid ptb-60">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center mb-40">
                    <h1 class="page-title">Partner Registration</h1>
                    <p class="lead">
                        <?php if ($account_type == 'individual'): ?>
                            <i class="fa fa-user"></i> Individual Partnership
                        <?php else: ?>
                            <i class="fa fa-building"></i> Organization Partnership
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <!-- Progress Steps -->
                <div class="registration-steps">
                    <div class="step active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-title">Contact Info</div>
                    </div>
                    <div class="step" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-title">Address</div>
                    </div>
                    <div class="step" data-step="3">
                        <div class="step-number">3</div>
                        <div class="step-title">Giving Details</div>
                    </div>
                    <div class="step" data-step="4">
                        <div class="step-number">4</div>
                        <div class="step-title">Account (Optional)</div>
                    </div>
                </div>

                <!-- Registration Form -->
                <div class="registration-form-container">
                    <form id="registrationForm" method="post">
                        <input type="hidden" name="account_type" value="<?php echo $account_type; ?>">
                        <input type="hidden" name="student_id" id="student_id" value="">

                        <!-- Step 1: Contact Information -->
                        <div class="form-step active" id="step1">
                            <h3><i class="fa fa-user"></i> Contact Information</h3>
                            <hr>

                            <?php if ($account_type == 'organization'): ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Organization Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="organization_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Organization Type <span class="text-danger">*</span></label>
                                        <select class="form-control" name="organization_type" required>
                                            <option value="">Select Type</option>
                                            <option value="ministry">Ministry</option>
                                            <option value="church">Church</option>
                                            <option value="business">Business</option>
                                            <option value="company">Company</option>
                                            <option value="foundation">Foundation</option>
                                            <option value="ngo">NGO</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="firstname" id="firstname" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="lastname" id="lastname" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" id="email" required>
                                        <small class="text-muted">We'll check if you're an existing student or staff</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="mobileno" id="mobileno" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Existing User Detection Alert -->
                            <div id="existingUserAlert" class="alert" style="display: none;">
                                <button type="button" class="close" onclick="$('#existingUserAlert').hide();">&times;</button>
                                <h4 id="alertTitle"></h4>
                                <p id="alertMessage"></p>
                                <div id="alertActions"></div>
                            </div>

                            <div class="form-navigation">
                                <button type="button" class="btn btn-primary btn-next" data-next="2">
                                    Next <i class="fa fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Address Information -->
                        <div class="form-step" id="step2">
                            <h3><i class="fa fa-map-marker"></i> Address Information</h3>
                            <hr>

                            <div class="form-group">
                                <label>Address</label>
                                <textarea class="form-control" name="address" rows="3"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input type="text" class="form-control" name="city">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>State/Province</label>
                                        <input type="text" class="form-control" name="state">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Country</label>
                                        <select class="form-control" name="country">
                                            <option value="">Select Country</option>
                                            <option value="Zimbabwe" selected>Zimbabwe</option>
                                            <option value="South Africa">South Africa</option>
                                            <option value="Zambia">Zambia</option>
                                            <option value="Botswana">Botswana</option>
                                            <option value="United States">United States</option>
                                            <option value="United Kingdom">United Kingdom</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Zip/Postal Code</label>
                                        <input type="text" class="form-control" name="zipcode">
                                    </div>
                                </div>
                            </div>

                            <div class="form-navigation">
                                <button type="button" class="btn btn-default btn-prev" data-prev="1">
                                    <i class="fa fa-arrow-left"></i> Previous
                                </button>
                                <button type="button" class="btn btn-primary btn-next" data-next="3">
                                    Next <i class="fa fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Giving Details -->
                        <div class="form-step" id="step3">
                            <h3><i class="fa fa-heart"></i> Giving Details</h3>
                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Giving Frequency</label>
                                        <select class="form-control" name="giving_frequency_id">
                                            <option value="">Select Frequency</option>
                                            <?php foreach ($giving_frequencies as $freq): ?>
                                                <option value="<?php echo $freq->id; ?>"><?php echo $freq->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="text-muted">How often do you plan to contribute?</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Currency</label>
                                        <select class="form-control" name="currency" id="currency">
                                            <option value="USD" selected>USD ($)</option>
                                            <option value="ZWL">ZWL (Z$)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Select Type(s) of Contribution <span class="text-muted">(You can select multiple)</span></label>
                                <div class="giving-types-container">
                                    <?php foreach ($giving_types as $type): ?>
                                    <div class="giving-type-item">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="giving-type-checkbox" name="giving_types[]" value="<?php echo $type->id; ?>" data-type-id="<?php echo $type->id; ?>">
                                                        <strong><?php echo $type->name; ?></strong>
                                                        <?php if (!empty($type->description)): ?>
                                                            <br><small class="text-muted"><?php echo $type->description; ?></small>
                                                        <?php endif; ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon currency-symbol">$</span>
                                                    <input type="number" class="form-control giving-amount" name="giving_amounts[<?php echo $type->id; ?>]" step="0.01" min="0" placeholder="0.00" disabled data-amount-for="<?php echo $type->id; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="total-contribution-display">
                                <div class="alert alert-info">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4 style="margin:0;"><i class="fa fa-calculator"></i> Total Contributions:</h4>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <h3 style="margin:0;"><strong><span id="currency-symbol-display">$</span> <span id="totalContributions">0.00</span></strong></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Additional Notes</label>
                                <textarea class="form-control" name="notes" rows="3" placeholder="Any special requirements or messages..."></textarea>
                            </div>

                            <div class="form-navigation">
                                <button type="button" class="btn btn-default btn-prev" data-prev="2">
                                    <i class="fa fa-arrow-left"></i> Previous
                                </button>
                                <button type="button" class="btn btn-primary btn-next" data-next="4">
                                    Next <i class="fa fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 4: Account Creation (Optional) -->
                        <div class="form-step" id="step4">
                            <h3><i class="fa fa-lock"></i> Account Creation (Optional)</h3>
                            <hr>

                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="create_account" id="create_account" value="1">
                                        <strong>Create an Account (optional)</strong>
                                    </label>
                                </div>
                                <p class="help-block">
                                    Adding a password will create an account and allow you to access your transaction history
                                    as well as manage account details. This is an optional step and can be completed at a
                                    later date if desired.
                                </p>
                            </div>

                            <!-- Password Fields (Hidden by default) -->
                            <div id="passwordFields" style="display: none;">
                                <div class="alert alert-warning">
                                    <i class="fa fa-key"></i> <strong>Set Your Password</strong><br>
                                    Choose a strong password to secure your account.
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Password <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="password" id="password" minlength="6">
                                                <span class="input-group-addon" id="togglePassword" style="cursor:pointer;">
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                            </div>
                                            <small class="text-muted">Minimum 6 characters</small>
                                            <div class="password-strength" id="passwordStrength"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Confirm Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="password_confirm" id="password_confirm">
                                            <div id="passwordMatch" class="help-block"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="agree_terms" id="agree_terms" required>
                                        I agree to the <a href="#" target="_blank">Terms and Conditions</a> <span class="text-danger">*</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="subscribe_newsletter" id="subscribe_newsletter" value="1">
                                        Subscribe to our newsletter
                                    </label>
                                </div>
                            </div>

                            <div class="form-navigation">
                                <button type="button" class="btn btn-default btn-prev" data-prev="3">
                                    <i class="fa fa-arrow-left"></i> Previous
                                </button>
                                <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                    <i class="fa fa-check"></i> Complete Registration
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm" style="margin-top: 200px;">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="fa fa-spinner fa-spin fa-3x text-primary"></i>
                <h4 class="mt-20">Processing...</h4>
                <p>Please wait while we process your registration.</p>
            </div>
        </div>
    </div>
</div>

<style>
.ptb-60 { padding: 60px 0; }
.mb-40 { margin-bottom: 40px; }
.mt-20 { margin-top: 20px; }

.registration-steps {
    display: flex;
    justify-content: space-between;
    margin-bottom: 50px;
    position: relative;
}

.registration-steps::before {
    content: '';
    position: absolute;
    top: 30px;
    left: 0;
    right: 0;
    height: 2px;
    background: #ddd;
    z-index: -1;
}

.step {
    text-align: center;
    position: relative;
    flex: 1;
}

.step-number {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #fff;
    border: 3px solid #ddd;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: bold;
    color: #999;
    margin-bottom: 10px;
    transition: all 0.3s ease;
}

.step.active .step-number {
    background: #3c8dbc;
    border-color: #3c8dbc;
    color: #fff;
}

.step.completed .step-number {
    background: #00a65a;
    border-color: #00a65a;
    color: #fff;
}

.step-title {
    font-weight: 600;
    color: #999;
}

.step.active .step-title {
    color: #3c8dbc;
}

.registration-form-container {
    background: #fff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.form-step {
    display: none;
}

.form-step.active {
    display: block;
}

.form-navigation {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
    text-align: right;
}

.form-navigation .btn {
    margin-left: 10px;
}

.form-group label {
    font-weight: 600;
    color: #333;
}

.alert h4 {
    margin-top: 0;
}

/* Giving Types Styles */
.giving-types-container {
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 20px;
    background: #f9f9f9;
    max-height: 400px;
    overflow-y: auto;
}

.giving-type-item {
    background: white;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 5px;
    border-left: 3px solid #3c8dbc;
    transition: all 0.3s ease;
}

.giving-type-item:hover {
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.giving-type-item.selected {
    border-left-color: #00a65a;
    background: #f0f9f0;
}

.total-contribution-display {
    margin-top: 20px;
}

.total-contribution-display .alert {
    margin-bottom: 0;
}

/* Password Strength Indicator */
.password-strength {
    margin-top: 5px;
    height: 5px;
    border-radius: 3px;
    transition: all 0.3s ease;
}

.password-strength.weak {
    background: #d9534f;
    width: 33%;
}

.password-strength.medium {
    background: #f0ad4e;
    width: 66%;
}

.password-strength.strong {
    background: #5cb85c;
    width: 100%;
}

#passwordMatch {
    margin-top: 5px;
    font-weight: 600;
}

#passwordMatch.match {
    color: #5cb85c;
}

#passwordMatch.no-match {
    color: #d9534f;
}
</style>

<script>
var base_url = '<?php echo base_url(); ?>';

$(document).ready(function() {
    // Giving Types - Enable/Disable amount input based on checkbox
    $('.giving-type-checkbox').change(function() {
        var typeId = $(this).data('type-id');
        var amountInput = $('input[data-amount-for="' + typeId + '"]');
        var parentItem = $(this).closest('.giving-type-item');

        if ($(this).is(':checked')) {
            amountInput.prop('disabled', false).focus();
            parentItem.addClass('selected');
        } else {
            amountInput.prop('disabled', true).val('');
            parentItem.removeClass('selected');
        }

        calculateTotal();
    });

    // Calculate total when amount changes
    $('.giving-amount').on('input', function() {
        calculateTotal();
    });

    // Currency change - update symbols
    $('#currency').change(function() {
        var symbol = $(this).val() == 'USD' ? '$' : 'Z$';
        $('.currency-symbol').text(symbol);
        $('#currency-symbol-display').text(symbol);
    });

    // Password Toggle Show/Hide
    $('#togglePassword').click(function() {
        var passwordInput = $('#password');
        var icon = $(this).find('i');

        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Password strength check
    $('#password').on('input', function() {
        checkPasswordStrength($(this).val());
    });

    // Password match check
    $('#password_confirm').on('input', function() {
        checkPasswordMatch();
    });

    // Create Account checkbox - show/hide password fields
    $('#create_account').change(function() {
        if ($(this).is(':checked')) {
            $('#passwordFields').slideDown();
            $('#password, #password_confirm').attr('required', true);
        } else {
            $('#passwordFields').slideUp();
            $('#password, #password_confirm').attr('required', false).val('');
            $('#passwordStrength').removeClass('weak medium strong');
            $('#passwordMatch').text('').removeClass('match no-match');
        }
    });

    // Multi-step form navigation
    $('.btn-next').click(function() {
        var nextStep = $(this).data('next');
        var currentStep = $(this).closest('.form-step').attr('id').replace('step', '');

        if (validateStep(currentStep)) {
            showStep(nextStep);
        }
    });

    $('.btn-prev').click(function() {
        var prevStep = $(this).data('prev');
        showStep(prevStep);
    });

    // Email/Phone blur - check existing user
    $('#email, #mobileno').blur(function() {
        checkExistingUser();
    });

    // Form submission
    $('#registrationForm').submit(function(e) {
        e.preventDefault();

        if (!$('#agree_terms').is(':checked')) {
            alert('Please agree to the Terms and Conditions');
            return false;
        }

        $('#loadingModal').modal('show');
        $('#submitBtn').prop('disabled', true);

        $.ajax({
            url: base_url + 'partnerregistration/submit',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                $('#loadingModal').modal('hide');

                if (response.status == 'success') {
                    alert(response.message);
                    window.location.href = response.redirect;
                } else {
                    alert(response.message);
                    $('#submitBtn').prop('disabled', false);
                }
            },
            error: function() {
                $('#loadingModal').modal('hide');
                alert('An error occurred. Please try again.');
                $('#submitBtn').prop('disabled', false);
            }
        });
    });
});

function showStep(stepNumber) {
    $('.form-step').removeClass('active');
    $('#step' + stepNumber).addClass('active');

    $('.step').removeClass('active completed');
    $('.step[data-step=' + stepNumber + ']').addClass('active');
    $('.step[data-step]').each(function() {
        if ($(this).data('step') < stepNumber) {
            $(this).addClass('completed');
        }
    });

    $('html, body').animate({ scrollTop: 0 }, 300);
}

function validateStep(step) {
    var isValid = true;
    var $step = $('#step' + step);

    $step.find('input[required], select[required], textarea[required]').each(function() {
        if (!$(this).val()) {
            $(this).addClass('error');
            isValid = false;
        } else {
            $(this).removeClass('error');
        }
    });

    if (!isValid) {
        alert('Please fill in all required fields');
    }

    return isValid;
}

function checkExistingUser() {
    var email = $('#email').val();
    var phone = $('#mobileno').val();

    if (!email && !phone) return;

    $.ajax({
        url: base_url + 'partnerregistration/checkExisting',
        type: 'POST',
        data: { email: email, phone: phone },
        dataType: 'json',
        success: function(response) {
            if (response.exists) {
                var alertClass = response.type == 'student' ? 'alert-info' : 'alert-warning';
                var icon = response.type == 'student' ? 'fa-graduation-cap' : 'fa-user';

                $('#existingUserAlert').removeClass().addClass('alert ' + alertClass).show();
                $('#alertTitle').html('<i class="fa ' + icon + '"></i> Existing ' + response.type.charAt(0).toUpperCase() + response.type.slice(1) + ' Detected');

                if (response.type == 'student') {
                    $('#alertMessage').text('We found that you are enrolled as: ' + response.name + ' (Admission No: ' + response.admission_no + ')');
                    $('#alertActions').html('<button type="button" class="btn btn-sm btn-primary" onclick="linkStudent(' + response.student_id + ')">Link to My Student Account</button>');
                    $('#student_id').val(response.student_id);
                } else {
                    $('#alertMessage').text('We found that you are a staff member: ' + response.name);
                    $('#alertActions').html('<p class="text-muted">Your staff information will be linked to this partnership.</p>');
                }
            } else {
                $('#existingUserAlert').hide();
                $('#student_id').val('');
            }
        }
    });
}

function linkStudent(student_id) {
    $('#student_id').val(student_id);
    $('#existingUserAlert').removeClass().addClass('alert alert-success').show();
    $('#alertTitle').html('<i class="fa fa-check"></i> Student Account Linked');
    $('#alertMessage').text('Your partnership will be linked to your student account.');
    $('#alertActions').html('');
}

// Calculate total contributions
function calculateTotal() {
    var total = 0;
    $('.giving-type-checkbox:checked').each(function() {
        var typeId = $(this).data('type-id');
        var amount = parseFloat($('input[data-amount-for="' + typeId + '"]').val()) || 0;
        total += amount;
    });

    $('#totalContributions').text(total.toFixed(2));
}

// Check password strength
function checkPasswordStrength(password) {
    var strength = 0;
    var $indicator = $('#passwordStrength');

    if (password.length === 0) {
        $indicator.removeClass('weak medium strong');
        return;
    }

    if (password.length >= 6) strength++;
    if (password.length >= 10) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;

    $indicator.removeClass('weak medium strong');

    if (strength <= 2) {
        $indicator.addClass('weak');
    } else if (strength <= 4) {
        $indicator.addClass('medium');
    } else {
        $indicator.addClass('strong');
    }
}

// Check if passwords match
function checkPasswordMatch() {
    var password = $('#password').val();
    var confirm = $('#password_confirm').val();
    var $match = $('#passwordMatch');

    if (confirm.length === 0) {
        $match.text('').removeClass('match no-match');
        return;
    }

    if (password === confirm) {
        $match.text('✓ Passwords match').removeClass('no-match').addClass('match');
    } else {
        $match.text('✗ Passwords do not match').removeClass('match').addClass('no-match');
    }
}
</script>