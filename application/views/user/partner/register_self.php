<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-handshake-o"></i> <?php echo $this->lang->line('become_a_partner'); ?>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="fa fa-check"></i> <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="fa fa-times"></i> <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <?php if ($existing_partner): ?>
                    <!-- User is already a partner -->
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-check-circle"></i> You are Already a Partner!</h3>
                        </div>
                        <div class="box-body">
                            <div class="alert alert-success">
                                <h4><i class="fa fa-info-circle"></i> Partner Account Active</h4>
                                <p>You are already registered as a partner. Here are your details:</p>
                            </div>

                            <?php 
                            // Handle both array and object
                            $partner = is_array($existing_partner) ? (object)$existing_partner : $existing_partner;
                            ?>
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th width="30%">Partner Code:</th>
                                    <td><strong class="text-primary"><?php echo isset($partner->partner_code) ? $partner->partner_code : 'N/A'; ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td><?php echo (isset($partner->firstname) ? $partner->firstname : '') . ' ' . (isset($partner->lastname) ? $partner->lastname : ''); ?></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?php echo isset($partner->email) ? $partner->email : 'N/A'; ?></td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td><?php echo isset($partner->mobileno) ? $partner->mobileno : 'N/A'; ?></td>
                                </tr>
                                <tr>
                                    <th>Giving Type:</th>
                                    <td><?php echo isset($partner->type_name) && $partner->type_name ? $partner->type_name : 'Not Set'; ?></td>
                                </tr>
                                <tr>
                                    <th>Giving Frequency:</th>
                                    <td><?php echo isset($partner->frequency_name) && $partner->frequency_name ? $partner->frequency_name : 'Not Set'; ?></td>
                                </tr>
                                <tr>
                                    <th>Contribution Amount:</th>
                                    <td><?php echo (isset($partner->currency) ? $partner->currency : 'USD') . ' ' . number_format(isset($partner->contribution_amount) ? $partner->contribution_amount : 0, 2); ?></td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="label label-<?php echo isset($partner->status) && $partner->status == 'active' ? 'success' : 'warning'; ?>">
                                            <?php echo isset($partner->status) ? ucfirst($partner->status) : 'Inactive'; ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <div class="text-center mt-20">
                                <a href="<?php echo base_url('user/partner_management'); ?>" class="btn btn-primary btn-lg">
                                    <i class="fa fa-cog"></i> Manage My Partnership
                                </a>
                                <a href="<?php echo base_url('user/partner_reports'); ?>" class="btn btn-info btn-lg">
                                    <i class="fa fa-file-text"></i> View My Reports
                                </a>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <!-- User is NOT a partner - Show Registration Form -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-user-plus"></i> Register as a Partner</h3>
                        </div>
                        <div class="box-body">
                            <div class="alert alert-info">
                                <h4><i class="fa fa-info-circle"></i> Welcome!</h4>
                                <p>
                                    Thank you for your interest in becoming a partner. By registering as a partner, you can:
                                </p>
                                <ul>
                                    <li>Support students and the school's mission</li>
                                    <li>Track your contributions online</li>
                                    <li>Receive automatic receipts</li>
                                    <li>Access exclusive partner benefits</li>
                                    <li>View detailed contribution reports</li>
                                </ul>
                            </div>

                            <form id="partnerRegistrationForm" method="post" action="<?php echo base_url('user/partner/process_self_registration'); ?>" class="form-horizontal">
                                <input type="hidden" name="user_type" value="<?php echo $user_type; ?>">
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                
                                <h4 class="text-primary"><i class="fa fa-user"></i> Personal Information</h4>
                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="firstname" value="<?php echo $prefill_data['firstname'] ?? ''; ?>" required readonly style="background-color: #f5f5f5;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="lastname" value="<?php echo $prefill_data['lastname'] ?? ''; ?>" required readonly style="background-color: #f5f5f5;">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" value="<?php echo $prefill_data['email'] ?? ''; ?>" required readonly style="background-color: #f5f5f5;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phone <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="mobileno" value="<?php echo $prefill_data['mobileno'] ?? ''; ?>" required readonly style="background-color: #f5f5f5;">
                                        </div>
                                    </div>
                                </div>

                                <h4 class="text-primary mt-30"><i class="fa fa-map-marker"></i> Address Information</h4>
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

                                <h4 class="text-primary mt-30"><i class="fa fa-heart"></i> Giving Preferences</h4>
                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Giving Frequency <span class="text-danger">*</span></label>
                                            <select class="form-control" name="giving_frequency_id" required>
                                                <option value="">Select Frequency</option>
                                                <?php foreach ($giving_frequencies as $freq): ?>
                                                    <option value="<?php echo $freq->id; ?>"><?php echo $freq->name; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Currency</label>
                                            <select class="form-control" name="currency">
                                                <option value="USD" selected>USD ($)</option>
                                                <option value="ZWL">ZWL (Z$)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Select Type(s) of Contribution <span class="text-muted">(You can select multiple or enter amounts directly)</span></label>
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
                                                        <input type="number" class="form-control giving-amount" name="giving_amounts[<?php echo $type->id; ?>]" step="0.01" min="0" placeholder="0.00" data-amount-for="<?php echo $type->id; ?>">
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

                                <h4 class="text-primary mt-30"><i class="fa fa-lock"></i> Partner Account Password</h4>
                                <hr>

                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i> Set a password for your partner account. This will allow you to login to the partner portal separately from your main account.
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="password" id="password" required minlength="6" placeholder="Enter password (minimum 6 characters)">
                                            <small class="help-block">Password must be at least 6 characters long</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Confirm Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="password_confirm" id="password_confirm" required minlength="6" placeholder="Confirm your password">
                                            <small class="help-block">Re-enter your password to confirm</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-30">
                                    <div class="checkbox" style="border: 2px solid #f39c12; border-radius: 5px; padding: 15px; background: #fff9f0;">
                                        <label style="font-size: 16px; font-weight: 600; display: flex; align-items: center; margin-bottom: 0; cursor: pointer;">
                                            <input type="checkbox" name="agree_terms" id="agree_terms" required style="width: 20px; height: 20px; margin-right: 15px; cursor: pointer; flex-shrink: 0; appearance: auto; -webkit-appearance: checkbox; -moz-appearance: checkbox;">
                                            <span>I agree to the <a href="#" target="_blank" style="color: #3c8dbc; text-decoration: underline;">Terms and Conditions</a> <span class="text-danger">*</span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="box-footer">
                                    <button type="submit" class="btn btn-success btn-lg pull-right">
                                        <i class="fa fa-check"></i> Complete Registration
                                    </button>
                                    <a href="<?php echo base_url('user/user/dashboard'); ?>" class="btn btn-default btn-lg">
                                        <i class="fa fa-arrow-left"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<style>
/* CRITICAL: Force checkboxes visible */
input[type="checkbox"] {
    appearance: auto !important;
    -webkit-appearance: checkbox !important;
    -moz-appearance: checkbox !important;
    width: 20px !important;
    height: 20px !important;
    display: inline-block !important;
    opacity: 1 !important;
    visibility: visible !important;
    position: relative !important;
}

.mt-30 {
    margin-top: 30px;
}

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
</style>

<script>
$(document).ready(function() {
    // FORCE checkboxes to be visible
    $('input[type="checkbox"]').css({
        'appearance': 'auto',
        '-webkit-appearance': 'checkbox',
        '-moz-appearance': 'checkbox',
        'width': '20px',
        'height': '20px',
        'display': 'inline-block',
        'opacity': '1',
        'visibility': 'visible',
        'position': 'relative',
        'margin-right': '15px'
    });

    // Auto-check checkbox when user enters amount
    $('.giving-amount').on('input', function() {
        var typeId = $(this).data('amount-for');
        var checkbox = $('input[data-type-id="' + typeId + '"]');
        var parentItem = $(this).closest('.giving-type-item');
        
        if ($(this).val() && parseFloat($(this).val()) > 0) {
            checkbox.prop('checked', true);
            parentItem.addClass('selected');
        } else {
            checkbox.prop('checked', false);
            parentItem.removeClass('selected');
        }
        
        calculateTotal();
    });

    // Checkbox change - handle amount input
    $('.giving-type-checkbox').change(function() {
        var typeId = $(this).data('type-id');
        var amountInput = $('input[data-amount-for="' + typeId + '"]');
        var parentItem = $(this).closest('.giving-type-item');

        if ($(this).is(':checked')) {
            parentItem.addClass('selected');
        } else {
            amountInput.val('');
            parentItem.removeClass('selected');
        }

        calculateTotal();
    });

    // Calculate total
    function calculateTotal() {
        var total = 0;
        $('.giving-type-checkbox:checked').each(function() {
            var typeId = $(this).data('type-id');
            var amount = parseFloat($('input[data-amount-for="' + typeId + '"]').val()) || 0;
            total += amount;
        });

        $('#totalContributions').text(total.toFixed(2));
    }

    // Currency change
    $('select[name="currency"]').change(function() {
        var symbol = $(this).val() == 'USD' ? '$' : 'Z$';
        $('.currency-symbol').text(symbol);
        $('#currency-symbol-display').text(symbol);
    });

    // Password confirmation validation
    $('#password_confirm').on('keyup', function() {
        var password = $('#password').val();
        var confirmPassword = $(this).val();
        
        if (password !== confirmPassword) {
            $(this).closest('.form-group').addClass('has-error');
            $(this).closest('.form-group').find('.help-block').text('Passwords do not match').css('color', '#a94442');
        } else {
            $(this).closest('.form-group').removeClass('has-error');
            $(this).closest('.form-group').find('.help-block').text('Re-enter your password to confirm').css('color', '#737373');
        }
    });

    // Form validation
    $('#partnerRegistrationForm').submit(function(e) {
        if (!$('#agree_terms').is(':checked')) {
            e.preventDefault();
            alert('Please agree to the Terms and Conditions');
            return false;
        }

        // Check password match
        var password = $('#password').val();
        var confirmPassword = $('#password_confirm').val();
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match. Please check your password confirmation.');
            $('#password_confirm').focus();
            return false;
        }

        // Check if at least one giving type is selected
        var hasGivingType = false;
        $('.giving-type-checkbox:checked').each(function() {
            hasGivingType = true;
        });

        if (!hasGivingType) {
            e.preventDefault();
            alert('Please select at least one type of contribution');
            return false;
        }
    });
});
</script>

