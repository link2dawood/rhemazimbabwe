<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <i class="fa fa-clock-o"></i> Giving Frequencies
        <small>Manage giving frequencies for partners</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url(); ?>admin/partners">Partners</a></li>
        <li class="active">Giving Frequencies</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-clock-o"></i> Giving Frequencies</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addFrequencyModal">
                            <i class="fa fa-plus"></i> Add New Frequency
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="frequenciesTable">
                            <thead>
                                <tr>
                                    <th width="20%">Name</th>
                                    <th width="12%">Code</th>
                                    <th width="12%">Days Interval</th>
                                    <th width="25%">Description</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Usage</th>
                                    <th width="11%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($giving_frequencies)): ?>
                                    <?php foreach ($giving_frequencies as $frequency): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($frequency->name); ?></strong></td>
                                        <td><span class="label label-info"><?php echo htmlspecialchars($frequency->code); ?></span></td>
                                        <td>
                                            <?php if ($frequency->days_interval): ?>
                                                <span class="badge bg-blue"><?php echo $frequency->days_interval; ?> days</span>
                                            <?php else: ?>
                                                <span class="text-muted">Once-off</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($frequency->description); ?></td>
                                        <td>
                                            <?php if ($frequency->is_active): ?>
                                                <span class="label label-success">Active</span>
                                            <?php else: ?>
                                                <span class="label label-danger">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-green"><?php echo $this->frequency_model->getUsageCount($frequency->id); ?> partners</span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default btn-xs" onclick="editFrequency(<?php echo $frequency->id; ?>)" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-<?php echo $frequency->is_active ? 'warning' : 'success'; ?> btn-xs" 
                                                        onclick="toggleStatus(<?php echo $frequency->id; ?>)" 
                                                        title="<?php echo $frequency->is_active ? 'Deactivate' : 'Activate'; ?>">
                                                    <i class="fa fa-<?php echo $frequency->is_active ? 'pause' : 'play'; ?>"></i>
                                                </button>
                                                <?php if ($this->frequency_model->getUsageCount($frequency->id) == 0): ?>
                                                <button type="button" class="btn btn-danger btn-xs" onclick="deleteFrequency(<?php echo $frequency->id; ?>)" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No giving frequencies found. Click "Add New Frequency" to create one.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add/Edit Frequency Modal -->
<div class="modal fade" id="addFrequencyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalTitle">Add New Giving Frequency</h4>
            </div>
            <form id="frequencyForm">
                <div class="modal-body">
                    <input type="hidden" id="frequencyId" name="id">
                    
                    <div class="form-group">
                        <label for="frequencyName">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="frequencyName" name="name" required>
                        <small class="help-block">e.g., Weekly, Monthly, Quarterly</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="frequencyCode">Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="frequencyCode" name="code" required placeholder="e.g., weekly, monthly">
                        <small class="help-block">Lowercase, no spaces (used in system)</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="frequencyInterval">Days Interval</label>
                        <input type="number" class="form-control" id="frequencyInterval" name="days_interval" min="0" placeholder="0">
                        <small class="help-block">Number of days between contributions. Use 0 or leave empty for one-time contributions.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="frequencyDescription">Description</label>
                        <textarea class="form-control" id="frequencyDescription" name="description" rows="3" placeholder="Brief description of this frequency"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="sortOrder">Sort Order</label>
                        <input type="number" class="form-control" id="sortOrder" name="sort_order" min="0" value="0">
                        <small class="help-block">Order in which this appears in dropdowns (lower = first)</small>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="frequencyActive" name="is_active" checked> Active
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Save Frequency
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    // Initialize DataTable
    $('#frequenciesTable').DataTable({
        "responsive": true,
        "pageLength": 25,
        "order": [[ 5, "asc" ]], // Sort by sort_order
        "columnDefs": [
            { "orderable": false, "targets": 6 } // Disable sorting on Actions column
        ]
    });

    // Form submission
    $('#frequencyForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var url = '<?php echo base_url(); ?>admin/givingfrequencies/save';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    showAlert('success', response.message);
                    $('#addFrequencyModal').modal('hide');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function() {
                showAlert('error', 'An error occurred while saving the giving frequency.');
            }
        });
    });

    // Reset form when modal is closed
    $('#addFrequencyModal').on('hidden.bs.modal', function() {
        $('#frequencyForm')[0].reset();
        $('#frequencyId').val('');
        $('#modalTitle').text('Add New Giving Frequency');
        $('#frequencyActive').prop('checked', true);
        $('#sortOrder').val('0');
    });
});

function editFrequency(id) {
    // Get frequency data via AJAX
    $.ajax({
        url: '<?php echo base_url(); ?>admin/givingfrequencies/get',
        type: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success' && response.data) {
                var frequency = response.data;
                $('#frequencyId').val(frequency.id);
                $('#frequencyName').val(frequency.name);
                $('#frequencyCode').val(frequency.code);
                $('#frequencyInterval').val(frequency.days_interval || '');
                $('#frequencyDescription').val(frequency.description);
                $('#sortOrder').val(frequency.sort_order || 0);
                $('#frequencyActive').prop('checked', frequency.is_active == 1);
                $('#modalTitle').text('Edit Giving Frequency');
                $('#addFrequencyModal').modal('show');
            } else {
                showAlert('error', 'Failed to load frequency data');
            }
        },
        error: function() {
            showAlert('error', 'An error occurred while loading frequency data.');
        }
    });
}

function deleteFrequency(id) {
    if (confirm('Are you sure you want to delete this giving frequency?\n\nThis action cannot be undone.')) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/givingfrequencies/delete',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    showAlert('success', response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function() {
                showAlert('error', 'An error occurred while deleting the frequency.');
            }
        });
    }
}

function toggleStatus(id) {
    $.ajax({
        url: '<?php echo base_url(); ?>admin/givingfrequencies/toggle_status',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                showAlert('success', response.message);
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                showAlert('error', response.message);
            }
        },
        error: function() {
            showAlert('error', 'An error occurred while toggling status.');
        }
    });
}

function showAlert(type, message) {
    var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    var iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
    var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    '<i class="fa ' + iconClass + '"></i> ' + message +
                    '</div>';
    
    $('.content').prepend(alertHtml);
    
    // Auto-hide after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}
</script>

