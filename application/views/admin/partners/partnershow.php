<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-users"></i> <?php echo $this->lang->line('partner_details'); ?></h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="<?php echo isset($partner->photo) && $partner->photo ? base_url().$partner->photo : base_url().'uploads/partner_images/default.png' ?>" alt="Partner">
                        <h3 class="profile-username text-center"><?php echo $partner->firstname . ' ' . $partner->lastname ?></h3>
                        <p class="text-muted text-center"><?php echo $partner->partner_code ?></p>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b><?php echo $this->lang->line('status'); ?></b>
                                <span class="pull-right label label-<?php echo $partner->status == 'active' ? 'success' : ($partner->status == 'inactive' ? 'warning' : 'danger') ?>">
                                    <?php echo ucfirst($partner->status) ?>
                                </span>
                            </li>
                            <li class="list-group-item">
                                <b><?php echo $this->lang->line('giving_type'); ?></b>
                                <span class="pull-right"><?php echo $partner->type_name ? $partner->type_name : '-' ?></span>
                            </li>
                            <li class="list-group-item">
                                <b><?php echo $this->lang->line('frequency'); ?></b>
                                <span class="pull-right"><?php echo $partner->frequency_name ? $partner->frequency_name : '-' ?></span>
                            </li>
                            <li class="list-group-item">
                                <b><?php echo $this->lang->line('total_contributed'); ?></b>
                                <span class="pull-right"><?php echo $currency_symbol . ' ' . number_format($total_contributions, 2) ?></span>
                            </li>
                        </ul>

                        <?php if ($this->rbac->hasPrivilege('partners', 'can_edit')) { ?>
                            <a href="<?php echo base_url() ?>admin/partners/edit/<?php echo $partner->id ?>" class="btn btn-primary btn-block">
                                <i class="fa fa-edit"></i> <?php echo $this->lang->line('edit'); ?>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#profile" data-toggle="tab"><?php echo $this->lang->line('profile'); ?></a></li>
                        <li><a href="#contributions" data-toggle="tab"><?php echo $this->lang->line('contributions'); ?></a></li>
                        <li><a href="#permissions" data-toggle="tab"><?php echo $this->lang->line('permissions'); ?></a></li>
                        <li><a href="#notes" data-toggle="tab"><?php echo $this->lang->line('notes'); ?></a></li>
                        <li><a href="#reminders" data-toggle="tab"><?php echo $this->lang->line('reminders'); ?></a></li>
                    </ul>

                    <div class="tab-content">
                        <!-- Profile Tab -->
                        <div class="active tab-pane" id="profile">
                            <?php if ($this->session->flashdata('msg')) {
                                echo $this->session->flashdata('msg');
                            } ?>

                            <h4><?php echo $this->lang->line('personal_information'); ?></h4>
                            <table class="table table-striped">
                                <tr>
                                    <td class="col-md-4"><strong><?php echo $this->lang->line('partner_code'); ?></strong></td>
                                    <td><?php echo $partner->partner_code ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo $this->lang->line('name'); ?></strong></td>
                                    <td><?php echo $partner->firstname . ' ' . $partner->lastname ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo $this->lang->line('email'); ?></strong></td>
                                    <td><?php echo $partner->email ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo $this->lang->line('phone'); ?></strong></td>
                                    <td><?php echo $partner->mobileno ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo $this->lang->line('address'); ?></strong></td>
                                    <td><?php echo $partner->address ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo $this->lang->line('city'); ?></strong></td>
                                    <td>
                                        <?php
                                            // Build city/state/country string safely to avoid undefined property warnings
                                            $city_parts = array();
                                            if (!empty($partner->city)) {
                                                $city_parts[] = $partner->city;
                                            }
                                            if (!empty($partner->state)) {
                                                $city_parts[] = $partner->state;
                                            }
                                            if (!empty($partner->country)) {
                                                $city_parts[] = $partner->country;
                                            }
                                            $city_str = !empty($city_parts) ? implode(', ', $city_parts) : '-';
                                            if (!empty($partner->zip_code)) {
                                                $city_str .= ' ' . $partner->zip_code;
                                            }
                                            echo $city_str;
                                        ?>
                                    </td>
                                </tr>
                            </table>

                            <h4><?php echo $this->lang->line('giving_information'); ?></h4>
                            <table class="table table-striped">
                                <tr>
                                    <td class="col-md-4"><strong><?php echo $this->lang->line('giving_type'); ?></strong></td>
                                    <td><?php echo $partner->type_name ? $partner->type_name : '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo $this->lang->line('giving_frequency'); ?></strong></td>
                                    <td><?php echo $partner->frequency_name ? $partner->frequency_name : '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo $this->lang->line('contribution_amount'); ?></strong></td>
                                    <td><?php echo $partner->currency . ' ' . number_format($partner->contribution_amount, 2) ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo $this->lang->line('start_date'); ?></strong></td>
                                    <td><?php echo $partner->start_date ? date($this->customlib->getSchoolDateFormat(), strtotime($partner->start_date)) : '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo $this->lang->line('end_date'); ?></strong></td>
                                    <td><?php echo $partner->end_date ? date($this->customlib->getSchoolDateFormat(), strtotime($partner->end_date)) : '-' ?></td>
                                </tr>
                                <?php if ($partner->student_id) { ?>
                                <tr>
                                    <td><strong><?php echo $this->lang->line('linked_student'); ?></strong></td>
                                    <td><?php echo $partner->student_firstname . ' ' . $partner->student_lastname . ' (' . $partner->admission_no . ')' ?></td>
                                </tr>
                                <?php } ?>
                            </table>

                            <?php if ($partner->notes) { ?>
                            <h4><?php echo $this->lang->line('notes'); ?></h4>
                            <p><?php echo nl2br($partner->notes) ?></p>
                            <?php } ?>
                        </div>

                        <!-- Contributions Tab -->
                        <div class="tab-pane" id="contributions">
                            <div class="box-tools">
                                <a href="<?php echo base_url() ?>admin/partnercontributions/add/<?php echo $partner->id ?>" class="btn btn-primary btn-sm pull-right">
                                    <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_contribution'); ?>
                                </a>
                            </div>
                            <div class="clearfix"></div>
                            <br>

                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('amount'); ?></th>
                                        <th><?php echo $this->lang->line('payment_method'); ?></th>
                                        <th><?php echo $this->lang->line('receipt_no'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($contributions)) {
                                        foreach ($contributions as $contribution) { ?>
                                        <tr>
                                            <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($contribution->contribution_date)) ?></td>
                                            <td><?php echo $contribution->currency . ' ' . number_format($contribution->amount, 2) ?></td>
                                            <td><?php echo ucfirst(str_replace('_', ' ', $contribution->payment_method)) ?></td>
                                            <td><?php echo $contribution->receipt_no ?></td>
                                            <td><span class="label label-<?php echo $contribution->status == 'completed' ? 'success' : 'warning' ?>"><?php echo ucfirst($contribution->status) ?></span></td>
                                            <td>
                                                <a href="<?php echo base_url() ?>admin/partnercontributions/show/<?php echo $contribution->id ?>" class="btn btn-xs btn-default">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="6" class="text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>

                            <div class="text-center">
                                <a href="<?php echo base_url() ?>admin/partnercontributions?partner_id=<?php echo $partner->id ?>" class="btn btn-sm btn-info">
                                    <?php echo $this->lang->line('view_all_contributions'); ?>
                                </a>
                            </div>
                        </div>

                        <!-- Permissions Tab -->
                        <div class="tab-pane" id="permissions">
                            <div class="box-tools">
                                <button class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#permissionModal">
                                    <i class="fa fa-key"></i> <?php echo $this->lang->line('manage_permissions'); ?>
                                </button>
                            </div>
                            <div class="clearfix"></div>
                            <br>

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('permission'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th><?php echo $this->lang->line('granted_date'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($permissions)) {
                                        foreach ($permissions as $permission) { ?>
                                        <tr>
                                            <td><?php echo $permission->permission_name ?></td>
                                            <td><span class="label label-success"><?php echo $this->lang->line('granted'); ?></span></td>
                                            <td><?php echo $permission->granted_at ? date($this->customlib->getSchoolDateFormat(), strtotime($permission->granted_at)) : '-' ?></td>
                                        </tr>
                                    <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="3" class="text-center"><?php echo $this->lang->line('no_permissions_granted'); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Notes Tab -->
                        <div class="tab-pane" id="notes">
                            <div class="box-tools">
                                <button class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#noteModal">
                                    <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_note'); ?>
                                </button>
                            </div>
                            <div class="clearfix"></div>
                            <br>

                            <?php if (!empty($notes)) {
                                foreach ($notes as $note) { ?>
                                <div class="post <?php echo $note->is_pinned ? 'box box-warning' : '' ?>">
                                    <div class="user-block">
                                        <span class="username">
                                            <?php if ($note->is_pinned) { ?><i class="fa fa-thumb-tack text-warning"></i><?php } ?>
                                            <?php echo $note->created_by_name ?>
                                            <span class="pull-right">
                                                <span class="label label-<?php echo $note->priority == 'urgent' ? 'danger' : ($note->priority == 'high' ? 'warning' : 'info') ?>">
                                                    <?php echo ucfirst($note->priority) ?>
                                                </span>
                                                <div class="btn-group btn-group-xs" style="margin-left: 10px;">
                                                    <button type="button" class="btn btn-info btn-xs edit-note" 
                                                            data-note-id="<?php echo $note->id; ?>"
                                                            data-title="<?php echo htmlspecialchars($note->title); ?>"
                                                            data-note="<?php echo htmlspecialchars($note->note); ?>"
                                                            data-priority="<?php echo $note->priority; ?>"
                                                            data-pinned="<?php echo $note->is_pinned; ?>"
                                                            title="Edit Note">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-xs delete-note" 
                                                            data-note-id="<?php echo $note->id; ?>"
                                                            title="Delete Note">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </span>
                                        </span>
                                        <span class="description"><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($note->created_at)) ?></span>
                                    </div>
                                    <?php if ($note->title) { ?>
                                        <h4><?php echo $note->title ?></h4>
                                    <?php } ?>
                                    <p><?php echo nl2br($note->note) ?></p>
                                </div>
                            <?php }
                            } else { ?>
                                <p class="text-center text-muted"><?php echo $this->lang->line('no_notes_found'); ?></p>
                            <?php } ?>
                        </div>

                        <!-- Reminders Tab -->
                        <div class="tab-pane" id="reminders">
                            <div class="box-tools">
                                <button class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#reminderModal">
                                    <i class="fa fa-bell"></i> <?php echo $this->lang->line('add_reminder'); ?>
                                </button>
                            </div>
                            <div class="clearfix"></div>
                            <br>

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('time'); ?></th>
                                        <th><?php echo $this->lang->line('type'); ?></th>
                                        <th><?php echo $this->lang->line('message'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th><?php echo $this->lang->line('actions'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($reminders)) {
                                        foreach ($reminders as $reminder) { ?>
                                        <tr>
                                            <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($reminder->reminder_date)) ?></td>
                                            <td><?php echo $reminder->reminder_time ?></td>
                                            <td><?php echo ucfirst(str_replace('_', ' ', $reminder->reminder_type)) ?></td>
                                            <td><?php echo substr($reminder->message, 0, 50) . (strlen($reminder->message) > 50 ? '...' : '') ?></td>
                                            <td>
                                                <span class="label label-<?php echo $reminder->is_active ? 'success' : 'default' ?>">
                                                    <?php echo $reminder->is_active ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-xs">
                                                    <button type="button" class="btn btn-info btn-xs edit-reminder" 
                                                            data-reminder-id="<?php echo $reminder->id; ?>"
                                                            data-type="<?php echo $reminder->reminder_type; ?>"
                                                            data-date="<?php echo $reminder->reminder_date; ?>"
                                                            data-time="<?php echo $reminder->reminder_time; ?>"
                                                            data-message="<?php echo htmlspecialchars($reminder->message); ?>"
                                                            data-active="<?php echo $reminder->is_active; ?>"
                                                            title="Edit Reminder">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-<?php echo $reminder->is_active ? 'warning' : 'success'; ?> btn-xs toggle-reminder" 
                                                            data-reminder-id="<?php echo $reminder->id; ?>"
                                                            data-active="<?php echo $reminder->is_active; ?>"
                                                            title="<?php echo $reminder->is_active ? 'Deactivate' : 'Activate'; ?>">
                                                        <i class="fa fa-<?php echo $reminder->is_active ? 'pause' : 'play'; ?>"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-xs delete-reminder" 
                                                            data-reminder-id="<?php echo $reminder->id; ?>"
                                                            title="Delete Reminder">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="6" class="text-center"><?php echo $this->lang->line('no_reminders_found'); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Add Note Modal -->
<div class="modal fade" id="noteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_note'); ?></h4>
            </div>
            <form id="noteForm" method="post">
                <div class="modal-body">
                    <input type="hidden" name="partner_id" value="<?php echo $partner->id; ?>">
                    <input type="hidden" name="note_id" id="note_id">
                    
                    <div class="form-group">
                        <label><?php echo $this->lang->line('title'); ?></label>
                        <input type="text" name="title" id="note_title" class="form-control" placeholder="Enter note title">
                    </div>
                    
                    <div class="form-group">
                        <label><?php echo $this->lang->line('note'); ?></label>
                        <textarea name="note" id="note_content" class="form-control" rows="4" placeholder="Enter note content"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('priority'); ?></label>
                                <select name="priority" id="note_priority" class="form-control">
                                    <option value="low">Low</option>
                                    <option value="normal" selected>Normal</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="is_pinned" id="note_pinned" value="1"> 
                                    <?php echo $this->lang->line('pin_note'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Reminder Modal -->
<div class="modal fade" id="reminderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_reminder'); ?></h4>
            </div>
            <form id="reminderForm" method="post">
                <div class="modal-body">
                    <input type="hidden" name="partner_id" value="<?php echo $partner->id; ?>">
                    <input type="hidden" name="reminder_id" id="reminder_id">
                    
                    <div class="form-group">
                        <label><?php echo $this->lang->line('reminder_type'); ?></label>
                        <select name="reminder_type" id="reminder_type" class="form-control">
                            <option value="payment_due">Payment Due</option>
                            <option value="follow_up">Follow Up</option>
                            <option value="meeting">Meeting</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('reminder_date'); ?></label>
                                <input type="date" name="reminder_date" id="reminder_date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('reminder_time'); ?></label>
                                <input type="time" name="reminder_time" id="reminder_time" class="form-control" value="09:00">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label><?php echo $this->lang->line('message'); ?></label>
                        <textarea name="message" id="reminder_message" class="form-control" rows="3" placeholder="Enter reminder message"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_active" id="reminder_active" value="1" checked> 
                            <?php echo $this->lang->line('active'); ?>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Note form submission
    $('#noteForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var url = '<?php echo base_url("admin/partners/add_note"); ?>';
        
        if ($('#note_id').val()) {
            url = '<?php echo base_url("admin/partners/update_note"); ?>';
        }
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while saving the note.');
            }
        });
    });
    
    // Reminder form submission
    $('#reminderForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var url = '<?php echo base_url("admin/partners/add_reminder"); ?>';
        
        if ($('#reminder_id').val()) {
            url = '<?php echo base_url("admin/partners/update_reminder"); ?>';
        }
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while saving the reminder.');
            }
        });
    });
    
    // Edit note
    $(document).on('click', '.edit-note', function() {
        var noteId = $(this).data('note-id');
        var title = $(this).data('title');
        var note = $(this).data('note');
        var priority = $(this).data('priority');
        var pinned = $(this).data('pinned');
        
        $('#note_id').val(noteId);
        $('#note_title').val(title);
        $('#note_content').val(note);
        $('#note_priority').val(priority);
        $('#note_pinned').prop('checked', pinned == 1);
        
        $('#noteModal .modal-title').text('<?php echo $this->lang->line('edit_note'); ?>');
        $('#noteModal').modal('show');
    });
    
    // Edit reminder
    $(document).on('click', '.edit-reminder', function() {
        var reminderId = $(this).data('reminder-id');
        var type = $(this).data('type');
        var date = $(this).data('date');
        var time = $(this).data('time');
        var message = $(this).data('message');
        var active = $(this).data('active');
        
        $('#reminder_id').val(reminderId);
        $('#reminder_type').val(type);
        $('#reminder_date').val(date);
        $('#reminder_time').val(time);
        $('#reminder_message').val(message);
        $('#reminder_active').prop('checked', active == 1);
        
        $('#reminderModal .modal-title').text('<?php echo $this->lang->line('edit_reminder'); ?>');
        $('#reminderModal').modal('show');
    });
    
    // Delete note
    $(document).on('click', '.delete-note', function() {
        if (confirm('<?php echo $this->lang->line('confirm_delete_note'); ?>')) {
            var noteId = $(this).data('note-id');
            
            $.ajax({
                url: '<?php echo base_url("admin/partners/delete_note"); ?>',
                type: 'POST',
                data: {note_id: noteId},
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while deleting the note.');
                }
            });
        }
    });
    
    // Delete reminder
    $(document).on('click', '.delete-reminder', function() {
        if (confirm('<?php echo $this->lang->line('confirm_delete_reminder'); ?>')) {
            var reminderId = $(this).data('reminder-id');
            
            $.ajax({
                url: '<?php echo base_url("admin/partners/delete_reminder"); ?>',
                type: 'POST',
                data: {reminder_id: reminderId},
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while deleting the reminder.');
                }
            });
        }
    });
    
    // Toggle reminder status
    $(document).on('click', '.toggle-reminder', function() {
        var reminderId = $(this).data('reminder-id');
        var isActive = $(this).data('active') ? 0 : 1;
        
        $.ajax({
            url: '<?php echo base_url("admin/partners/toggle_reminder_status"); ?>',
            type: 'POST',
            data: {reminder_id: reminderId, is_active: isActive},
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while updating the reminder.');
            }
        });
    });
    
    // Reset forms when modals are closed
    $('#noteModal').on('hidden.bs.modal', function() {
        $('#noteForm')[0].reset();
        $('#note_id').val('');
        $('#noteModal .modal-title').text('<?php echo $this->lang->line('add_note'); ?>');
    });
    
    $('#reminderModal').on('hidden.bs.modal', function() {
        $('#reminderForm')[0].reset();
        $('#reminder_id').val('');
        $('#reminderModal .modal-title').text('<?php echo $this->lang->line('add_reminder'); ?>');
    });
});
</script>