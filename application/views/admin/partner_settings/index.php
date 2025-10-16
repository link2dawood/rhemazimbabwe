<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <i class="fa fa-cogs"></i> Partner Settings
        <small>Manage partner system settings</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url(); ?>admin/partners">Partners</a></li>
        <li class="active">Settings</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <!-- Giving Types Card -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3><?php echo count($giving_types); ?></h3>
                    <p>Giving Types</p>
                </div>
                <div class="icon">
                    <i class="fa fa-gift"></i>
                </div>
                <a href="<?php echo base_url(); ?>admin/partner_settings/giving_types" class="small-box-footer">
                    Manage <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Giving Frequencies Card -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?php echo count($giving_frequencies); ?></h3>
                    <p>Giving Frequencies</p>
                </div>
                <div class="icon">
                    <i class="fa fa-clock-o"></i>
                </div>
                <a href="<?php echo base_url(); ?>admin/partner_settings/giving_frequencies" class="small-box-footer">
                    Manage <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Permissions Card -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3><?php echo count($permission_types); ?></h3>
                    <p>Permission Types</p>
                </div>
                <div class="icon">
                    <i class="fa fa-key"></i>
                </div>
                <a href="<?php echo base_url(); ?>admin/partner_settings/permissions" class="small-box-footer">
                    Manage <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Reminders Card -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3><?php echo count($reminder_templates); ?></h3>
                    <p>Reminder Templates</p>
                </div>
                <div class="icon">
                    <i class="fa fa-bell"></i>
                </div>
                <a href="<?php echo base_url(); ?>admin/partner_settings/reminders" class="small-box-footer">
                    Manage <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Quick Settings Panel -->
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-cogs"></i> Quick Settings Overview</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <!-- Giving Types Overview -->
                        <div class="col-md-6">
                            <h4><i class="fa fa-gift text-aqua"></i> Giving Types</h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Code</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($giving_types, 0, 5) as $type): ?>
                                        <tr>
                                            <td><?php echo $type->name; ?></td>
                                            <td><span class="label label-info"><?php echo $type->code; ?></span></td>
                                            <td>
                                                <?php if ($type->is_active): ?>
                                                    <span class="label label-success">Active</span>
                                                <?php else: ?>
                                                    <span class="label label-danger">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if (count($giving_types) > 5): ?>
                            <p class="text-muted">... and <?php echo count($giving_types) - 5; ?> more</p>
                            <?php endif; ?>
                        </div>

                        <!-- Giving Frequencies Overview -->
                        <div class="col-md-6">
                            <h4><i class="fa fa-clock-o text-green"></i> Giving Frequencies</h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Interval</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($giving_frequencies, 0, 5) as $frequency): ?>
                                        <tr>
                                            <td><?php echo $frequency->name; ?></td>
                                            <td>
                                                <?php if ($frequency->days_interval): ?>
                                                    <?php echo $frequency->days_interval; ?> days
                                                <?php else: ?>
                                                    <span class="text-muted">Once-off</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($frequency->is_active): ?>
                                                    <span class="label label-success">Active</span>
                                                <?php else: ?>
                                                    <span class="label label-danger">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if (count($giving_frequencies) > 5): ?>
                            <p class="text-muted">... and <?php echo count($giving_frequencies) - 5; ?> more</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Permission Types Overview -->
                        <div class="col-md-6">
                            <h4><i class="fa fa-key text-yellow"></i> Permission Types</h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Code</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($permission_types, 0, 5) as $permission): ?>
                                        <tr>
                                            <td><?php echo $permission->permission_name; ?></td>
                                            <td><span class="label label-info"><?php echo $permission->permission_code; ?></span></td>
                                            <td>
                                                <?php if ($permission->is_active): ?>
                                                    <span class="label label-success">Active</span>
                                                <?php else: ?>
                                                    <span class="label label-danger">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if (count($permission_types) > 5): ?>
                            <p class="text-muted">... and <?php echo count($permission_types) - 5; ?> more</p>
                            <?php endif; ?>
                        </div>

                        <!-- Reminder Templates Overview -->
                        <div class="col-md-6">
                            <h4><i class="fa fa-bell text-red"></i> Reminder Templates</h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Template</th>
                                            <th>Type</th>
                                            <th>Timing</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($reminder_templates, 0, 5) as $template): ?>
                                        <tr>
                                            <td><?php echo $template->template_name; ?></td>
                                            <td><span class="label label-primary"><?php echo ucfirst($template->reminder_type); ?></span></td>
                                            <td>
                                                <?php if ($template->timing == 'before'): ?>
                                                    <span class="text-warning"><?php echo $template->days_before; ?> days before</span>
                                                <?php else: ?>
                                                    <span class="text-danger"><?php echo $template->days_after; ?> days after</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($template->is_active): ?>
                                                    <span class="label label-success">Active</span>
                                                <?php else: ?>
                                                    <span class="label label-danger">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if (count($reminder_templates) > 5): ?>
                            <p class="text-muted">... and <?php echo count($reminder_templates) - 5; ?> more</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Information Panel -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-info-circle"></i> Settings Information</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h5><i class="fa fa-gift text-aqua"></i> Giving Types</h5>
                            <p class="text-muted">Define different types of contributions partners can make (e.g., Tuition Support, Scholarship Fund, Building Fund).</p>
                        </div>
                        <div class="col-md-3">
                            <h5><i class="fa fa-clock-o text-green"></i> Giving Frequencies</h5>
                            <p class="text-muted">Set how often partners can contribute (Once-Off, Weekly, Monthly, Quarterly, Annually).</p>
                        </div>
                        <div class="col-md-3">
                            <h5><i class="fa fa-key text-yellow"></i> Permission Types</h5>
                            <p class="text-muted">Configure what access partners have (Library, Online Courses, Download Centre, GMeet, Zoom).</p>
                        </div>
                        <div class="col-md-3">
                            <h5><i class="fa fa-bell text-red"></i> Reminder Templates</h5>
                            <p class="text-muted">Create automated reminder messages for contributions, follow-ups, and renewals.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
