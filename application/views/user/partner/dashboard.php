<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-handshake-o"></i> <?php echo $this->lang->line('partner_dashboard'); ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('user/user/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line('dashboard'); ?></a></li>
            <li class="active"><?php echo $this->lang->line('partners'); ?></li>
        </ol>
    </section>

    <section class="content">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($partners)): ?>
            <!-- No Partner Record - Registration Invitation -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-body text-center" style="padding: 50px;">
                            <i class="fa fa-handshake-o" style="font-size: 80px; color: #3c8dbc; margin-bottom: 20px;"></i>
                            <h2><?php echo $this->lang->line('become_a_partner'); ?></h2>
                            <p style="font-size: 16px; margin: 20px 0;">
                                <?php echo $this->lang->line('make_a_difference'); ?> in students' lives through partnership.
                                <br>Join our community of supporters and help transform education.
                            </p>
                            <a href="<?php echo base_url('user/partner/register'); ?>" class="btn btn-primary btn-lg">
                                <i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('register_as_partner'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Statistics Boxes -->
            <?php if ($statistics): ?>
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="fa fa-handshake-o"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text"><?php echo $this->lang->line('total_partners'); ?></span>
                                <span class="info-box-number"><?php echo $statistics['total_partners']; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="fa fa-check-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text"><?php echo $this->lang->line('active_partners'); ?></span>
                                <span class="info-box-number"><?php echo $statistics['active_partners']; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-yellow"><i class="fa fa-money"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text"><?php echo $this->lang->line('total_contributed'); ?></span>
                                <span class="info-box-number">$<?php echo number_format($statistics['total_contributed'], 2); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-red"><i class="fa fa-calendar"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text"><?php echo date('Y'); ?> <?php echo $this->lang->line('contributions'); ?></span>
                                <span class="info-box-number">$<?php echo number_format($statistics['this_year_contributed'], 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Partner Records -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-user"></i> <?php echo isset($is_partner_portal) && $is_partner_portal ? $this->lang->line('my_profile') : $this->lang->line('my_partnerships'); ?></h3>
                            <?php if (!isset($is_partner_portal) || !$is_partner_portal): ?>
                            <div class="box-tools pull-right">
                                <a href="<?php echo base_url('user/partner/register'); ?>" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_partner'); ?>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('partner_code'); ?></th>
                                            <th><?php echo $this->lang->line('account_type'); ?></th>
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('email'); ?></th>
                                            <th><?php echo $this->lang->line('giving_type'); ?></th>
                                            <th><?php echo $this->lang->line('status'); ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($partners as $partner): ?>
                                            <tr>
                                                <td><strong><?php echo $partner['partner_code']; ?></strong></td>
                                                <td>
                                                    <?php if ($partner['account_type'] == 'individual'): ?>
                                                        <span class="label label-info">
                                                            <i class="fa fa-user"></i> <?php echo $this->lang->line('individual_partner'); ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="label label-primary">
                                                            <i class="fa fa-building"></i> <?php echo $this->lang->line('organization_partner'); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($partner['account_type'] == 'organization'): ?>
                                                        <strong><?php echo $partner['organization_name']; ?></strong><br>
                                                        <small><?php echo $partner['firstname'] . ' ' . $partner['lastname']; ?></small>
                                                    <?php else: ?>
                                                        <?php echo $partner['firstname'] . ' ' . $partner['lastname']; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo $partner['email']; ?></td>
                                                <td>
                                                    <?php
                                                    if ($partner['giving_type_id']) {
                                                        $giving_type = $this->giving_type_model->get($partner['giving_type_id']);
                                                        echo $giving_type['name'] ?? '-';
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $status_class = '';
                                                    $status_text = '';
                                                    switch ($partner['status']) {
                                                        case 'active':
                                                            $status_class = 'success';
                                                            $status_text = $this->lang->line('active');
                                                            break;
                                                        case 'inactive':
                                                            $status_class = 'warning';
                                                            $status_text = $this->lang->line('pending');
                                                            break;
                                                        case 'suspended':
                                                            $status_class = 'danger';
                                                            $status_text = $this->lang->line('suspended');
                                                            break;
                                                    }
                                                    ?>
                                                    <span class="label label-<?php echo $status_class; ?>">
                                                        <?php echo $status_text; ?>
                                                    </span>
                                                </td>
                                                <td class="text-right">
                                                    <div class="btn-group">
                                                        <a href="<?php echo base_url('user/partner/contributions?partner_id=' . $partner['id']); ?>"
                                                           class="btn btn-primary btn-xs"
                                                           data-toggle="tooltip"
                                                           title="<?php echo $this->lang->line('contributions'); ?>">
                                                            <i class="fa fa-history"></i>
                                                        </a>
                                                        <a href="<?php echo base_url('user/partner/settings?partner_id=' . $partner['id']); ?>"
                                                           class="btn btn-info btn-xs"
                                                           data-toggle="tooltip"
                                                           title="<?php echo $this->lang->line('giving_settings'); ?>">
                                                            <i class="fa fa-cog"></i>
                                                        </a>
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

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-info-circle"></i> <?php echo $this->lang->line('quick_actions'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <?php if (!isset($is_partner_portal) || !$is_partner_portal): ?>
                                <div class="col-md-3 col-sm-6">
                                    <a href="<?php echo base_url('user/partner/register'); ?>" class="btn btn-app" style="width: 100%;">
                                        <i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('add_partner'); ?>
                                    </a>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($partners)): ?>
                                    <div class="col-md-3 col-sm-6">
                                        <a href="<?php echo base_url('user/partner/contributions?partner_id=' . $partners[0]['id']); ?>" class="btn btn-app" style="width: 100%;">
                                            <i class="fa fa-history"></i> <?php echo $this->lang->line('view_history'); ?>
                                        </a>
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <a href="<?php echo base_url('user/partner/settings?partner_id=' . $partners[0]['id']); ?>" class="btn btn-app" style="width: 100%;">
                                            <i class="fa fa-cog"></i> <?php echo $this->lang->line('manage_settings'); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </section>
</div>

<script>
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
