<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-cog"></i> Giving Settings
            <small>Manage your contribution preferences</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>partnerdashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Giving Settings</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-check"></i> <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-ban"></i> <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-heart"></i> Configure Your Giving</h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo base_url('partnerdashboard'); ?>" class="btn btn-sm btn-default">
                                <i class="fa fa-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>

                    <form id="giving-settings-form" method="post">
                        <?php echo $this->customlib->getCSRF(); ?>

                        <div class="box-body">
                            <!-- Partner Info Summary -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <h4><i class="icon fa fa-info-circle"></i> Partner Information</h4>
                                        <p><strong>Name:</strong> <?php echo $partner['firstname'] . ' ' . $partner['lastname']; ?></p>
                                        <p><strong>Partner Code:</strong> <?php echo $partner['partner_code']; ?></p>
                                        <p><strong>Status:</strong> <span class="label label-<?php echo $partner['status'] == 'active' ? 'success' : 'warning'; ?>"><?php echo ucfirst($partner['status']); ?></span></p>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Giving Types Selection -->
                            <h4 class="box-title"><i class="fa fa-list"></i> Select Giving Type(s)</h4>
                            <p class="text-muted">Choose one or more types of contributions and specify the amount for each.</p>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr class="bg-primary">
                                                    <th width="50">Select</th>
                                                    <th>Giving Type</th>
                                                    <th width="200">Amount (<?php echo $currency_symbol; ?>)</th>
                                                </tr>
                                            </thead>
                                            <tbody id="giving-types-table">
                                                <?php if (!empty($giving_types)): ?>
                                                    <?php foreach ($giving_types as $index => $type): ?>
                                                        <?php
                                                        // Check if this type is already selected
                                                        $is_selected = false;
                                                        $current_amount = 0;
                                                        if (!empty($current_settings)) {
                                                            foreach ($current_settings as $setting) {
                                                                if ($setting['giving_type_id'] == $type->id) {
                                                                    $is_selected = true;
                                                                    $current_amount = $setting['amount'];
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <input type="checkbox"
                                                                       name="giving_types[]"
                                                                       value="<?php echo $type->id; ?>"
                                                                       id="type_<?php echo $type->id; ?>"
                                                                       class="giving-type-checkbox"
                                                                       data-index="<?php echo $index; ?>"
                                                                       <?php echo $is_selected ? 'checked' : ''; ?>>
                                                            </td>
                                                            <td>
                                                                <label for="type_<?php echo $type->id; ?>" style="font-weight: normal; margin: 0;">
                                                                    <strong><?php echo $type->name; ?></strong>
                                                                    <?php if (!empty($type->description)): ?>
                                                                        <br><small class="text-muted"><?php echo $type->description; ?></small>
                                                                    <?php endif; ?>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"><?php echo $currency_symbol; ?></span>
                                                                    <input type="number"
                                                                           step="0.01"
                                                                           min="0"
                                                                           name="amounts[]"
                                                                           id="amount_<?php echo $type->id; ?>"
                                                                           class="form-control amount-input"
                                                                           data-type-id="<?php echo $type->id; ?>"
                                                                           placeholder="0.00"
                                                                           value="<?php echo $current_amount > 0 ? number_format($current_amount, 2, '.', '') : ''; ?>">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">
                                                            No giving types available. Please contact administrator.
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr class="bg-gray-light">
                                                    <td colspan="2" class="text-right"><strong>Total Amount:</strong></td>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><?php echo $currency_symbol; ?></span>
                                                            <input type="text"
                                                                   id="total-amount-display"
                                                                   class="form-control"
                                                                   value="<?php echo number_format($total_amount, 2); ?>"
                                                                   readonly
                                                                   style="font-weight: bold; font-size: 16px;">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Giving Frequency -->
                            <h4 class="box-title"><i class="fa fa-calendar"></i> Select Giving Frequency</h4>
                            <p class="text-muted">Choose how often you would like to contribute.</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="frequency_id">Giving Frequency <span class="text-danger">*</span></label>
                                        <select name="frequency_id" id="frequency_id" class="form-control" required>
                                            <option value="">-- Select Frequency --</option>
                                            <?php if (!empty($giving_frequencies)): ?>
                                                <?php foreach ($giving_frequencies as $frequency): ?>
                                                    <option value="<?php echo $frequency->id; ?>"
                                                            <?php echo ($current_frequency == $frequency->id) ? 'selected' : ''; ?>>
                                                        <?php echo $frequency->name; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="currency">Currency <span class="text-danger">*</span></label>
                                        <select name="currency" id="currency" class="form-control">
                                            <option value="USD" <?php echo ($partner['currency'] == 'USD') ? 'selected' : ''; ?>>USD - US Dollar</option>
                                            <option value="ZWL" <?php echo ($partner['currency'] == 'ZWL') ? 'selected' : ''; ?>>ZWL - Zimbabwe Dollar</option>
                                            <option value="ZAR" <?php echo ($partner['currency'] == 'ZAR') ? 'selected' : ''; ?>>ZAR - South African Rand</option>
                                            <option value="EUR" <?php echo ($partner['currency'] == 'EUR') ? 'selected' : ''; ?>>EUR - Euro</option>
                                            <option value="GBP" <?php echo ($partner['currency'] == 'GBP') ? 'selected' : ''; ?>>GBP - British Pound</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Commitment Summary -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="callout callout-success">
                                        <h4><i class="icon fa fa-check"></i> Your Giving Commitment Summary</h4>
                                        <p id="commitment-summary">
                                            You have selected <strong id="types-count">0</strong> giving type(s) with a total of
                                            <strong><?php echo $currency_symbol; ?><span id="summary-total">0.00</span></strong>
                                            per <strong id="summary-frequency">--</strong>.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-lg btn-success pull-right" id="save-settings-btn">
                                <i class="fa fa-save"></i> Save Giving Settings
                            </button>
                            <a href="<?php echo base_url('partnerdashboard'); ?>" class="btn btn-lg btn-default">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
$(document).ready(function() {

    // Calculate total when amounts change
    function calculateTotal() {
        var total = 0;
        var selectedCount = 0;

        $('.giving-type-checkbox:checked').each(function() {
            var typeId = $(this).val();
            var amount = parseFloat($('#amount_' + typeId).val()) || 0;
            if (amount > 0) {
                total += amount;
                selectedCount++;
            }
        });

        $('#total-amount-display').val(total.toFixed(2));
        $('#summary-total').text(total.toFixed(2));
        $('#types-count').text(selectedCount);

        updateSummary();
    }

    // Update commitment summary
    function updateSummary() {
        var frequency = $('#frequency_id option:selected').text();
        if (frequency && frequency !== '-- Select Frequency --') {
            $('#summary-frequency').text(frequency);
        } else {
            $('#summary-frequency').text('--');
        }
    }

    // When checkbox is checked/unchecked
    $('.giving-type-checkbox').on('change', function() {
        var typeId = $(this).val();
        var amountInput = $('#amount_' + typeId);

        if ($(this).is(':checked')) {
            amountInput.prop('disabled', false);
            if (!amountInput.val() || amountInput.val() == '0') {
                amountInput.focus();
            }
        } else {
            amountInput.val('');
            amountInput.prop('disabled', true);
        }

        calculateTotal();
    });

    // When amount changes
    $('.amount-input').on('input change', function() {
        var typeId = $(this).data('type-id');
        var checkbox = $('#type_' + typeId);
        var amount = parseFloat($(this).val()) || 0;

        // Auto-check checkbox if amount is entered
        if (amount > 0 && !checkbox.is(':checked')) {
            checkbox.prop('checked', true);
        }

        // Uncheck if amount is 0
        if (amount == 0 && checkbox.is(':checked')) {
            checkbox.prop('checked', false);
        }

        calculateTotal();
    });

    // When frequency changes
    $('#frequency_id').on('change', function() {
        updateSummary();
    });

    // Initialize on page load
    $('.giving-type-checkbox:checked').each(function() {
        var typeId = $(this).val();
        $('#amount_' + typeId).prop('disabled', false);
    });

    $('.giving-type-checkbox:not(:checked)').each(function() {
        var typeId = $(this).val();
        $('#amount_' + typeId).prop('disabled', true);
    });

    calculateTotal();

    // Form submission
    $('#giving-settings-form').on('submit', function(e) {
        e.preventDefault();

        // Validate at least one type selected
        var selectedTypes = $('.giving-type-checkbox:checked').length;
        if (selectedTypes == 0) {
            errorMsg('Please select at least one giving type');
            return false;
        }

        // Validate amounts
        var hasValidAmount = false;
        $('.giving-type-checkbox:checked').each(function() {
            var typeId = $(this).val();
            var amount = parseFloat($('#amount_' + typeId).val()) || 0;
            if (amount > 0) {
                hasValidAmount = true;
            }
        });

        if (!hasValidAmount) {
            errorMsg('Please enter valid amounts greater than 0 for selected giving types');
            return false;
        }

        // Validate frequency
        if (!$('#frequency_id').val()) {
            errorMsg('Please select a giving frequency');
            return false;
        }

        var formData = $(this).serialize();
        var btn = $('#save-settings-btn');
        var originalText = btn.html();

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: '<?php echo base_url("partnerdashboard/update_giving_settings"); ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                btn.prop('disabled', false).html(originalText);

                if (response.status) {
                    successMsg(response.message);

                    // Update display
                    if (response.total_amount) {
                        $('#total-amount-display').val(parseFloat(response.total_amount).toFixed(2));
                        $('#summary-total').text(parseFloat(response.total_amount).toFixed(2));
                    }

                    // Optionally reload after 2 seconds
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                } else {
                    errorMsg(response.message);
                }
            },
            error: function(xhr, status, error) {
                btn.prop('disabled', false).html(originalText);
                errorMsg('An error occurred while saving settings. Please try again.');
                console.error(error);
            }
        });
    });
});
</script>

<style>
.box-title {
    color: #3c8dbc;
    font-weight: bold;
    margin-bottom: 10px;
}

.table > thead > tr > th {
    background-color: #3c8dbc;
    color: white;
}

.callout-success {
    border-left-color: #00a65a;
}

.amount-input:disabled {
    background-color: #f4f4f4;
    cursor: not-allowed;
}

.giving-type-checkbox {
    transform: scale(1.3);
    cursor: pointer;
}

#total-amount-display {
    background-color: #d9edf7 !important;
    color: #31708f;
    font-weight: bold;
}
</style>
