<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <i class="fa fa-bell"></i> Reminder Settings
        <small>Manage reminder templates for partners</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url(); ?>admin/partners">Partners</a></li>
        <li><a href="<?php echo base_url(); ?>admin/partner_settings">Settings</a></li>
        <li class="active">Reminder Settings</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-bell"></i> Reminder Templates Management</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addReminderModal">
                            <i class="fa fa-plus"></i> Add New Template
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="remindersTable">
                            <thead>
                                <tr>
                                    <th>Template Name</th>
                                    <th>Type</th>
                                    <th>Timing</th>
                                    <th>Days</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reminder_templates as $template): ?>
                                <tr>
                                    <td><?php echo $template->template_name; ?></td>
                                    <td>
                                        <span class="label label-<?php 
                                            echo $template->reminder_type == 'contribution' ? 'primary' : 
                                                ($template->reminder_type == 'follow_up' ? 'info' : 
                                                ($template->reminder_type == 'renewal' ? 'success' : 'warning')); 
                                        ?>">
                                            <?php echo ucfirst($template->reminder_type); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="label label-<?php echo $template->timing == 'before' ? 'warning' : 'danger'; ?>">
                                            <?php echo ucfirst($template->timing); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($template->timing == 'before'): ?>
                                            <span class="text-warning"><?php echo $template->days_before; ?> days</span>
                                        <?php else: ?>
                                            <span class="text-danger"><?php echo $template->days_after; ?> days</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $template->subject; ?></td>
                                    <td>
                                        <?php if ($template->is_active): ?>
                                            <span class="label label-success">Active</span>
                                        <?php else: ?>
                                            <span class="label label-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs" onclick="editReminder(<?php echo $template->id; ?>)">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-info btn-xs" onclick="previewReminder(<?php echo $template->id; ?>)">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-<?php echo $template->is_active ? 'warning' : 'success'; ?> btn-xs" onclick="toggleStatus(<?php echo $template->id; ?>, 'reminder_template')">
                                                <i class="fa fa-<?php echo $template->is_active ? 'pause' : 'play'; ?>"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-xs" onclick="deleteReminder(<?php echo $template->id; ?>)">
                                                <i class="fa fa-trash"></i>
                                            </button>
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

    <!-- Reminder Types Information -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-info-circle"></i> Reminder Types Information</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="fa fa-gift text-primary"></i> Contribution Reminders</h5>
                            <p class="text-muted">Automated reminders for upcoming or overdue contributions. Can be set to trigger before or after due dates.</p>
                            
                            <h5><i class="fa fa-phone text-info"></i> Follow Up Reminders</h5>
                            <p class="text-muted">General follow-up messages to maintain communication with partners and check on their status.</p>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fa fa-refresh text-success"></i> Renewal Reminders</h5>
                            <p class="text-muted">Notifications about partnership renewals and contract expirations.</p>
                            
                            <h5><i class="fa fa-ellipsis-h text-warning"></i> Other Reminders</h5>
                            <p class="text-muted">Custom reminders for specific events, meetings, or special occasions.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add/Edit Reminder Modal -->
<div class="modal fade" id="addReminderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalTitle">Add New Reminder Template</h4>
            </div>
            <form id="reminderForm">
                <div class="modal-body">
                    <input type="hidden" id="reminderId" name="id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="templateName">Template Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="templateName" name="template_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reminderType">Reminder Type <span class="text-danger">*</span></label>
                                <select class="form-control" id="reminderType" name="reminder_type" required>
                                    <option value="">Select Type</option>
                                    <option value="contribution">Contribution</option>
                                    <option value="follow_up">Follow Up</option>
                                    <option value="renewal">Renewal</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="timing">Timing <span class="text-danger">*</span></label>
                                <select class="form-control" id="timing" name="timing" required onchange="toggleDaysInput()">
                                    <option value="">Select Timing</option>
                                    <option value="before">Before Due Date</option>
                                    <option value="after">After Due Date</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="daysInput" id="daysLabel">Days Before</label>
                                <input type="number" class="form-control" id="daysInput" name="days_before" min="1" max="365">
                                <input type="hidden" id="daysAfterInput" name="days_after">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="message" name="message" rows="8" required></textarea>
                        <small class="help-block">Use placeholders: {partner_name}, {amount}, {due_date}, {school_name}</small>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="reminderActive" name="is_active" checked> Active
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

<!-- Preview Reminder Modal -->
<div class="modal fade" id="previewReminderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Reminder Preview</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 id="previewSubject">Subject</h4>
                    </div>
                    <div class="panel-body">
                        <div id="previewMessage" style="white-space: pre-line;">Message content</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    // Initialize DataTable
    $('#remindersTable').DataTable({
        "responsive": true,
        "pageLength": 25,
        "order": [[ 0, "asc" ]]
    });

    // Form submission
    $('#reminderForm').on('submit', function(e) {
        e.preventDefault();
        
        // Set days_after if timing is 'after'
        if ($('#timing').val() === 'after') {
            $('#daysAfterInput').val($('#daysInput').val());
            $('#daysInput').val('');
        }
        
        var formData = $(this).serialize();
        var url = '<?php echo base_url(); ?>admin/partner_settings/save_reminder_template';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    showAlert('success', response.message);
                    $('#addReminderModal').modal('hide');
                    location.reload();
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function() {
                showAlert('error', 'An error occurred while saving the reminder template.');
            }
        });
    });

    // Reset form when modal is closed
    $('#addReminderModal').on('hidden.bs.modal', function() {
        $('#reminderForm')[0].reset();
        $('#reminderId').val('');
        $('#modalTitle').text('Add New Reminder Template');
        $('#reminderActive').prop('checked', true);
        $('#daysLabel').text('Days Before');
        $('#daysInput').attr('name', 'days_before');
    });
});

function toggleDaysInput() {
    var timing = $('#timing').val();
    if (timing === 'before') {
        $('#daysLabel').text('Days Before');
        $('#daysInput').attr('name', 'days_before');
    } else if (timing === 'after') {
        $('#daysLabel').text('Days After');
        $('#daysInput').attr('name', 'days_after');
    }
}

function editReminder(id) {
    // Get reminder data via AJAX
    $.ajax({
        url: '<?php echo base_url(); ?>admin/partner_settings/get_settings_data',
        type: 'GET',
        data: { type: 'reminder_templates' },
        dataType: 'json',
        success: function(templates) {
            var template = templates.find(t => t.id == id);
            if (template) {
                $('#reminderId').val(template.id);
                $('#templateName').val(template.template_name);
                $('#reminderType').val(template.reminder_type);
                $('#timing').val(template.timing);
                $('#subject').val(template.subject);
                $('#message').val(template.message);
                $('#reminderActive').prop('checked', template.is_active == 1);
                
                if (template.timing === 'before') {
                    $('#daysInput').val(template.days_before);
                    $('#daysLabel').text('Days Before');
                    $('#daysInput').attr('name', 'days_before');
                } else {
                    $('#daysInput').val(template.days_after);
                    $('#daysLabel').text('Days After');
                    $('#daysInput').attr('name', 'days_after');
                }
                
                $('#modalTitle').text('Edit Reminder Template');
                $('#addReminderModal').modal('show');
            }
        }
    });
}

function previewReminder(id) {
    // Get reminder data via AJAX
    $.ajax({
        url: '<?php echo base_url(); ?>admin/partner_settings/get_settings_data',
        type: 'GET',
        data: { type: 'reminder_templates' },
        dataType: 'json',
        success: function(templates) {
            var template = templates.find(t => t.id == id);
            if (template) {
                // Replace placeholders with sample data
                var message = template.message
                    .replace(/{partner_name}/g, 'John Doe')
                    .replace(/{amount}/g, '$100.00')
                    .replace(/{due_date}/g, '2025-02-15')
                    .replace(/{school_name}/g, 'Rhema Zimbabwe School');
                
                $('#previewSubject').text(template.subject);
                $('#previewMessage').text(message);
                $('#previewReminderModal').modal('show');
            }
        }
    });
}

function deleteReminder(id) {
    if (confirm('Are you sure you want to delete this reminder template?')) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/partner_settings/delete_reminder_template',
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
