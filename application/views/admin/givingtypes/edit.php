<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
        <i class="fa fa-edit"></i> Edit Giving Type
        <small>Update giving type information</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url(); ?>admin/partners"><i class="fa fa-handshake-o"></i> Partners</a></li>
        <li><a href="<?php echo base_url(); ?>admin/givingtypes"><i class="fa fa-gift"></i> Giving Types</a></li>
        <li class="active">Edit</li>
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
                    <h3 class="box-title"><i class="fa fa-edit"></i> Edit Giving Type Information</h3>
                    <div class="box-tools pull-right">
                        <a href="<?php echo base_url(); ?>admin/givingtypes/show/<?php echo $giving_type->id; ?>" class="btn btn-info btn-sm">
                            <i class="fa fa-eye"></i> View Details
                        </a>
                        <a href="<?php echo base_url(); ?>admin/givingtypes" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <?php echo form_open('admin/givingtypes/edit/' . $giving_type->id, array('class' => 'form-horizontal')); ?>
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
                                       placeholder="Enter giving type name"
                                       value="<?php echo set_value('name', $giving_type->name); ?>"
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
                                       placeholder="Enter code"
                                       value="<?php echo set_value('code', $giving_type->code); ?>">
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
                                          placeholder="Enter a detailed description"><?php echo set_value('description', $giving_type->description); ?></textarea>
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
                                       value="<?php echo set_value('sort_order', $giving_type->sort_order); ?>"
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
                                               <?php echo set_checkbox('is_active', '1', $giving_type->is_active == 1); ?>>
                                        <strong>Active</strong> - This giving type is available for selection
                                    </label>
                                </div>
                                <span class="help-block">Inactive giving types will not be available for new partners</span>
                            </div>
                        </div>

                        <!-- Metadata Information -->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Created</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">
                                    <i class="fa fa-calendar"></i>
                                    <?php echo date('F j, Y g:i A', strtotime($giving_type->created_at)); ?>
                                </p>
                            </div>
                        </div>

                        <?php if ($giving_type->updated_at && $giving_type->updated_at != '0000-00-00 00:00:00'): ?>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Last Updated</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">
                                    <i class="fa fa-calendar"></i>
                                    <?php echo date('F j, Y g:i A', strtotime($giving_type->updated_at)); ?>
                                </p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Update Giving Type
                                </button>
                                <a href="<?php echo base_url(); ?>admin/givingtypes/show/<?php echo $giving_type->id; ?>" class="btn btn-info">
                                    <i class="fa fa-eye"></i> View Details
                                </a>
                                <a href="<?php echo base_url(); ?>admin/givingtypes" class="btn btn-default">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>

            <!-- Usage Warning -->
            <?php
            $this->load->model('type_model');
            $usage_count = $this->type_model->getUsageCount($giving_type->id);
            if ($usage_count > 0):
            ?>
            <div class="alert alert-warning">
                <h4><i class="fa fa-warning"></i> Usage Notice</h4>
                <p>
                    This giving type is currently being used by <strong><?php echo $usage_count; ?></strong> partner<?php echo $usage_count != 1 ? 's' : ''; ?>.
                    Changes to this giving type will affect all associated partners.
                </p>
            </div>
            <?php endif; ?>
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
