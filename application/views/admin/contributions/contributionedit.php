<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-edit"></i> Edit Contribution</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo base_url(); ?>admin/partnercontributions">Contributions</a></li>
            <li class="active">Edit Contribution</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Contribution Details</h3>
                    </div>

                    <form role="form" id="contributionForm" method="post" action="<?php echo base_url('admin/partnercontributions/edit/' . $contribution->id); ?>" enctype="multipart/form-data">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) {
                                echo $this->session->flashdata('msg');
                            } ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="partner_id">Partner <span class="text-danger">*</span></label>
                                        <select class="form-control select2" name="partner_id" id="partner_id" required>
                                            <option value="">Select Partner</option>
                                            <?php foreach ($partners as $partner) { ?>
                                                <option value="<?php echo $partner->id; ?>" 
                                                        <?php echo set_select('partner_id', $partner->id, ($contribution->partner_id == $partner->id)); ?>>
                                                    <?php echo $partner->firstname . ' ' . $partner->lastname . ' (' . $partner->partner_code . ')'; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('partner_id'); ?></span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contribution_date">Contribution Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="contribution_date" id="contribution_date" 
                                               value="<?php echo set_value('contribution_date', $contribution->contribution_date); ?>" required>
                                        <span class="text-danger"><?php echo form_error('contribution_date'); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="amount">Amount <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><?php echo $currency_symbol; ?></span>
                                            <input type="number" class="form-control" name="amount" id="amount" 
                                                   value="<?php echo set_value('amount', $contribution->amount); ?>" step="0.01" min="0" required>
                                        </div>
                                        <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="currency">Currency</label>
                                        <select class="form-control" name="currency" id="currency">
                                            <option value="USD" <?php echo set_select('currency', 'USD', ($contribution->currency == 'USD')); ?>>USD</option>
                                            <option value="ZWL" <?php echo set_select('currency', 'ZWL', ($contribution->currency == 'ZWL')); ?>>ZWL</option>
                                            <option value="EUR" <?php echo set_select('currency', 'EUR', ($contribution->currency == 'EUR')); ?>>EUR</option>
                                            <option value="GBP" <?php echo set_select('currency', 'GBP', ($contribution->currency == 'GBP')); ?>>GBP</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                                        <select class="form-control" name="payment_method" id="payment_method" required>
                                            <option value="">Select Payment Method</option>
                                            <option value="cash" <?php echo set_select('payment_method', 'cash', ($contribution->payment_method == 'cash')); ?>>Cash</option>
                                            <option value="bank_transfer" <?php echo set_select('payment_method', 'bank_transfer', ($contribution->payment_method == 'bank_transfer')); ?>>Bank Transfer</option>
                                            <option value="mobile_money" <?php echo set_select('payment_method', 'mobile_money', ($contribution->payment_method == 'mobile_money')); ?>>Mobile Money</option>
                                            <option value="credit_card" <?php echo set_select('payment_method', 'credit_card', ($contribution->payment_method == 'credit_card')); ?>>Credit Card</option>
                                            <option value="debit_card" <?php echo set_select('payment_method', 'debit_card', ($contribution->payment_method == 'debit_card')); ?>>Debit Card</option>
                                            <option value="cheque" <?php echo set_select('payment_method', 'cheque', ($contribution->payment_method == 'cheque')); ?>>Cheque</option>
                                            <option value="online" <?php echo set_select('payment_method', 'online', ($contribution->payment_method == 'online')); ?>>Online</option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('payment_method'); ?></span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="completed" <?php echo set_select('status', 'completed', ($contribution->status == 'completed')); ?>>Completed</option>
                                            <option value="pending" <?php echo set_select('status', 'pending', ($contribution->status == 'pending')); ?>>Pending</option>
                                            <option value="cancelled" <?php echo set_select('status', 'cancelled', ($contribution->status == 'cancelled')); ?>>Cancelled</option>
                                            <option value="failed" <?php echo set_select('status', 'failed', ($contribution->status == 'failed')); ?>>Failed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="giving_type_id">Giving Type</label>
                                        <select class="form-control" name="giving_type_id" id="giving_type_id">
                                            <option value="">Select Giving Type</option>
                                            <?php foreach ($giving_types as $type) { ?>
                                                <option value="<?php echo $type->id; ?>" 
                                                        <?php echo set_select('giving_type_id', $type->id, ($contribution->giving_type_id == $type->id)); ?>>
                                                    <?php echo $type->name; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="giving_frequency_id">Giving Frequency</label>
                                        <select class="form-control" name="giving_frequency_id" id="giving_frequency_id">
                                            <option value="">Select Frequency</option>
                                            <?php foreach ($giving_frequencies as $frequency) { ?>
                                                <option value="<?php echo $frequency->id; ?>" 
                                                        <?php echo set_select('giving_frequency_id', $frequency->id, ($contribution->giving_frequency_id == $frequency->id)); ?>>
                                                    <?php echo $frequency->name; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="transaction_id">Transaction ID</label>
                                        <input type="text" class="form-control" name="transaction_id" id="transaction_id" 
                                               value="<?php echo set_value('transaction_id', $contribution->transaction_id); ?>" placeholder="Enter transaction ID">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="reference_no">Reference Number</label>
                                        <input type="text" class="form-control" name="reference_no" id="reference_no" 
                                               value="<?php echo set_value('reference_no', $contribution->reference_no); ?>" placeholder="Enter reference number">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea class="form-control" name="notes" id="notes" rows="3" 
                                                  placeholder="Enter any additional notes"><?php echo set_value('notes', $contribution->notes); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="attachment">Attachment</label>
                                        <?php if (!empty($contribution->attachment)) { ?>
                                            <div class="alert alert-info">
                                                <i class="fa fa-paperclip"></i> Current attachment: 
                                                <a href="<?php echo base_url($contribution->attachment); ?>" target="_blank">
                                                    <?php echo basename($contribution->attachment); ?>
                                                </a>
                                            </div>
                                        <?php } ?>
                                        <input type="file" class="form-control" name="attachment" id="attachment" 
                                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                        <small class="text-muted">Supported formats: PDF, JPG, PNG, DOC, DOCX (Max 5MB). Leave empty to keep current attachment.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update Contribution
                            </button>
                            <a href="<?php echo base_url('admin/partnercontributions'); ?>" class="btn btn-default">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                            <a href="<?php echo base_url('admin/partnercontributions/show/' . $contribution->id); ?>" class="btn btn-info">
                                <i class="fa fa-eye"></i> View Details
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
        // Initialize Select2
        $('.select2').select2({
            placeholder: "Select an option",
            allowClear: true
        });

        // Form validation
        $('#contributionForm').validate({
            rules: {
                partner_id: {
                    required: true
                },
                contribution_date: {
                    required: true
                },
                amount: {
                    required: true,
                    number: true,
                    min: 0.01
                },
                payment_method: {
                    required: true
                }
            },
            messages: {
                partner_id: {
                    required: "Please select a partner"
                },
                contribution_date: {
                    required: "Please select a contribution date"
                },
                amount: {
                    required: "Please enter an amount",
                    number: "Please enter a valid number",
                    min: "Amount must be greater than 0"
                },
                payment_method: {
                    required: "Please select a payment method"
                }
            },
            errorElement: 'span',
            errorClass: 'text-danger',
            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
            }
        });

        // File size validation
        $('#attachment').on('change', function() {
            var file = this.files[0];
            if (file) {
                var fileSize = file.size / 1024 / 1024; // Convert to MB
                if (fileSize > 5) {
                    alert('File size must be less than 5MB');
                    $(this).val('');
                }
            }
        });
    });
</script>
