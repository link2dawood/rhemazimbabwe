<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <i class="fa fa-key"></i> Permission Types Settings
        <small>Manage permission types for partners</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url(); ?>admin/partners">Partners</a></li>
        <li><a href="<?php echo base_url(); ?>admin/partner_settings">Settings</a></li>
        <li class="active">Permission Types</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-key"></i> Permission Types Management</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addPermissionModal">
                            <i class="fa fa-plus"></i> Add New Permission
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="permissionsTable">
                            <thead>
                                <tr>
                                    <th>Permission Name</th>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Usage</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($permission_types as $permission): ?>
                                <tr>
                                    <td><?php echo $permission->permission_name; ?></td>
                                    <td><span class="label label-info"><?php echo $permission->permission_code; ?></span></td>
                                    <td><?php echo $permission->description; ?></td>
                                    <td>
                                        <?php if ($permission->is_active): ?>
                                            <span class="label label-success">Active</span>
                                        <?php else: ?>
                                            <span class="label label-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-blue"><?php echo $this->permission_model->getPermissionUsageCount($permission->permission_code); ?> partners</span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs" onclick="editPermission(<?php echo $permission->id; ?>)">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-<?php echo $permission->is_active ? 'warning' : 'success'; ?> btn-xs" onclick="toggleStatus(<?php echo $permission->id; ?>, 'permission_type')">
                                                <i class="fa fa-<?php echo $permission->is_active ? 'pause' : 'play'; ?>"></i>
                                            </button>
                                            <?php if ($this->permission_model->getPermissionUsageCount($permission->permission_code) == 0): ?>
                                            <button type="button" class="btn btn-danger btn-xs" onclick="deletePermission(<?php echo $permission->id; ?>)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Permission Types Information -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-info-circle"></i> Permission Types Information</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="fa fa-book text-blue"></i> Library Access</h5>
                            <p class="text-muted">Allows partners to access the school library and borrow books.</p>
                            
                            <h5><i class="fa fa-graduation-cap text-green"></i> Online Courses</h5>
                            <p class="text-muted">Grants access to online learning platforms and course materials.</p>
                            
                            <h5><i class="fa fa-download text-orange"></i> Download Centre</h5>
                            <p class="text-muted">Enables downloading of school resources, documents, and materials.</p>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fa fa-video-camera text-red"></i> GMeet Access</h5>
                            <p class="text-muted">Allows participation in Google Meet sessions and virtual events.</p>
                            
                            <h5><i class="fa fa-users text-purple"></i> Zoom Access</h5>
                            <p class="text-muted">Grants access to Zoom meetings and webinars.</p>
                            
                            <h5><i class="fa fa-calendar text-teal"></i> Events Access</h5>
                            <p class="text-muted">Allows participation in school events and activities.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add/Edit Permission Modal -->
<div class="modal fade" id="addPermissionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalTitle">Add New Permission Type</h4>
            </div>
            <form id="permissionForm">
                <div class="modal-body">
                    <input type="hidden" id="permissionId" name="id">
                    
                    <div class="form-group">
                        <label for="permissionName">Permission Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="permissionName" name="permission_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="permissionCode">Permission Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="permissionCode" name="permission_code" required placeholder="e.g., library_access, online_courses">
                        <small class="help-block">Use lowercase with underscores (e.g., library_access, online_courses)</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="permissionDescription">Description</label>
                        <textarea class="form-control" id="permissionDescription" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="permissionActive" name="is_active" checked> Active
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    // Initialize DataTable
    $('#permissionsTable').DataTable({
        "responsive": true,
        "pageLength": 25,
        "order": [[ 0, "asc" ]]
    });

    // Form submission
    $('#permissionForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var url = '<?php echo base_url(); ?>admin/partner_settings/save_permission_type';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    showAlert('success', response.message);
                    $('#addPermissionModal').modal('hide');
                    location.reload();
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function() {
                showAlert('error', 'An error occurred while saving the permission type.');
            }
        });
    });

    // Reset form when modal is closed
    $('#addPermissionModal').on('hidden.bs.modal', function() {
        $('#permissionForm')[0].reset();
        $('#permissionId').val('');
        $('#modalTitle').text('Add New Permission Type');
        $('#permissionActive').prop('checked', true);
    });
});

function editPermission(id) {
    // Get permission data via AJAX
    $.ajax({
        url: '<?php echo base_url(); ?>admin/partner_settings/get_settings_data',
        type: 'GET',
        data: { type: 'permission_types' },
        dataType: 'json',
        success: function(permissions) {
            var permission = permissions.find(p => p.id == id);
            if (permission) {
                $('#permissionId').val(permission.id);
                $('#permissionName').val(permission.permission_name);
                $('#permissionCode').val(permission.permission_code);
                $('#permissionDescription').val(permission.description);
                $('#permissionActive').prop('checked', permission.is_active == 1);
                $('#modalTitle').text('Edit Permission Type');
                $('#addPermissionModal').modal('show');
            }
        }
    });
}

function deletePermission(id) {
    if (confirm('Are you sure you want to delete this permission type?')) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/partner_settings/delete_permission_type',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    showAlert('success', response.message);
                    location.reload();
                } else {
                    showAlert('error', response.message);
                }
            }
        });
    }
}

function toggleStatus(id, type) {
    $.ajax({
        url: '<?php echo base_url(); ?>admin/partner_settings/toggle_status',
        type: 'POST',
        data: { id: id, type: type },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                showAlert('success', response.message);
                location.reload();
            } else {
                showAlert('error', response.message);
            }
        }
    });
}

function showAlert(type, message) {
    var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    message +
                    '</div>';
    
    $('.content').prepend(alertHtml);
    
    // Auto-hide after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
