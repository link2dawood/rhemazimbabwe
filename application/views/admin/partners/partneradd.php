<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-users"></i> <?php echo $this->lang->line('partners'); ?></h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('add_partner'); ?></h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo base_url() ?>admin/partners" class="btn btn-sm btn-primary">
                                <i class="fa fa-list"></i> <?php echo $this->lang->line('partner_list'); ?>
                            </a>
                        </div>
                    </div>

                    <form action="<?php echo site_url('admin/partners/add') ?>" method="post" enctype="multipart/form-data">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) {
                                echo $this->session->flashdata('msg');
                                $this->session->unset_userdata('msg');
                            } ?>
                            <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
                            <?php echo $this->customlib->getCSRF(); ?>

                            <h4 class="box-title"><?php echo $this->lang->line('personal_information'); ?></h4>
                            <hr/>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label><?php echo $this->lang->line('first_name'); ?></label><small class="req"> *</small>
                                    <input type="text" name="firstname" class="form-control" value="<?php echo set_value('firstname'); ?>" autofocus>
                                    <span class="text-danger"><?php echo form_error('firstname'); ?></span>
                                </div>

                                <div class="form-group col-md-6">
                                    <label><?php echo $this->lang->line('last_name'); ?></label><small class="req"> *</small>
                                    <input type="text" name="lastname" class="form-control" value="<?php echo set_value('lastname'); ?>">
                                    <span class="text-danger"><?php echo form_error('lastname'); ?></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label><?php echo $this->lang->line('email'); ?></label>
                                    <input type="email" name="email" class="form-control" value="<?php echo set_value('email'); ?>">
                                    <span class="text-danger"><?php echo form_error('email'); ?></span>
                                </div>

                                <div class="form-group col-md-6">
                                    <label><?php echo $this->lang->line('phone'); ?></label>
                                    <input type="text" name="mobileno" class="form-control" value="<?php echo set_value('mobileno'); ?>">
                                    <span class="text-danger"><?php echo form_error('mobileno'); ?></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label><?php echo $this->lang->line('address'); ?></label>
                                    <textarea name="address" class="form-control" rows="2"><?php echo set_value('address'); ?></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label><?php echo $this->lang->line('city'); ?></label>
                                    <input type="text" name="city" class="form-control" value="<?php echo set_value('city'); ?>">
                                </div>

                                <div class="form-group col-md-3">
                                    <label><?php echo $this->lang->line('state'); ?></label>
                                    <input type="text" name="state" class="form-control" value="<?php echo set_value('state'); ?>">
                                </div>

                                <div class="form-group col-md-3">
                                    <label><?php echo $this->lang->line('country'); ?></label>
                                    <input type="text" name="country" class="form-control" value="<?php echo set_value('country'); ?>">
                                </div>

                                <div class="form-group col-md-3">
                                    <label><?php echo $this->lang->line('zip_code'); ?></label>
                                    <input type="text" name="zipcode" class="form-control" value="<?php echo set_value('zipcode'); ?>">
                                </div>
                            </div>

                            <h4 class="box-title"><?php echo $this->lang->line('giving_information'); ?></h4>
                            <hr/>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label><?php echo $this->lang->line('giving_type'); ?></label>
                                    <select name="giving_type_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($giving_types as $type) { ?>
                                            <option value="<?php echo $type->id ?>" <?php echo set_select('giving_type_id', $type->id); ?>>
                                                <?php echo $type->name ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label><?php echo $this->lang->line('giving_frequency'); ?></label>
                                    <select name="giving_frequency_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($giving_frequencies as $frequency) { ?>
                                            <option value="<?php echo $frequency->id ?>" <?php echo set_select('giving_frequency_id', $frequency->id); ?>>
                                                <?php echo $frequency->name ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label><?php echo $this->lang->line('contribution_amount'); ?> (<?php echo $currency_symbol; ?>)</label>
                                    <input type="number" step="0.01" name="contribution_amount" class="form-control" value="<?php echo set_value('contribution_amount'); ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label><?php echo $this->lang->line('currency'); ?></label>
                                    <select name="currency" class="form-control">
                                        <option value="USD" <?php echo set_select('currency', 'USD', true); ?>>USD</option>
                                        <option value="ZMW" <?php echo set_select('currency', 'ZMW'); ?>>ZMW</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label><?php echo $this->lang->line('start_date'); ?></label>
                                    <input type="text" name="start_date" class="form-control date" value="<?php echo set_value('start_date', date($this->customlib->getSchoolDateFormat())); ?>">
                                </div>

                                <div class="form-group col-md-4">
                                    <label><?php echo $this->lang->line('student_link'); ?> <small>(<?php echo $this->lang->line('optional'); ?>)</small></label>
                                    <select name="student_id" class="form-control select2">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                </div>
                            </div>

                            <h4 class="box-title"><?php echo $this->lang->line('additional_information'); ?></h4>
                            <hr/>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label><?php echo $this->lang->line('photo'); ?></label>
                                    <input type="file" name="file" class="form-control filestyle" data-buttonText="<?php echo $this->lang->line('browse'); ?>">
                                    <span class="text-danger"><?php echo form_error('file'); ?></span>
                                </div>

                                <div class="form-group col-md-6">
                                    <label><?php echo $this->lang->line('notes'); ?></label>
                                    <textarea name="notes" class="form-control" rows="3"><?php echo set_value('notes'); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right">
                                <i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>