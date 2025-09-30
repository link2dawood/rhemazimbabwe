<div class="content-wrapper">       
    <section class="content">
        <div class="row">
            <div class="col-md-12">             
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-envelope"></i> <?php echo $this->lang->line('setting'); ?></h3>
                    </div>   
                    <form id="form1" action="<?php echo current_url(); ?>" name="employeeform" class="form-horizontal form-label-left" method="post" accept-charset="utf-8">
                        <div class="box-body">
                                                                      

                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg') ?>
                            <?php } ?>   
                            <?php echo $this->customlib->getCSRF(); ?>                           
                         
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exampleInputEmail1">
                               <?php echo $this->lang->line('two_factor_authentication'); ?><small class="req"> *</small>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="radio-inline">
                                        <input type="radio" name="use_authenticator" value="0" <?php
                                            if (!$setting->use_authenticator) {
                                                echo "checked";
                                            }
                                            ?> ><?php echo $this->lang->line('disabled'); ?>
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="use_authenticator" value="1" <?php
                                            if ($setting->use_authenticator) {
                                                echo "checked";
                                            }
                                            ?>><?php echo $this->lang->line('enabled'); ?>
                                    </label>
                                    <span class="text-danger"><?php echo form_error('use_authenticator'); ?></span>
                                </div>
                            </div>                          
                        </div>
                        <div class="box-footer">
                            <div class="col-md-6 col-sm-6 col-xs-6 col-md-offset-3">
                                <?php
                                if ($this->rbac->hasPrivilege('google_authenticate_setting', 'can_view')) {
                                    ?>
                                    <button type="submit" class="btn btn-info pull-left"><?php echo $this->lang->line('save'); ?></button>
                                    <?php
                                }
                                ?>                           
                            </div>
                            <div class="pull-right"><?php echo $this->lang->line('version') . " " . $version; ?></div>
                        </div>
                    </form>
                </div>
            </div>           
        </div>
    </section>
</div>