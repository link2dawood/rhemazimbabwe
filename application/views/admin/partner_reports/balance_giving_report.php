<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <i class="fa fa-exclamation-triangle"></i> Balance Giving Report with Remark
        <small>Monitor partner giving balances and follow-up actions</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url(); ?>admin/partners">Partners</a></li>
        <li><a href="<?php echo base_url(); ?>admin/partner_reports">Reports</a></li>
        <li class="active">Balance Giving Report</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Filter Form -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-filter"></i> Report Filters</h3>
                </div>
                <form method="post" action="">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Partner Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">All Statuses</option>
                                        <option value="active" <?php echo (isset($filters['status']) && $filters['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo (isset($filters['status']) && $filters['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                        <option value="suspended" <?php echo (isset($filters['status']) && $filters['status'] == 'suspended') ? 'selected' : ''; ?>>Suspended</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Balance Status</label>
                                    <select name="balance_status" class="form-control">
                                        <option value="">All Balance Statuses</option>
                                        <option value="Up to Date" <?php echo (isset($filters['balance_status']) && $filters['balance_status'] == 'Up to Date') ? 'selected' : ''; ?>>Up to Date</option>
                                        <option value="Good" <?php echo (isset($filters['balance_status']) && $filters['balance_status'] == 'Good') ? 'selected' : ''; ?>>Good</option>
                                        <option value="Behind" <?php echo (isset($filters['balance_status']) && $filters['balance_status'] == 'Behind') ? 'selected' : ''; ?>>Behind</option>
                                        <option value="Critical" <?php echo (isset($filters['balance_status']) && $filters['balance_status'] == 'Critical') ? 'selected' : ''; ?>>Critical</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label><br>
                                    <button type="submit" name="filter" class="btn btn-primary">
                                        <i class="fa fa-search"></i> Apply Filters
                                    </button>
                                    <button type="submit" name="generate_pdf" class="btn btn-success">
                                        <i class="fa fa-file-pdf-o"></i> Generate PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?php echo $summary_stats['up_to_date']; ?></h3>
                    <p>Up to Date</p>
                </div>
                <div class="icon">
                    <i class="fa fa-check"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-blue">
                <div class="inner">
                    <h3><?php echo $summary_stats['good']; ?></h3>
                    <p>Good Status</p>
                </div>
                <div class="icon">
                    <i class="fa fa-thumbs-up"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3><?php echo $summary_stats['behind']; ?></h3>
                    <p>Behind</p>
                </div>
                <div class="icon">
                    <i class="fa fa-exclamation"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3><?php echo $summary_stats['critical']; ?></h3>
                    <p>Critical</p>
                </div>
                <div class="icon">
                    <i class="fa fa-warning"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-calculator"></i> Financial Summary</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box bg-blue">
                                <span class="info-box-icon"><i class="fa fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Partners</span>
                                    <span class="info-box-number"><?php echo $summary_stats['total_partners']; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-green">
                                <span class="info-box-icon"><i class="fa fa-money"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Expected</span>
                                    <span class="info-box-number">$<?php echo number_format($summary_stats['total_expected'], 2); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-yellow">
                                <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Actual</span>
                                    <span class="info-box-number">$<?php echo number_format($summary_stats['total_actual'], 2); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-red">
                                <span class="info-box-icon"><i class="fa fa-exclamation-triangle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Balance</span>
                                    <span class="info-box-number">$<?php echo number_format($summary_stats['total_balance'], 2); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Balance Report Results -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-balance-scale"></i> Balance Giving Report with Remarks
                        <small class="pull-right">Generated: <?php echo date('Y-m-d H:i:s'); ?></small>
                    </h3>
                </div>
                <div class="box-body">
                    <?php if (!empty($balance_data)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Partner Code</th>
                                    <th>Partner Name</th>
                                    <th>Giving Type</th>
                                    <th>Frequency</th>
                                    <th>Expected Amount</th>
                                    <th>Actual Amount</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Remark</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($balance_data as $data): ?>
                                <tr class="<?php echo $data->balance_status == 'Critical' ? 'danger' : ($data->balance_status == 'Behind' ? 'warning' : ''); ?>">
                                    <td>
                                        <span class="label label-info"><?php echo $data->partner_code; ?></span>
                                    </td>
                                    <td>
                                        <strong><?php echo $data->firstname . ' ' . $data->lastname; ?></strong>
                                        <?php if ($data->account_type == 'organization' && $data->organization_name): ?>
                                        <br><small class="text-muted"><?php echo $data->organization_name; ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $data->type_name ? $data->type_name : 'Not Set'; ?></td>
                                    <td><?php echo $data->frequency_name ? $data->frequency_name : 'Not Set'; ?></td>
                                    <td>
                                        <strong><?php echo $data->currency . ' ' . number_format($data->expected_amount, 2); ?></strong>
                                    </td>
                                    <td>
                                        <?php echo $data->currency . ' ' . number_format($data->actual_amount, 2); ?>
                                    </td>
                                    <td>
                                        <strong class="<?php echo $data->balance > 0 ? 'text-red' : 'text-green'; ?>">
                                            <?php echo $data->currency . ' ' . number_format($data->balance, 2); ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="label label-<?php 
                                            echo $data->balance_status == 'Up to Date' ? 'success' : 
                                                ($data->balance_status == 'Good' ? 'primary' : 
                                                ($data->balance_status == 'Behind' ? 'warning' : 'danger')); 
                                        ?>">
                                            <?php echo $data->balance_status; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?php echo $data->remark; ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <?php if ($data->balance_status == 'Critical' || $data->balance_status == 'Behind'): ?>
                                            <a href="<?php echo base_url(); ?>admin/partners/show/<?php echo $data->id; ?>" class="btn btn-warning btn-xs" title="Follow Up">
                                                <i class="fa fa-phone"></i>
                                            </a>
                                            <?php endif; ?>
                                            <a href="<?php echo base_url(); ?>admin/partners/show/<?php echo $data->id; ?>" class="btn btn-info btn-xs" title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Priority Actions -->
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <h4><i class="fa fa-warning"></i> Priority Actions Required</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Critical Partners (<?php echo $summary_stats['critical']; ?>):</strong>
                                        <p>Immediate follow-up required. These partners are significantly behind on their contributions.</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Behind Partners (<?php echo $summary_stats['behind']; ?>):</strong>
                                        <p>Follow-up recommended. These partners are behind but within manageable range.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> No partners found matching the selected criteria.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

