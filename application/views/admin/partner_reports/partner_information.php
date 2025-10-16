<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <i class="fa fa-users"></i> Partner Information Report
        <small>Comprehensive partner data report</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url(); ?>admin/partners">Partners</a></li>
        <li><a href="<?php echo base_url(); ?>admin/partner_reports">Reports</a></li>
        <li class="active">Partner Information</li>
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">All Statuses</option>
                                        <option value="active" <?php echo (isset($filters['status']) && $filters['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo (isset($filters['status']) && $filters['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                        <option value="suspended" <?php echo (isset($filters['status']) && $filters['status'] == 'suspended') ? 'selected' : ''; ?>>Suspended</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Giving Type</label>
                                    <select name="giving_type_id" class="form-control">
                                        <option value="">All Types</option>
                                        <?php foreach ($giving_types as $type): ?>
                                        <option value="<?php echo $type->id; ?>" <?php echo (isset($filters['giving_type_id']) && $filters['giving_type_id'] == $type->id) ? 'selected' : ''; ?>>
                                            <?php echo $type->name; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Giving Frequency</label>
                                    <select name="giving_frequency_id" class="form-control">
                                        <option value="">All Frequencies</option>
                                        <?php foreach ($giving_frequencies as $frequency): ?>
                                        <option value="<?php echo $frequency->id; ?>" <?php echo (isset($filters['giving_frequency_id']) && $filters['giving_frequency_id'] == $frequency->id) ? 'selected' : ''; ?>>
                                            <?php echo $frequency->name; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Account Type</label>
                                    <select name="account_type" class="form-control">
                                        <option value="">All Types</option>
                                        <option value="individual" <?php echo (isset($filters['account_type']) && $filters['account_type'] == 'individual') ? 'selected' : ''; ?>>Individual</option>
                                        <option value="organization" <?php echo (isset($filters['account_type']) && $filters['account_type'] == 'organization') ? 'selected' : ''; ?>>Organization</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" name="filter" class="btn btn-primary">
                            <i class="fa fa-search"></i> Apply Filters
                        </button>
                        <button type="submit" name="generate_pdf" class="btn btn-success">
                            <i class="fa fa-file-pdf-o"></i> Generate PDF
                        </button>
                        <a href="<?php echo base_url(); ?>admin/partner_reports/partner_information" class="btn btn-default">
                            <i class="fa fa-refresh"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Report Results -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-list"></i> Partner Information Report
                        <small class="pull-right">Generated: <?php echo date('Y-m-d H:i:s'); ?></small>
                    </h3>
                </div>
                <div class="box-body">
                    <?php if (!empty($partners)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Partner Code</th>
                                    <th>Name</th>
                                    <th>Account Type</th>
                                    <th>Contact Information</th>
                                    <th>Giving Details</th>
                                    <th>Status</th>
                                    <th>Registration Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($partners as $partner): ?>
                                <tr>
                                    <td>
                                        <span class="label label-info"><?php echo $partner->partner_code; ?></span>
                                    </td>
                                    <td>
                                        <strong><?php echo $partner->firstname . ' ' . $partner->lastname; ?></strong>
                                        <?php if ($partner->account_type == 'organization' && $partner->organization_name): ?>
                                        <br><small class="text-muted"><?php echo $partner->organization_name; ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="label label-<?php echo $partner->account_type == 'individual' ? 'primary' : 'success'; ?>">
                                            <?php echo ucfirst($partner->account_type); ?>
                                        </span>
                                        <?php if ($partner->organization_type): ?>
                                        <br><small><?php echo $partner->organization_type; ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <i class="fa fa-envelope"></i> <?php echo $partner->email; ?><br>
                                        <i class="fa fa-phone"></i> <?php echo $partner->mobileno; ?><br>
                                        <?php if ($partner->city): ?>
                                        <i class="fa fa-map-marker"></i> <?php echo $partner->city . ', ' . $partner->country; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong>Type:</strong> <?php echo $partner->type_name ? $partner->type_name : 'Not Set'; ?><br>
                                        <strong>Frequency:</strong> <?php echo $partner->frequency_name ? $partner->frequency_name : 'Not Set'; ?><br>
                                        <strong>Amount:</strong> <?php echo $partner->currency . ' ' . number_format($partner->contribution_amount, 2); ?>
                                    </td>
                                    <td>
                                        <span class="label label-<?php 
                                            echo $partner->status == 'active' ? 'success' : 
                                                ($partner->status == 'inactive' ? 'warning' : 'danger'); 
                                        ?>">
                                            <?php echo ucfirst($partner->status); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php echo date('d M Y', strtotime($partner->created_at)); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Summary Statistics -->
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h4><i class="fa fa-info-circle"></i> Report Summary</h4>
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Total Partners:</strong> <?php echo count($partners); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Active Partners:</strong> <?php echo count(array_filter($partners, function($p) { return $p->status == 'active'; })); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Individual Accounts:</strong> <?php echo count(array_filter($partners, function($p) { return $p->account_type == 'individual'; })); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Organization Accounts:</strong> <?php echo count(array_filter($partners, function($p) { return $p->account_type == 'organization'; })); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fa fa-warning"></i> No partners found matching the selected criteria.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

