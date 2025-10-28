<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
        <i class="fa fa-gift"></i> Add Giving Type
        <small>Create a new giving type</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url(); ?>admin/partners"><i class="fa fa-handshake-o"></i> Partners</a></li>
        <li><a href="<?php echo base_url(); ?>admin/givingtypes"><i class="fa fa-gift"></i> Giving Types</a></li>
        <li class="active">Add</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Display flash messages -->
            <?php if ($this->session->flashdata('msg')) {
                echo $this->session->flashdata('msg');
            } ?>

            <!-- Display validation errors -->
            <?php if (validation_errors()) { ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-ban"></i> Validation Error!</h4>
                    <?php echo validation_errors(); ?>
                </div>
            <?php } ?>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-plus-circle"></i> Giving Type Information</h3>
                    <div class="box-tools pull-right">
                        <a href="<?php echo base_url(); ?>admin/givingtypes" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <?php echo form_open('admin/givingtypes/add', array('class' => 'form-horizontal')); ?>
                    <div class="box-body">
                        <!-- Name -->
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">
                                Name <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-9">
                                <input type="text"
                                       class="form-control"
                                       id="name"
                                       name="name"
                                       placeholder="Enter giving type name (e.g., Tuition Support, Building Fund)"
                                       value="<?php echo set_value('name'); ?>"
                                       required>
                                <span class="help-block">A unique, descriptive name for this giving type</span>
                            </div>
                        </div>

                        <!-- Code -->
                        <div class="form-group">
                            <label for="code" class="col-sm-3 control-label">
                                Code
                            </label>
                            <div class="col-sm-9">
                                <input type="text"
                                       class="form-control"
                                       id="code"
                                       name="code"
                                       placeholder="Enter code (e.g., TUITION, BUILDING)"
                                       value="<?php echo set_value('code'); ?>">
                                <span class="help-block">Optional short code for identification (will be converted to uppercase)</span>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description" class="col-sm-3 control-label">
                                Description
                            </label>
                            <div class="col-sm-9">
                                <textarea class="form-control"
                                          id="description"
                                          name="description"
                                          rows="4"
                                          placeholder="Enter a detailed description of this giving type"><?php echo set_value('description'); ?></textarea>
                                <span class="help-block">Provide details about what this giving type is used for</span>
                            </div>
                        </div>

                        <!-- Sort Order -->
                        <div class="form-group">
                            <label for="sort_order" class="col-sm-3 control-label">
                                Sort Order
                            </label>
                            <div class="col-sm-9">
                                <input type="number"
                                       class="form-control"
                                       id="sort_order"
                                       name="sort_order"
                                       value="<?php echo set_value('sort_order', '0'); ?>"
                                       min="0"
                                       step="1">
                                <span class="help-block">Lower numbers appear first in lists (0 = highest priority)</span>
                            </div>
                        </div>

                        <!-- Active Status -->
                        <div class="form-group">
                            <label for="is_active" class="col-sm-3 control-label">
                                Status
                            </label>
                            <div class="col-sm-9">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"
                                               id="is_active"
                                               name="is_active"
                                               value="1"
                                               <?php echo set_checkbox('is_active', '1', true); ?>>
                                        <strong>Active</strong> - This giving type is available for selection
                                    </label>
                                </div>
                                <span class="help-block">Inactive giving types will not be available for new partners</span>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Save Giving Type
                                </button>
                                <a href="<?php echo base_url(); ?>admin/givingtypes" class="btn btn-default">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>

            <!-- Help Section -->
            <div class="box box-info collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-question-circle"></i> Help & Guidelines</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <h4>What are Giving Types?</h4>
                    <p>Giving types categorize the different ways partners can contribute to the school. Examples include:</p>
                    <ul>
                        <li><strong>Tuition Support</strong> - Direct support for student tuition fees</li>
                        <li><strong>Building Fund</strong> - Contributions towards infrastructure development</li>
                        <li><strong>Scholarship Fund</strong> - Dedicated to student scholarships</li>
                        <li><strong>General Fund</strong> - Unrestricted donations for any school need</li>
                        <li><strong>Special Projects</strong> - Specific initiatives or programs</li>
                    </ul>

                    <h4>Best Practices:</h4>
                    <ul>
                        <li>Use clear, descriptive names that partners will understand</li>
                        <li>Keep codes short and uppercase (e.g., TUITION, BLDG, SCHLR)</li>
                        <li>Set sort order to control the display order in dropdowns</li>
                        <li>Only deactivate types that are no longer accepting contributions</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
</div>

<script type="text/javascript">
$(document).ready(function() {
    // Auto-uppercase code field
    $('#code').on('input', function() {
        this.value = this.value.toUpperCase();
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Form validation
    $('form').on('submit', function(e) {
        var name = $('#name').val().trim();

        if (name === '') {
            e.preventDefault();
            alert('Please enter a name for the giving type.');
            $('#name').focus();
            return false;
        }
    });
});
</script>
