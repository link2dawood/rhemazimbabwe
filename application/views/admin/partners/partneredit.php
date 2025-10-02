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
                        <h3 class="box-title"><?php echo $this->lang->line('edit_partner'); ?></h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo base_url() ?>admin/partners/show/<?php echo $partner->id ?>" class="btn btn-sm btn-info">
                                <i class="fa fa-eye"></i> <?php echo $this->lang->line('view_details'); ?>
                            </a>
                            <a href="<?php echo base_url() ?>admin/partners" class="btn btn-sm btn-primary">
                                <i class="fa fa-list"></i> <?php echo $this->lang->line('partner_list'); ?>
                            </a>
                        </div>
                    </div>

                    <form action="<?php echo site_url('admin/partners/edit/'.$partner->id) ?>" method="post" enctype="multipart/form-data">
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
                                    <label><?php echo $this->lang->line('partner_code'); ?></label>
                                    <input type="text" class="form-control" value="<?php echo $partner->partner_code ?>" readonly>
                                </div>

                                <div class="form-group col-md-6">
                                    <label><?php echo $this->lang->line('status'); ?></label>
                                    <select name="status" class="form-control">
                                        <option value="active" <?php echo ($partner->status == 'active') ? 'selected' : '' ?>><?php echo $this->lang->line('active'); ?></option>
                                        <option value="inactive" <?php echo ($partner->status == 'inactive') ? 'selected' : '' ?>><?php echo $this->lang->line('inactive'); ?></option>
                                        <option value="suspended" <?php echo ($partner->status == 'suspended') ? 'selected' : '' ?>><?php echo $this->lang->line('suspended'); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label><?php echo $this->lang->line('first_name'); ?></label><small class="req"> *</small>
                                    <input type="text" name="firstname" class="form-control" value="<?php echo set_value('firstname', $partner->firstname); ?>">
                                    <span class="text-danger"><?php echo form_error('firstname'); ?></span>
                                </div>

                                <div class="form-group col-md-6">
                                    <label><?php echo $this->lang->line('last_name'); ?></label><small class="req"> *</small>
                                    <input type="text" name="lastname" class="form-control" value="<?php echo set_value('lastname', $partner->lastname); ?>">
                                    <span class="text-danger"><?php echo form_error('lastname'); ?></span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label><?php echo $this->lang->line('email'); ?></label>
                                    <input type="email" name="email" class="form-control" value="<?php echo set_value('email', $partner->email); ?>">
                                    <span class="text-danger"><?php echo form_error('email'); ?></span>
                                </div>

                                <div class="form-group col-md-6">
                                    <label><?php echo $this->lang->line('phone'); ?></label>
                                    <input type="text" name="mobileno" class="form-control" value="<?php echo set_value('mobileno', $partner->mobileno); ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label><?php echo $this->lang->line('address'); ?></label>
                                    <textarea name="address" class="form-control" rows="2"><?php echo set_value('address', $partner->address); ?></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label><?php echo $this->lang->line('city'); ?></label>
                                    <input type="text" name="city" class="form-control" value="<?php echo set_value('city', $partner->city); ?>">
                                </div>

                                <div class="form-group col-md-3">
                                    <label><?php echo $this->lang->line('state'); ?></label>
                                    <input type="text" name="state" class="form-control" value="<?php echo set_value('state', $partner->state); ?>">
                                </div>

                                <div class="form-group col-md-3">
                                    <label><?php echo $this->lang->line('country'); ?></label>
                                    <input type="text" name="country" class="form-control" value="<?php echo set_value('country', $partner->country); ?>">
                                </div>

                                <div class="form-group col-md-3">
                                    <label><?php echo $this->lang->line('zip_code'); ?></label>
                                    <input type="text" name="zipcode" class="form-control" value="<?php echo set_value('zipcode', $partner->zipcode); ?>">
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
                                            <option value="<?php echo $type->id ?>" <?php echo ($partner->giving_type_id == $type->id) ? 'selected' : '' ?>>
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
                                            <option value="<?php echo $frequency->id ?>" <?php echo ($partner->giving_frequency_id == $frequency->id) ? 'selected' : '' ?>>
                                                <?php echo $frequency->name ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label><?php echo $this->lang->line('contribution_amount'); ?> (<?php echo $currency_symbol; ?>)</label>
                                    <input type="number" step="0.01" name="contribution_amount" class="form-control" value="<?php echo set_value('contribution_amount', $partner->contribution_amount); ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label><?php echo $this->lang->line('currency'); ?></label>
                                    <select name="currency" class="form-control">
                                        <option value="USD" <?php echo ($partner->currency == 'USD') ? 'selected' : '' ?>>USD</option>
                                        <option value="ZMW" <?php echo ($partner->currency == 'ZMW') ? 'selected' : '' ?>>ZMW</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label><?php echo $this->lang->line('start_date'); ?></label>
                                    <input type="text" name="start_date" class="form-control date" value="<?php echo set_value('start_date', date($this->customlib->getSchoolDateFormat(), strtotime($partner->start_date))); ?>">
                                </div>

                                <div class="form-group col-md-4">
                                    <label><?php echo $this->lang->line('end_date'); ?> <small>(<?php echo $this->lang->line('optional'); ?>)</small></label>
                                    <input type="text" name="end_date" class="form-control date" value="<?php echo set_value('end_date', $partner->end_date ? date($this->customlib->getSchoolDateFormat(), strtotime($partner->end_date)) : ''); ?>">
                                </div>
                            </div>

                            <h4 class="box-title"><?php echo $this->lang->line('additional_information'); ?></h4>
                            <hr/>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label><?php echo $this->lang->line('photo'); ?></label>
                                    <?php if ($partner->image) { ?>
                                        <div>
                                            <img src="<?php echo base_url() . $partner->image ?>" width="100" class="img-thumbnail">
                                        </div>
                                    <?php } ?>
                                    <input type="file" name="file" class="form-control filestyle" data-buttonText="<?php echo $this->lang->line('browse'); ?>">
                                    <span class="text-info"><?php echo $this->lang->line('leave_blank_to_keep_current'); ?></span>
                                </div>

                                <div class="form-group col-md-6">
                                    <label><?php echo $this->lang->line('notes'); ?></label>
                                    <textarea name="notes" class="form-control" rows="4"><?php echo set_value('notes', $partner->notes); ?></textarea>
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