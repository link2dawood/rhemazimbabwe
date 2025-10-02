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
                        <img class="profile-user-img img-responsive img-circle" src="<?php echo $partner->image ? base_url().$partner->image : base_url().'uploads/partner_images/default.png' ?>" alt="Partner">
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
                                    <td><?php echo $partner->city . ', ' . $partner->state . ', ' . $partner->country . ' ' . $partner->zipcode ?></td>
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
                                        <th><?php echo $this->lang->line('title'); ?></th>
                                        <th><?php echo $this->lang->line('type'); ?></th>
                                        <th><?php echo $this->lang->line('send_via'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($reminders)) {
                                        foreach ($reminders as $reminder) { ?>
                                        <tr>
                                            <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($reminder->reminder_date)) ?></td>
                                            <td><?php echo $reminder->title ?></td>
                                            <td><?php echo ucfirst(str_replace('_', ' ', $reminder->reminder_type)) ?></td>
                                            <td><?php echo ucfirst($reminder->send_via) ?></td>
                                            <td><span class="label label-<?php echo $reminder->status == 'sent' ? 'success' : 'warning' ?>"><?php echo ucfirst($reminder->status) ?></span></td>
                                        </tr>
                                    <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="5" class="text-center"><?php echo $this->lang->line('no_reminders_found'); ?></td>
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