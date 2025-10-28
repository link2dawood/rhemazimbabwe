<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
        <i class="fa fa-gift"></i> Giving Type Details
        <small><?php echo htmlspecialchars($giving_type->name); ?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url(); ?>admin/partners"><i class="fa fa-handshake-o"></i> Partners</a></li>
        <li><a href="<?php echo base_url(); ?>admin/givingtypes"><i class="fa fa-gift"></i> Giving Types</a></li>
        <li class="active">Details</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-8">
            <!-- Display flash messages -->
            <?php if ($this->session->flashdata('msg')) {
                echo $this->session->flashdata('msg');
            } ?>

            <!-- Main Details Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-info-circle"></i> Giving Type Information</h3>
                    <div class="box-tools pull-right">
                        <?php if ($this->rbac->hasPrivilege('partners', 'can_edit')): ?>
                        <a href="<?php echo base_url(); ?>admin/givingtypes/edit/<?php echo $giving_type->id; ?>" class="btn btn-primary btn-sm">
                            <i class="fa fa-pencil"></i> Edit
                        </a>
                        <?php endif; ?>
                        <a href="<?php echo base_url(); ?>admin/givingtypes" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th style="width: 200px;">Name</th>
                                <td><strong><?php echo htmlspecialchars($giving_type->name); ?></strong></td>
                            </tr>
                            <tr>
                                <th>Code</th>
                                <td>
                                    <?php if ($giving_type->code): ?>
                                        <span class="label label-info"><?php echo htmlspecialchars($giving_type->code); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">No code assigned</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>
                                    <?php if ($giving_type->description): ?>
                                        <?php echo nl2br(htmlspecialchars($giving_type->description)); ?>
                                    <?php else: ?>
                                        <span class="text-muted">No description provided</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <?php if ($giving_type->is_active): ?>
                                        <span class="label label-success"><i class="fa fa-check-circle"></i> Active</span>
                                        <small class="text-muted"> - Available for selection</small>
                                    <?php else: ?>
                                        <span class="label label-danger"><i class="fa fa-times-circle"></i> Inactive</span>
                                        <small class="text-muted"> - Not available for new partners</small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Sort Order</th>
                                <td>
                                    <span class="badge bg-gray"><?php echo $giving_type->sort_order; ?></span>
                                    <small class="text-muted"> - Lower numbers appear first in lists</small>
                                </td>
                            </tr>
                            <tr>
                                <th>Created</th>
                                <td>
                                    <i class="fa fa-calendar"></i>
                                    <?php echo date('F j, Y', strtotime($giving_type->created_at)); ?>
                                    <small class="text-muted">at <?php echo date('g:i A', strtotime($giving_type->created_at)); ?></small>
                                </td>
                            </tr>
                            <?php if ($giving_type->updated_at && $giving_type->updated_at != '0000-00-00 00:00:00'): ?>
                            <tr>
                                <th>Last Updated</th>
                                <td>
                                    <i class="fa fa-calendar"></i>
                                    <?php echo date('F j, Y', strtotime($giving_type->updated_at)); ?>
                                    <small class="text-muted">at <?php echo date('g:i A', strtotime($giving_type->updated_at)); ?></small>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    <?php if ($this->rbac->hasPrivilege('partners', 'can_edit')): ?>
                    <a href="<?php echo base_url(); ?>admin/givingtypes/edit/<?php echo $giving_type->id; ?>" class="btn btn-primary">
                        <i class="fa fa-pencil"></i> Edit Giving Type
                    </a>
                    <?php endif; ?>
                    <?php if ($this->rbac->hasPrivilege('partners', 'can_delete') && $usage_count == 0): ?>
                    <a href="<?php echo base_url(); ?>admin/givingtypes/delete/<?php echo $giving_type->id; ?>"
                       class="btn btn-danger"
                       onclick="return confirm('Are you sure you want to delete this giving type? This action cannot be undone.')">
                        <i class="fa fa-trash"></i> Delete Giving Type
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Partners Using This Type -->
            <?php if (!empty($partners)): ?>
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"></i> Partners Using This Giving Type</h3>
                    <span class="pull-right badge bg-green"><?php echo count($partners); ?> Partner<?php echo count($partners) != 1 ? 's' : ''; ?></span>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Partner Code</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($partners as $partner): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($partner->partner_code); ?></strong></td>
                                    <td><?php echo htmlspecialchars($partner->firstname . ' ' . $partner->lastname); ?></td>
                                    <td><?php echo htmlspecialchars($partner->email); ?></td>
                                    <td>
                                        <span class="label label-<?php echo $partner->status == 'active' ? 'success' : ($partner->status == 'inactive' ? 'warning' : 'danger'); ?>">
                                            <?php echo ucfirst($partner->status); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url(); ?>admin/partners/show/<?php echo $partner->id; ?>" class="btn btn-xs btn-info">
                                            <i class="fa fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Statistics Sidebar -->
        <div class="col-md-4">
            <!-- Usage Statistics -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-bar-chart"></i> Usage Statistics</h3>
                </div>
                <div class="box-body">
                    <div class="info-box bg-aqua">
                        <span class="info-box-icon"><i class="fa fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Partners</span>
                            <span class="info-box-number"><?php echo $usage_count; ?></span>
                        </div>
                    </div>

                    <?php if ($usage_count > 0): ?>
                    <div class="callout callout-warning">
                        <h4><i class="fa fa-info-circle"></i> Notice</h4>
                        <p>This giving type is currently in use and cannot be deleted. You can deactivate it to prevent new assignments.</p>
                    </div>
                    <?php else: ?>
                    <div class="callout callout-success">
                        <h4><i class="fa fa-check-circle"></i> Status</h4>
                        <p>This giving type is not currently being used and can be safely deleted if needed.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-bolt"></i> Quick Actions</h3>
                </div>
                <div class="box-body">
                    <?php if ($this->rbac->hasPrivilege('partners', 'can_edit')): ?>
                    <a href="<?php echo base_url(); ?>admin/givingtypes/edit/<?php echo $giving_type->id; ?>" class="btn btn-primary btn-block">
                        <i class="fa fa-pencil"></i> Edit This Type
                    </a>
                    <?php endif; ?>

                    <?php if ($this->rbac->hasPrivilege('partners', 'can_add')): ?>
                    <a href="<?php echo base_url(); ?>admin/givingtypes/add" class="btn btn-success btn-block">
                        <i class="fa fa-plus"></i> Add New Type
                    </a>
                    <?php endif; ?>

                    <a href="<?php echo base_url(); ?>admin/givingtypes" class="btn btn-default btn-block">
                        <i class="fa fa-list"></i> View All Types
                    </a>

                    <?php if ($this->rbac->hasPrivilege('partners', 'can_view')): ?>
                    <a href="<?php echo base_url(); ?>admin/partners" class="btn btn-info btn-block">
                        <i class="fa fa-handshake-o"></i> View All Partners
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>

<script type="text/javascript">
$(document).ready(function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
