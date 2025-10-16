<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <i class="fa fa-file-text-o"></i> My Partner Statement
        <small>Financial statement and contribution history</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>user/user/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url(); ?>user/partner">Partners</a></li>
        <li><a href="<?php echo base_url(); ?>user/partner_reports">Reports</a></li>
        <li class="active">My Statement</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Filter Form -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-filter"></i> Statement Period</h3>
                </div>
                <form method="post" action="">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label><br>
                                    <button type="submit" name="filter" class="btn btn-primary">
                                        <i class="fa fa-search"></i> Update Statement
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if (!empty($partner_statements)): ?>
    <?php foreach ($partner_statements as $statement_data): ?>
    <?php $partner = $statement_data['partner']; ?>
    <?php $contributions = $statement_data['contributions']; ?>
    <?php $summary = $statement_data['statement_summary']; ?>
    
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-file-text-o"></i> Statement for <?php echo $partner['firstname'] . ' ' . $partner['lastname']; ?>
                        <span class="pull-right">
                            <span class="label label-info"><?php echo $partner['partner_code']; ?></span>
                        </span>
                    </h3>
                </div>
                <div class="box-body">
                    <!-- Statement Summary -->
                    <div class="row">
                        <div class="col-md-12">
                            <h4><i class="fa fa-calculator text-blue"></i> Statement Summary</h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="info-box bg-blue">
                                        <span class="info-box-icon"><i class="fa fa-money"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Opening Balance</span>
                                            <span class="info-box-number"><?php echo $partner['currency'] . ' ' . number_format($summary['opening_balance'], 2); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box bg-green">
                                        <span class="info-box-icon"><i class="fa fa-plus"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Period Contributions</span>
                                            <span class="info-box-number"><?php echo $partner['currency'] . ' ' . number_format($summary['total_contributed'], 2); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box bg-yellow">
                                        <span class="info-box-icon"><i class="fa fa-calendar"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Expected Amount</span>
                                            <span class="info-box-number"><?php echo $partner['currency'] . ' ' . number_format($summary['expected_amount'], 2); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box bg-<?php echo $summary['balance_status'] == 'Up to Date' ? 'green' : ($summary['balance_status'] == 'Good' ? 'blue' : ($summary['balance_status'] == 'Behind' ? 'yellow' : 'red')); ?>">
                                        <span class="info-box-icon"><i class="fa fa-balance-scale"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Closing Balance</span>
                                            <span class="info-box-number"><?php echo $partner['currency'] . ' ' . number_format($summary['closing_balance'], 2); ?></span>
                                            <span class="progress-description"><?php echo $summary['balance_status']; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contribution Details -->
                    <div class="row">
                        <div class="col-md-12">
                            <h4><i class="fa fa-list text-green"></i> Contribution Details</h4>
                            <?php if (!empty($contributions)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Receipt No</th>
                                            <th>Giving Type</th>
                                            <th>Amount</th>
                                            <th>Payment Method</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($contributions as $contribution): ?>
                                        <tr>
                                            <td><?php echo date('d M Y', strtotime($contribution->contribution_date)); ?></td>
                                            <td>
                                                <span class="label label-info"><?php echo $contribution->receipt_no; ?></span>
                                            </td>
                                            <td><?php echo $contribution->type_name ? $contribution->type_name : 'Not Specified'; ?></td>
                                            <td>
                                                <strong><?php echo $contribution->currency . ' ' . number_format($contribution->amount, 2); ?></strong>
                                            </td>
                                            <td><?php echo $contribution->payment_method ? $contribution->payment_method : 'Not Specified'; ?></td>
                                            <td>
                                                <span class="label label-<?php 
                                                    echo $contribution->status == 'completed' ? 'success' : 
                                                        ($contribution->status == 'pending' ? 'warning' : 'danger'); 
                                                ?>">
                                                    <?php echo ucfirst($contribution->status); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-gray">
                                            <th colspan="3">Total Contributions in Period:</th>
                                            <th><?php echo $partner['currency'] . ' ' . number_format($summary['total_contributed'], 2); ?></th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> No contributions found for the selected period.
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Balance Status Alert -->
                    <?php if ($summary['balance_status'] != 'Up to Date'): ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-<?php echo $summary['balance_status'] == 'Critical' ? 'danger' : 'warning'; ?>">
                                <h4><i class="fa fa-<?php echo $summary['balance_status'] == 'Critical' ? 'warning' : 'exclamation'; ?>"></i> 
                                    Balance Status: <?php echo $summary['balance_status']; ?>
                                </h4>
                                <p>
                                    <?php if ($summary['balance_status'] == 'Critical'): ?>
                                    Your contributions are significantly behind. Please contact the school administration to discuss your partnership status.
                                    <?php elseif ($summary['balance_status'] == 'Behind'): ?>
                                    You are behind on your contributions. Please consider making a payment to bring your account up to date.
                                    <?php else: ?>
                                    You are slightly behind on your contributions but within an acceptable range.
                                    <?php endif; ?>
                                </p>
                                <?php if ($partner['status'] == 'active'): ?>
                                <a href="<?php echo base_url(); ?>user/partner/contribute" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> Make Contribution
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning">
                <h4><i class="fa fa-warning"></i> No Partner Statements Found</h4>
                <p>You don't have any partner accounts associated with your profile. If you believe this is an error, please contact the school administration.</p>
                <a href="<?php echo base_url(); ?>user/partner/register" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Become a Partner
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</section>

<script type="text/javascript">
$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
