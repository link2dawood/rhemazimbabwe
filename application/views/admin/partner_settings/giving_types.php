<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <i class="fa fa-gift"></i> Giving Types Settings
        <small>Manage giving types for partners</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url(); ?>admin/partners">Partners</a></li>
        <li><a href="<?php echo base_url(); ?>admin/partner_settings">Settings</a></li>
        <li class="active">Giving Types</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-gift"></i> Giving Types Management</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addGivingTypeModal">
                            <i class="fa fa-plus"></i> Add New Type
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="givingTypesTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Usage</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($giving_types as $type): ?>
                                <tr>
                                    <td><?php echo $type->name; ?></td>
                                    <td><span class="label label-info"><?php echo $type->code; ?></span></td>
                                    <td><?php echo $type->description; ?></td>
                                    <td>
                                        <?php if ($type->is_active): ?>
                                            <span class="label label-success">Active</span>
                                        <?php else: ?>
                                            <span class="label label-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-blue"><?php echo $this->type_model->getUsageCount($type->id); ?> partners</span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs" onclick="editGivingType(<?php echo $type->id; ?>)">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-<?php echo $type->is_active ? 'warning' : 'success'; ?> btn-xs" onclick="toggleStatus(<?php echo $type->id; ?>, 'giving_type')">
                                                <i class="fa fa-<?php echo $type->is_active ? 'pause' : 'play'; ?>"></i>
                                            </button>
                                            <?php if ($this->type_model->getUsageCount($type->id) == 0): ?>
                                            <button type="button" class="btn btn-danger btn-xs" onclick="deleteGivingType(<?php echo $type->id; ?>)">
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
</section>

<!-- Add/Edit Giving Type Modal -->
<div class="modal fade" id="addGivingTypeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalTitle">Add New Giving Type</h4>
            </div>
            <form id="givingTypeForm">
                <div class="modal-body">
                    <input type="hidden" id="typeId" name="id">
                    
                    <div class="form-group">
                        <label for="typeName">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="typeName" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="typeCode">Code</label>
                        <input type="text" class="form-control" id="typeCode" name="code" placeholder="e.g., tuition, scholarship">
                    </div>
                    
                    <div class="form-group">
                        <label for="typeDescription">Description</label>
                        <textarea class="form-control" id="typeDescription" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="typeActive" name="is_active" checked> Active
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
    $('#givingTypesTable').DataTable({
        "responsive": true,
        "pageLength": 25,
        "order": [[ 0, "asc" ]]
    });

    // Form submission
    $('#givingTypeForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var url = '<?php echo base_url(); ?>admin/partner_settings/save_giving_type';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    showAlert('success', response.message);
                    $('#addGivingTypeModal').modal('hide');
                    location.reload();
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function() {
                showAlert('error', 'An error occurred while saving the giving type.');
            }
        });
    });

    // Reset form when modal is closed
    $('#addGivingTypeModal').on('hidden.bs.modal', function() {
        $('#givingTypeForm')[0].reset();
        $('#typeId').val('');
        $('#modalTitle').text('Add New Giving Type');
        $('#typeActive').prop('checked', true);
    });
});

function editGivingType(id) {
    // Get type data via AJAX
    $.ajax({
        url: '<?php echo base_url(); ?>admin/partner_settings/get_settings_data',
        type: 'GET',
        data: { type: 'giving_types' },
        dataType: 'json',
        success: function(types) {
            var type = types.find(t => t.id == id);
            if (type) {
                $('#typeId').val(type.id);
                $('#typeName').val(type.name);
                $('#typeCode').val(type.code);
                $('#typeDescription').val(type.description);
                $('#typeActive').prop('checked', type.is_active == 1);
                $('#modalTitle').text('Edit Giving Type');
                $('#addGivingTypeModal').modal('show');
            }
        }
    });
}

function deleteGivingType(id) {
    if (confirm('Are you sure you want to delete this giving type?')) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/partner_settings/delete_giving_type',
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
