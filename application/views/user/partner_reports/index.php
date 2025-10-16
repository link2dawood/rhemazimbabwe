<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <i class="fa fa-bar-chart"></i> Partner Reports
        <small>View your partner reports and statements</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>user/user/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url(); ?>user/partner">Partners</a></li>
        <li class="active">Reports</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <?php if (!empty($partners)): ?>
    <div class="row">
        <!-- Partner Information Report Card -->
        <div class="col-lg-6 col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-user"></i> My Partner Information</h3>
                </div>
                <div class="box-body">
                    <p>View your complete partner information including contact details, giving preferences, and account status.</p>
                    <ul class="list-unstyled">
                        <li><i class="fa fa-check text-green"></i> Personal information</li>
                        <li><i class="fa fa-check text-green"></i> Giving preferences</li>
                        <li><i class="fa fa-check text-green"></i> Account status</li>
                        <li><i class="fa fa-check text-green"></i> Contact details</li>
                    </ul>
                </div>
                <div class="box-footer">
                    <a href="<?php echo base_url(); ?>user/partner_reports/partner_information" class="btn btn-primary btn-block">
                        <i class="fa fa-user"></i> View My Information
                    </a>
                </div>
            </div>
        </div>

        <!-- Partner Statement Report Card -->
        <div class="col-lg-6 col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-file-text-o"></i> My Partner Statement</h3>
                </div>
                <div class="box-body">
                    <p>View your detailed financial statement showing contribution history, balances, and payment status.</p>
                    <ul class="list-unstyled">
                        <li><i class="fa fa-check text-green"></i> Contribution history</li>
                        <li><i class="fa fa-check text-green"></i> Balance information</li>
                        <li><i class="fa fa-check text-green"></i> Payment status</li>
                        <li><i class="fa fa-check text-green"></i> Date range filtering</li>
                    </ul>
                </div>
                <div class="box-footer">
                    <a href="<?php echo base_url(); ?>user/partner_reports/partner_statement" class="btn btn-success btn-block">
                        <i class="fa fa-file-text"></i> View My Statement
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- My Partners Overview -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"></i> My Partner Accounts</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Partner Code</th>
                                    <th>Name</th>
                                    <th>Account Type</th>
                                    <th>Giving Type</th>
                                    <th>Frequency</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($partners as $partner): ?>
                                <tr>
                                    <td><span class="label label-info"><?php echo $partner['partner_code']; ?></span></td>
                                    <td><strong><?php echo $partner['firstname'] . ' ' . $partner['lastname']; ?></strong></td>
                                    <td>
                                        <span class="label label-<?php echo $partner['account_type'] == 'individual' ? 'primary' : 'success'; ?>">
                                            <?php echo ucfirst($partner['account_type']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $partner['type_name'] ? $partner['type_name'] : 'Not Set'; ?></td>
                                    <td><?php echo $partner['frequency_name'] ? $partner['frequency_name'] : 'Not Set'; ?></td>
                                    <td><strong><?php echo $partner['currency'] . ' ' . number_format($partner['contribution_amount'], 2); ?></strong></td>
                                    <td>
                                        <span class="label label-<?php 
                                            echo $partner['status'] == 'active' ? 'success' : 
                                                ($partner['status'] == 'inactive' ? 'warning' : 'danger'); 
                                        ?>">
                                            <?php echo ucfirst($partner['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url(); ?>user/partner_reports/partner_information" class="btn btn-info btn-xs">
                                            <i class="fa fa-eye"></i> View
                                        </a>
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
    <?php else: ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <h4><i class="fa fa-info-circle"></i> No Partner Accounts Found</h4>
                <p>You don't have any partner accounts associated with your profile. If you believe this is an error, please contact the school administration.</p>
                <a href="<?php echo base_url(); ?>user/partner/register" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Become a Partner
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Report Information -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-info-circle"></i> About Partner Reports</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="fa fa-user text-primary"></i> Partner Information</h5>
                            <p class="text-muted">View your complete partner profile including personal details, giving preferences, and account status. This information helps you stay updated on your partnership details.</p>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fa fa-file-text-o text-success"></i> Partner Statement</h5>
                            <p class="text-muted">Access your financial statement showing contribution history, balances, and payment status. You can filter by date range to view specific periods.</p>
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
