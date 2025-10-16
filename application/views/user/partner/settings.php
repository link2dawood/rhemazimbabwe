<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-cog"></i> Partner Settings
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('partnerdashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Settings</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <!-- Profile Information -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-user"></i> Profile Information</h3>
                    </div>
                    <form id="profileForm">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="firstname">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="firstname" name="firstname" 
                                               value="<?php echo $partner['firstname']; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lastname">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="lastname" name="lastname" 
                                               value="<?php echo $partner['lastname']; ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo $partner['email']; ?>" readonly>
                                <small class="text-muted">Email cannot be changed. Contact admin if needed.</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="mobileno">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="mobileno" name="mobileno" 
                                       value="<?php echo $partner['mobileno']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3"><?php echo $partner['address']; ?></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" class="form-control" id="city" name="city" 
                                               value="<?php echo $partner['city']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <input type="text" class="form-control" id="state" name="state" 
                                               value="<?php echo $partner['state']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="zip_code">ZIP Code</label>
                                        <input type="text" class="form-control" id="zip_code" name="zip_code" 
                                               value="<?php echo $partner['zip_code']; ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"><?php echo $partner['notes']; ?></textarea>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Giving Settings -->
            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-heart"></i> Giving Settings</h3>
                    </div>
                    <form id="givingSettingsForm">
                        <div class="box-body">
                            <div class="form-group">
                                <label>Giving Types & Amounts <span class="text-danger">*</span></label>
                                <div id="giving-types-container">
                                    <?php if (!empty($current_settings)): ?>
                                        <?php foreach ($current_settings as $index => $setting): ?>
                                            <div class="row giving-type-row" data-index="<?php echo $index; ?>">
                                                <div class="col-md-6">
                                                    <select class="form-control giving-type-select" name="giving_types[]" required>
                                                        <option value="">Select Giving Type</option>
                                                        <?php foreach ($giving_types as $type): ?>
                                                            <option value="<?php echo $type->id; ?>" 
                                                                    <?php echo ($type->id == $setting['giving_type_id']) ? 'selected' : ''; ?>>
                                                                <?php echo $type->name; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="number" class="form-control amount-input" name="amounts[]" 
                                                           placeholder="Amount" value="<?php echo $setting['amount']; ?>" 
                                                           min="0" step="0.01" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-danger btn-sm remove-giving-type">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="row giving-type-row" data-index="0">
                                            <div class="col-md-6">
                                                <select class="form-control giving-type-select" name="giving_types[]" required>
                                                    <option value="">Select Giving Type</option>
                                                    <?php foreach ($giving_types as $type): ?>
                                                        <option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="number" class="form-control amount-input" name="amounts[]" 
                                                       placeholder="Amount" min="0" step="0.01" required>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger btn-sm remove-giving-type">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <button type="button" class="btn btn-success btn-sm" id="add-giving-type">
                                    <i class="fa fa-plus"></i> Add Giving Type
                                </button>
                            </div>
                            
                            <div class="form-group">
                                <label for="frequency_id">Giving Frequency <span class="text-danger">*</span></label>
                                <select class="form-control" id="frequency_id" name="frequency_id" required>
                                    <option value="">Select Frequency</option>
                                    <?php foreach ($giving_frequencies as $frequency): ?>
                                        <option value="<?php echo $frequency->id; ?>" 
                                                <?php echo (!empty($current_settings) && $current_settings[0]['frequency_id'] == $frequency->id) ? 'selected' : ''; ?>>
                                            <?php echo $frequency->name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> Update Giving Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Contribution -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-plus"></i> Add New Contribution</h3>
                    </div>
                    <form id="contributionForm">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="contribution_giving_type_id">Giving Type <span class="text-danger">*</span></label>
                                        <select class="form-control" id="contribution_giving_type_id" name="giving_type_id" required>
                                            <option value="">Select Giving Type</option>
                                            <?php foreach ($giving_types as $type): ?>
                                                <option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="amount">Amount <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="amount" name="amount" 
                                               min="0" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="currency">Currency</label>
                                        <select class="form-control" id="currency" name="currency">
                                            <option value="USD">USD</option>
                                            <option value="ZWL">ZWL</option>
                                            <option value="EUR">EUR</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="contribution_date">Contribution Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="contribution_date" name="contribution_date" 
                                               value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                                        <select class="form-control" id="payment_method" name="payment_method" required>
                                            <option value="">Select Payment Method</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="mobile_money">Mobile Money</option>
                                            <option value="cash">Cash</option>
                                            <option value="check">Check</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="transaction_id">Transaction ID</label>
                                        <input type="text" class="form-control" id="transaction_id" name="transaction_id" 
                                               placeholder="Optional">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="contribution_notes">Notes</label>
                                <textarea class="form-control" id="contribution_notes" name="notes" rows="3" 
                                          placeholder="Additional notes about this contribution"></textarea>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info">
                                <i class="fa fa-plus"></i> Submit Contribution
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Profile form submission
    $('#profileForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '<?php echo base_url("partnerdashboard/update-profile"); ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('An error occurred. Please try again.');
            }
        });
    });

    // Giving settings form submission
    $('#givingSettingsForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '<?php echo base_url("partnerdashboard/update-giving-settings"); ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('An error occurred. Please try again.');
            }
        });
    });

    // Contribution form submission
    $('#contributionForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '<?php echo base_url("partnerdashboard/add-contribution"); ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    toastr.success(response.message);
                    $('#contributionForm')[0].reset();
                    $('#contribution_date').val('<?php echo date('Y-m-d'); ?>');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('An error occurred. Please try again.');
            }
        });
    });

    // Add giving type row
    $('#add-giving-type').on('click', function() {
        var index = $('.giving-type-row').length;
        var newRow = `
            <div class="row giving-type-row" data-index="${index}">
                <div class="col-md-6">
                    <select class="form-control giving-type-select" name="giving_types[]" required>
                        <option value="">Select Giving Type</option>
                        <?php foreach ($giving_types as $type): ?>
                            <option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" class="form-control amount-input" name="amounts[]" 
                           placeholder="Amount" min="0" step="0.01" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm remove-giving-type">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        $('#giving-types-container').append(newRow);
    });

    // Remove giving type row
    $(document).on('click', '.remove-giving-type', function() {
        if ($('.giving-type-row').length > 1) {
            $(this).closest('.giving-type-row').remove();
        } else {
            toastr.warning('At least one giving type is required.');
        }
    });
});
</script>