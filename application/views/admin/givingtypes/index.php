<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <h1>
        <i class="fa fa-gift"></i> Giving Types Management
        <small>Manage giving types for partners</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url(); ?>admin/partners"><i class="fa fa-handshake-o"></i> Partners</a></li>
        <li class="active">Giving Types</li>
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

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-list"></i> All Giving Types</h3>
                    <div class="box-tools pull-right">
                        <?php if ($this->rbac->hasPrivilege('partners', 'can_add')) { ?>
                        <a href="<?php echo base_url(); ?>admin/givingtypes/add" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Add New Giving Type
                        </a>
                        <?php } ?>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="givingTypesTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>Usage</th>
                                    <th>Status</th>
                                    <th>Sort Order</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($giving_types)): ?>
                                    <?php foreach ($giving_types as $type): ?>
                                        <tr data-id="<?php echo $type->id; ?>">
                                            <td><strong><?php echo htmlspecialchars($type->name); ?></strong></td>
                                            <td>
                                                <?php if ($type->code): ?>
                                                    <span class="label label-info"><?php echo htmlspecialchars($type->code); ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo substr(htmlspecialchars($type->description), 0, 80) . (strlen($type->description) > 80 ? '...' : ''); ?></td>
                                            <td>
                                                <span class="badge bg-blue">
                                                    <?php echo $type->partner_count; ?> partner<?php echo $type->partner_count != 1 ? 's' : ''; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($type->is_active): ?>
                                                    <span class="label label-success">Active</span>
                                                <?php else: ?>
                                                    <span class="label label-danger">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-gray"><?php echo $type->sort_order; ?></span>
                                            </td>
                                            <td class="text-right">
                                                <div class="btn-group">
                                                    <?php if ($this->rbac->hasPrivilege('partners', 'can_view')): ?>
                                                        <a href="<?php echo base_url(); ?>admin/givingtypes/show/<?php echo $type->id; ?>"
                                                           class="btn btn-default btn-xs"
                                                           data-toggle="tooltip"
                                                           title="View Details">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($this->rbac->hasPrivilege('partners', 'can_edit')): ?>
                                                        <a href="<?php echo base_url(); ?>admin/givingtypes/edit/<?php echo $type->id; ?>"
                                                           class="btn btn-primary btn-xs"
                                                           data-toggle="tooltip"
                                                           title="Edit">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($this->rbac->hasPrivilege('partners', 'can_delete') && $type->partner_count == 0): ?>
                                                        <a href="<?php echo base_url(); ?>admin/givingtypes/delete/<?php echo $type->id; ?>"
                                                           class="btn btn-danger btn-xs"
                                                           data-toggle="tooltip"
                                                           title="Delete"
                                                           onclick="return confirm('Are you sure you want to delete this giving type? This action cannot be undone.')">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            <i class="fa fa-info-circle"></i> No giving types found. Click "Add New Giving Type" to create one.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box-footer clearfix">
                    <div class="pull-left">
                        <p class="text-muted">
                            <i class="fa fa-info-circle"></i>
                            Showing <?php echo count($giving_types); ?> giving type<?php echo count($giving_types) != 1 ? 's' : ''; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>

<script type="text/javascript">
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#givingTypesTable').DataTable({
        "responsive": true,
        "pageLength": 25,
        "order": [[ 5, "asc" ]], // Sort by sort_order by default
        "columnDefs": [
            { "orderable": false, "targets": 6 } // Disable sorting on actions column
        ],
        "language": {
            "emptyTable": "No giving types found",
            "zeroRecords": "No matching giving types found",
            "search": "Search:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "Showing 0 to 0 of 0 entries",
            "infoFiltered": "(filtered from _TOTAL_ total entries)",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            }
        }
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>

<style>
.table > tbody > tr > td {
    vertical-align: middle;
}
.btn-group {
    white-space: nowrap;
}
</style>
