<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-handshake-o"></i> <?php echo $this->lang->line('my_partners'); ?>
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

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-handshake-o"></i> <?php echo $this->lang->line('my_partners'); ?>
                        </h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo base_url('user/partner/add'); ?>" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_partner'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (empty($partners)): ?>
            <!-- No Partners - Add First Partner -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-body text-center" style="padding: 50px;">
                            <i class="fa fa-handshake-o" style="font-size: 80px; color: #3c8dbc; margin-bottom: 20px;"></i>
                            <h2>No Partners Found</h2>
                            <p style="font-size: 16px; margin: 20px 0;">
                                You haven't added any partners yet. Start by adding your first partner.
                            </p>
                            <a href="<?php echo base_url('user/partner/add'); ?>" class="btn btn-primary btn-lg">
                                <i class="fa fa-plus"></i> Add First Partner
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Partners List -->
            <div class="row">
                <?php foreach ($partners as $partner): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="box box-widget widget-user-2">
                            <div class="widget-user-header bg-blue">
                                <div class="widget-user-image">
                                    <img class="img-circle" src="<?php echo isset($partner->image) && $partner->image ? base_url($partner->image) : base_url('uploads/partner_images/default.png'); ?>" alt="Partner">
                                </div>
                                <h3 class="widget-user-username"><?php echo $partner->firstname . ' ' . $partner->lastname; ?></h3>
                                <h5 class="widget-user-desc"><?php echo $partner->email; ?></h5>
                            </div>
                            <div class="box-footer no-padding">
                                <ul class="nav nav-stacked">
                                    <li>
                                        <a href="#">
                                            <strong>Giving Type:</strong> 
                                            <span class="pull-right"><?php echo $partner->type_name ?: 'Not Set'; ?></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <strong>Frequency:</strong> 
                                            <span class="pull-right"><?php echo $partner->frequency_name ?: 'Not Set'; ?></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <strong>Amount:</strong> 
                                            <span class="pull-right"><?php echo $partner->currency . ' ' . number_format($partner->contribution_amount, 2); ?></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <strong>Status:</strong> 
                                            <span class="pull-right">
                                                <span class="label label-<?php echo $partner->status == 'active' ? 'success' : 'danger'; ?>">
                                                    <?php echo ucfirst($partner->status); ?>
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-sm-4 border-right">
                                        <div class="description-block">
                                            <a href="<?php echo base_url('user/partner/contributions/' . $partner->id); ?>" class="btn btn-info btn-sm">
                                                <i class="fa fa-money"></i> Contributions
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 border-right">
                                        <div class="description-block">
                                            <a href="<?php echo base_url('user/partner/add_contribution/' . $partner->id); ?>" class="btn btn-success btn-sm">
                                                <i class="fa fa-plus"></i> Add Contribution
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="description-block">
                                            <div class="dropdown">
                                                <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                                    <i class="fa fa-cog"></i> Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="<?php echo base_url('user/partner/edit/' . $partner->id); ?>">
                                                        <i class="fa fa-edit"></i> Edit Partner
                                                    </a></li>
                                                    <li><a href="<?php echo base_url('user/partner/contributions/' . $partner->id); ?>">
                                                        <i class="fa fa-money"></i> View Contributions
                                                    </a></li>
                                                    <li><a href="<?php echo base_url('user/partner/add_contribution/' . $partner->id); ?>">
                                                        <i class="fa fa-plus"></i> Add Contribution
                                                    </a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="<?php echo base_url('user/partner/delete/' . $partner->id); ?>" 
                                                           onclick="return confirm('Are you sure you want to delete this partner?')" 
                                                           class="text-danger">
                                                        <i class="fa fa-trash"></i> Delete Partner
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Summary Statistics -->
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-handshake-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Partners</span>
                            <span class="info-box-number"><?php echo count($partners); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Active Partners</span>
                            <span class="info-box-number">
                                <?php 
                                $active_count = 0;
                                foreach ($partners as $partner) {
                                    if ($partner->status == 'active') $active_count++;
                                }
                                echo $active_count;
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-money"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Amount</span>
                            <span class="info-box-number">
                                <?php 
                                $total_amount = 0;
                                foreach ($partners as $partner) {
                                    $total_amount += $partner->contribution_amount;
                                }
                                echo '$' . number_format($total_amount, 2);
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-calendar"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">This Month</span>
                            <span class="info-box-number"><?php echo date('M Y'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </section>
</div>
