<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_partner'); ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('user/user/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line('dashboard'); ?></a></li>
            <li><a href="<?php echo base_url('user/partner'); ?>"><i class="fa fa-handshake-o"></i> <?php echo $this->lang->line('partners'); ?></a></li>
            <li class="active"><?php echo $this->lang->line('add_partner'); ?></li>
        </ol>
    </section>

    <section class="content">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_new_partner'); ?>
                        </h3>
                    </div>
                    <form action="<?php echo base_url('user/partner/process_add'); ?>" method="post" id="partnerForm">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="firstname"><?php echo $this->lang->line('first_name'); ?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo set_value('firstname'); ?>" required>
                                        <?php echo form_error('firstname', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lastname"><?php echo $this->lang->line('last_name'); ?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo set_value('lastname'); ?>" required>
                                        <?php echo form_error('lastname', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email"><?php echo $this->lang->line('email'); ?> <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo set_value('email'); ?>" required>
                                        <?php echo form_error('email', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobileno"><?php echo $this->lang->line('mobile_number'); ?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="mobileno" name="mobileno" value="<?php echo set_value('mobileno'); ?>" required>
                                        <?php echo form_error('mobileno', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="address"><?php echo $this->lang->line('address'); ?></label>
                                <textarea class="form-control" id="address" name="address" rows="3"><?php echo set_value('address'); ?></textarea>
                                <?php echo form_error('address', '<div class="text-danger">', '</div>'); ?>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="city"><?php echo $this->lang->line('city'); ?></label>
                                        <input type="text" class="form-control" id="city" name="city" value="<?php echo set_value('city'); ?>">
                                        <?php echo form_error('city', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="state"><?php echo $this->lang->line('state'); ?></label>
                                        <input type="text" class="form-control" id="state" name="state" value="<?php echo set_value('state'); ?>">
                                        <?php echo form_error('state', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="country"><?php echo $this->lang->line('country'); ?></label>
                                        <input type="text" class="form-control" id="country" name="country" value="<?php echo set_value('country'); ?>">
                                        <?php echo form_error('country', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="zip_code"><?php echo $this->lang->line('zip_code'); ?></label>
                                        <input type="text" class="form-control" id="zip_code" name="zip_code" value="<?php echo set_value('zip_code'); ?>">
                                        <?php echo form_error('zip_code', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="currency"><?php echo $this->lang->line('currency'); ?></label>
                                        <select class="form-control" id="currency" name="currency">
                                            <option value="USD" <?php echo set_select('currency', 'USD', TRUE); ?>>USD</option>
                                            <option value="ZMW" <?php echo set_select('currency', 'ZMW'); ?>>ZMW</option>
                                            <option value="EUR" <?php echo set_select('currency', 'EUR'); ?>>EUR</option>
                                            <option value="GBP" <?php echo set_select('currency', 'GBP'); ?>>GBP</option>
                                        </select>
                                        <?php echo form_error('currency', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <h4><i class="fa fa-gift"></i> <?php echo $this->lang->line('giving_information'); ?></h4>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="giving_type_id"><?php echo $this->lang->line('giving_type'); ?> <span class="text-danger">*</span></label>
                                        <select class="form-control" id="giving_type_id" name="giving_type_id" required>
                                            <option value=""><?php echo $this->lang->line('select_giving_type'); ?></option>
                                            <?php foreach ($giving_types as $type): ?>
                                                <option value="<?php echo $type->id; ?>" <?php echo set_select('giving_type_id', $type->id); ?>>
                                                    <?php echo $type->name; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php echo form_error('giving_type_id', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="giving_frequency_id"><?php echo $this->lang->line('giving_frequency'); ?> <span class="text-danger">*</span></label>
                                        <select class="form-control" id="giving_frequency_id" name="giving_frequency_id" required>
                                            <option value=""><?php echo $this->lang->line('select_frequency'); ?></option>
                                            <?php foreach ($giving_frequencies as $frequency): ?>
                                                <option value="<?php echo $frequency->id; ?>" <?php echo set_select('giving_frequency_id', $frequency->id); ?>>
                                                    <?php echo $frequency->name; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php echo form_error('giving_frequency_id', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contribution_amount"><?php echo $this->lang->line('contribution_amount'); ?> <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="contribution_amount" name="contribution_amount" 
                                               value="<?php echo set_value('contribution_amount'); ?>" step="0.01" min="0" required>
                                        <?php echo form_error('contribution_amount', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status"><?php echo $this->lang->line('status'); ?></label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="active" <?php echo set_select('status', 'active', TRUE); ?>><?php echo $this->lang->line('active'); ?></option>
                                            <option value="inactive" <?php echo set_select('status', 'inactive'); ?>><?php echo $this->lang->line('inactive'); ?></option>
                                        </select>
                                        <?php echo form_error('status', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date"><?php echo $this->lang->line('start_date'); ?></label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo set_value('start_date'); ?>">
                                        <?php echo form_error('start_date', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date"><?php echo $this->lang->line('end_date'); ?></label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo set_value('end_date'); ?>">
                                        <?php echo form_error('end_date', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="notes"><?php echo $this->lang->line('notes'); ?></label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="<?php echo $this->lang->line('additional_notes'); ?>"><?php echo set_value('notes'); ?></textarea>
                                <?php echo form_error('notes', '<div class="text-danger">', '</div>'); ?>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> <?php echo $this->lang->line('add_partner'); ?>
                            </button>
                            <a href="<?php echo base_url('user/partner'); ?>" class="btn btn-default">
                                <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Form validation
    $('#partnerForm').validate({
        rules: {
            firstname: {
                required: true,
                minlength: 2
            },
            lastname: {
                required: true,
                minlength: 2
            },
            email: {
                required: true,
                email: true
            },
            mobileno: {
                required: true,
                minlength: 10
            },
            giving_type_id: {
                required: true
            },
            giving_frequency_id: {
                required: true
            },
            contribution_amount: {
                required: true,
                min: 0.01
            }
        },
        messages: {
            firstname: {
                required: "Please enter first name",
                minlength: "First name must be at least 2 characters"
            },
            lastname: {
                required: "Please enter last name",
                minlength: "Last name must be at least 2 characters"
            },
            email: {
                required: "Please enter email address",
                email: "Please enter a valid email address"
            },
            mobileno: {
                required: "Please enter mobile number",
                minlength: "Mobile number must be at least 10 digits"
            },
            giving_type_id: {
                required: "Please select giving type"
            },
            giving_frequency_id: {
                required: "Please select giving frequency"
            },
            contribution_amount: {
                required: "Please enter contribution amount",
                min: "Contribution amount must be greater than 0"
            }
        }
    });
});
</script>