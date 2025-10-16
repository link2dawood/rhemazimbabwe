<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <i class="fa fa-file-text-o"></i> Partner Statement Report
        <small>Individual partner financial statements</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url(); ?>admin/partners">Partners</a></li>
        <li><a href="<?php echo base_url(); ?>admin/partner_reports">Reports</a></li>
        <li class="active">Partner Statement</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Filter Form -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-filter"></i> Statement Parameters</h3>
                </div>
                <form method="post" action="">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Select Partner <span class="text-danger">*</span></label>
                                    <select name="partner_id" class="form-control" required>
                                        <option value="">Choose Partner</option>
                                        <?php foreach ($partners as $partner): ?>
                                        <option value="<?php echo $partner->id; ?>" <?php echo ($partner_id == $partner->id) ? 'selected' : ''; ?>>
                                            <?php echo $partner->partner_code . ' - ' . $partner->firstname . ' ' . $partner->lastname; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label><br>
                                    <button type="submit" name="filter" class="btn btn-primary">
                                        <i class="fa fa-search"></i> Generate
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if ($partner_id && isset($partner)): ?>
    <!-- Partner Information -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-user"></i> Partner Information
                        <small class="pull-right">Statement Period: <?php echo date('d M Y', strtotime($start_date)) . ' - ' . date('d M Y', strtotime($end_date)); ?></small>
                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Partner Code:</th>
                                    <td><span class="label label-info"><?php echo $partner->partner_code; ?></span></td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td><strong><?php echo $partner->firstname . ' ' . $partner->lastname; ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?php echo $partner->email; ?></td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td><?php echo $partner->mobileno; ?></td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td><?php echo $partner->address . ', ' . $partner->city . ', ' . $partner->country; ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Giving Type:</th>
                                    <td><?php echo $partner->type_name ? $partner->type_name : 'Not Set'; ?></td>
                                </tr>
                                <tr>
                                    <th>Frequency:</th>
                                    <td><?php echo $partner->frequency_name ? $partner->frequency_name : 'Not Set'; ?></td>
                                </tr>
                                <tr>
                                    <th>Amount:</th>
                                    <td><strong><?php echo $partner->currency . ' ' . number_format($partner->contribution_amount, 2); ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="label label-<?php 
                                            echo $partner->status == 'active' ? 'success' : 
                                                ($partner->status == 'inactive' ? 'warning' : 'danger'); 
                                        ?>">
                                            <?php echo ucfirst($partner->status); ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Registration:</th>
                                    <td><?php echo date('d M Y', strtotime($partner->created_at)); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statement Summary -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-calculator"></i> Statement Summary</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box bg-blue">
                                <span class="info-box-icon"><i class="fa fa-money"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Opening Balance</span>
                                    <span class="info-box-number"><?php echo $partner->currency . ' ' . number_format($statement_summary['opening_balance'], 2); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-green">
                                <span class="info-box-icon"><i class="fa fa-plus"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Period Contributions</span>
                                    <span class="info-box-number"><?php echo $partner->currency . ' ' . number_format($statement_summary['total_contributed'], 2); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-yellow">
                                <span class="info-box-icon"><i class="fa fa-calendar"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Expected Amount</span>
                                    <span class="info-box-number"><?php echo $partner->currency . ' ' . number_format($statement_summary['expected_amount'], 2); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-<?php echo $statement_summary['balance_status'] == 'Up to Date' ? 'green' : ($statement_summary['balance_status'] == 'Good' ? 'blue' : ($statement_summary['balance_status'] == 'Behind' ? 'yellow' : 'red')); ?>">
                                <span class="info-box-icon"><i class="fa fa-balance-scale"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Closing Balance</span>
                                    <span class="info-box-number"><?php echo $partner->currency . ' ' . number_format($statement_summary['closing_balance'], 2); ?></span>
                                    <span class="progress-description"><?php echo $statement_summary['balance_status']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contribution Details -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-list"></i> Contribution Details
                        <div class="box-tools pull-right">
                            <button type="submit" form="statementForm" name="generate_pdf" class="btn btn-success btn-sm">
                                <i class="fa fa-file-pdf-o"></i> Generate PDF
                            </button>
                        </div>
                    </h3>
                </div>
                <form id="statementForm" method="post" action="">
                    <input type="hidden" name="partner_id" value="<?php echo $partner_id; ?>">
                    <input type="hidden" name="start_date" value="<?php echo $start_date; ?>">
                    <input type="hidden" name="end_date" value="<?php echo $end_date; ?>">
                </form>
                <div class="box-body">
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
                                    <th>Transaction ID</th>
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
                                    <td><?php echo $contribution->transaction_id ? $contribution->transaction_id : '-'; ?></td>
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
                                    <th><?php echo $partner->currency . ' ' . number_format($statement_summary['total_contributed'], 2); ?></th>
                                    <th colspan="3"></th>
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
        </div>
    </div>
    <?php else: ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> Please select a partner and date range to generate the statement.
            </div>
        </div>
    </div>
    <?php endif; ?>
</section>

