<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-handshake-o"></i> <?php echo $this->lang->line('become_a_partner'); ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('user/user/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line('dashboard'); ?></a></li>
            <li><a href="<?php echo base_url('user/partner'); ?>"><i class="fa fa-handshake-o"></i> <?php echo $this->lang->line('partners'); ?></a></li>
            <li class="active"><?php echo $this->lang->line('register'); ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('partner_registration'); ?></h3>
                    </div>
                    <form id="partnerRegistrationForm" method="post" action="<?php echo base_url('user/partner/submitRegistration'); ?>">
                        <?php echo $this->customlib->getCSRF(); ?>
                        <div class="box-body">
                            <!-- Account Type Selection -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="box-title"><?php echo $this->lang->line('account_type'); ?> <span class="text-danger">*</span></h4>
                                    <div class="form-group">
                                        <label class="radio-inline">
                                            <input type="radio" name="account_type" value="individual" checked>
                                            <i class="fa fa-user"></i> <?php echo $this->lang->line('individual_partner'); ?>
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="account_type" value="organization">
                                            <i class="fa fa-building"></i> <?php echo $this->lang->line('organization_partner'); ?>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Organization Details (Conditional) -->
                            <div id="organizationFields" style="display: none;">
                                <h4><?php echo $this->lang->line('organization_information'); ?></h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('organization_name'); ?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="organization_name" id="organization_name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('organization_type'); ?></label>
                                            <select class="form-control" name="organization_type">
                                                <option value=""><?php echo $this->lang->line('select_type'); ?></option>
                                                <option value="Church"><?php echo $this->lang->line('church'); ?></option>
                                                <option value="Company"><?php echo $this->lang->line('company'); ?></option>
                                                <option value="Foundation"><?php echo $this->lang->line('foundation'); ?></option>
                                                <option value="NGO"><?php echo $this->lang->line('ngo'); ?></option>
                                                <option value="Other"><?php echo $this->lang->line('other'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <!-- Contact Information -->
                            <h4><?php echo $this->lang->line('contact_information'); ?></h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('first_name'); ?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="firstname" value="<?php echo $prefill['firstname'] ?? ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('last_name'); ?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="lastname" value="<?php echo $prefill['lastname'] ?? ''; ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('email'); ?> <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" value="<?php echo $prefill['email'] ?? ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('phone'); ?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="mobileno" value="<?php echo $prefill['mobileno'] ?? ''; ?>" required>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Address Information -->
                            <h4><?php echo $this->lang->line('address_information'); ?></h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('address'); ?></label>
                                        <textarea class="form-control" name="address" rows="2"><?php echo $prefill['address'] ?? ''; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('city'); ?></label>
                                        <input type="text" class="form-control" name="city">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('state'); ?></label>
                                        <input type="text" class="form-control" name="state">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('country'); ?></label>
                                        <input type="text" class="form-control" name="country" value="Zimbabwe">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('zip_code'); ?></label>
                                        <input type="text" class="form-control" name="zip_code">
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Giving Details -->
                            <h4><?php echo $this->lang->line('giving_details'); ?></h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('giving_type'); ?></label>
                                        <select class="form-control" name="giving_type_id">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($giving_types as $type): ?>
                                                <option value="<?php echo $type['id']; ?>"><?php echo $type['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('giving_frequency'); ?></label>
                                        <select class="form-control" name="giving_frequency_id">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($giving_frequencies as $freq): ?>
                                                <option value="<?php echo $freq['id']; ?>"><?php echo $freq['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('contribution_amount'); ?></label>
                                        <input type="number" class="form-control" name="contribution_amount" step="0.01" min="0">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('currency'); ?></label>
                                        <select class="form-control" name="currency">
                                            <option value="USD" selected>USD - US Dollar</option>
                                            <option value="ZWL">ZWL - Zimbabwe Dollar</option>
                                            <option value="ZAR">ZAR - South African Rand</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('notes'); ?></label>
                                        <textarea class="form-control" name="notes" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden Fields -->
                            <input type="hidden" name="student_id" value="<?php echo $prefill['student_id'] ?? ''; ?>">
                            <input type="hidden" name="staff_id" value="<?php echo $prefill['staff_id'] ?? ''; ?>">

                        </div>
                        <div class="box-footer">
                            <a href="<?php echo base_url('user/partner'); ?>" class="btn btn-default">
                                <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?>
                            </a>
                            <button type="submit" class="btn btn-primary pull-right">
                                <i class="fa fa-check"></i> <?php echo $this->lang->line('submit'); ?>
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
    // Toggle organization fields
    $('input[name="account_type"]').change(function() {
        if ($(this).val() == 'organization') {
            $('#organizationFields').slideDown();
            $('#organization_name').attr('required', true);
        } else {
            $('#organizationFields').slideUp();
            $('#organization_name').attr('required', false);
        }
    });

    // Form submission
    $('#partnerRegistrationForm').submit(function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> <?php echo $this->lang->line('processing'); ?>...');
            },
            success: function(response) {
                if (response.status == 'success') {
                    successMsg(response.message);
                    setTimeout(function() {
                        window.location.href = baseurl + 'user/partner';
                    }, 2000);
                } else {
                    errorMsg(response.message);
                    $('button[type="submit"]').prop('disabled', false).html('<i class="fa fa-check"></i> <?php echo $this->lang->line('submit'); ?>');
                }
            },
            error: function() {
                errorMsg('<?php echo $this->lang->line('error_occurred'); ?>');
                $('button[type="submit"]').prop('disabled', false).html('<i class="fa fa-check"></i> <?php echo $this->lang->line('submit'); ?>');
            }
        });
    });
});
</script>
