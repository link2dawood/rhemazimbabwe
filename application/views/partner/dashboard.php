<!-- Dashboard Content -->
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?php echo isset($statistics['total_contributions']) ? $statistics['total_contributions'] : '0'; ?></h3>
                <p>Total Contributions</p>
            </div>
            <div class="icon">
                <i class="fa fa-money"></i>
            </div>
            <a href="<?php echo base_url('partnerdashboard/contributions'); ?>" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?php echo isset($statistics['total_amount']) ? '$' . number_format($statistics['total_amount'], 2) : '$0.00'; ?></h3>
                <p>Total Amount</p>
            </div>
            <div class="icon">
                <i class="fa fa-dollar"></i>
            </div>
            <a href="<?php echo base_url('partnerdashboard/contributions'); ?>" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?php echo isset($statistics['this_year_amount']) ? '$' . number_format($statistics['this_year_amount'], 2) : '$0.00'; ?></h3>
                <p>This Year</p>
            </div>
            <div class="icon">
                <i class="fa fa-calendar"></i>
            </div>
            <a href="<?php echo base_url('partnerdashboard/contributions'); ?>" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
            <div class="inner">
                <h3><?php echo isset($statistics['last_contribution']) ? date('M d', strtotime($statistics['last_contribution'])) : 'Never'; ?></h3>
                <p>Last Contribution</p>
            </div>
            <div class="icon">
                <i class="fa fa-clock-o"></i>
            </div>
            <a href="<?php echo base_url('partnerdashboard/contributions'); ?>" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Partner Information -->
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Partner Information</h3>
            </div>
            <div class="box-body">
                <div class="text-center">
                    <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url('backend/images/avatar5.png'); ?>" alt="User profile picture">
                    <h3 class="profile-username text-center"><?php echo $partner['firstname'] . ' ' . $partner['lastname']; ?></h3>
                    <p class="text-muted text-center">Partner Code: <?php echo $partner['partner_code']; ?></p>
                </div>
                
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Email</b> <a class="pull-right"><?php echo $partner['email']; ?></a>
                    </li>
                    <li class="list-group-item">
                        <b>Phone</b> <a class="pull-right"><?php echo $partner['mobileno']; ?></a>
                    </li>
                    <li class="list-group-item">
                        <b>Account Type</b> <a class="pull-right"><?php echo ucfirst($partner['account_type']); ?></a>
                    </li>
                    <li class="list-group-item">
                        <b>Status</b> <a class="pull-right"><span class="label label-success"><?php echo ucfirst($partner['status']); ?></span></a>
                    </li>
                    <li class="list-group-item">
                        <b>Member Since</b> <a class="pull-right"><?php echo date('M d, Y', strtotime($partner['created_at'])); ?></a>
                    </li>
                </ul>
                
                <a href="<?php echo base_url('partnerdashboard/profile'); ?>" class="btn btn-primary btn-block"><b>Update Profile</b></a>
            </div>
        </div>
    </div>
    
    <!-- Recent Contributions -->
    <div class="col-md-8">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Recent Contributions</h3>
                <div class="box-tools pull-right">
                    <a href="<?php echo base_url('partnerdashboard/contributions'); ?>" class="btn btn-sm btn-info">View All</a>
                </div>
            </div>
            <div class="box-body">
                <?php if (!empty($recent_contributions)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_contributions as $contribution): ?>
                                <tr>
                                    <td><?php echo date('M d, Y', strtotime($contribution['contribution_date'])); ?></td>
                                    <td><?php echo !empty($contribution['giving_type_name']) ? $contribution['giving_type_name'] : 'General'; ?></td>
                                    <td>$<?php echo number_format($contribution['amount'], 2); ?></td>
                                    <td>
                                        <span class="label label-<?php echo $contribution['status'] == 'completed' ? 'success' : 'warning'; ?>">
                                            <?php echo ucfirst($contribution['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url('partnerdashboard/receipt/' . $contribution['id']); ?>" class="btn btn-xs btn-info">
                                            <i class="fa fa-download"></i> Receipt
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center">
                        <i class="fa fa-money fa-3x text-muted"></i>
                        <h4 class="text-muted">No contributions yet</h4>
                        <p class="text-muted">Your contribution history will appear here once you make your first contribution.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-md-12">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Quick Actions</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="<?php echo base_url('partnerdashboard/giving-settings'); ?>" class="btn btn-app">
                            <i class="fa fa-cogs"></i> Giving Settings
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?php echo base_url('partnerdashboard/contributions'); ?>" class="btn btn-app">
                            <i class="fa fa-money"></i> View Contributions
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?php echo base_url('partnerdashboard/profile'); ?>" class="btn btn-app">
                            <i class="fa fa-user"></i> Update Profile
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?php echo base_url('partnerdashboard/change-password'); ?>" class="btn btn-app">
                            <i class="fa fa-lock"></i> Change Password
                        </a>
                    </div>
                </div>
                
                <!-- Test Data Button (for development/testing) -->
                <?php if (empty($recent_contributions)): ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <h4><i class="icon fa fa-info"></i> No Contributions Yet</h4>
                            <p>You haven't made any contributions yet. To test the dashboard with sample data, you can add some sample contributions.</p>
                            <a href="<?php echo base_url('partnerdashboard/add-sample-contributions'); ?>" class="btn btn-info">
                                <i class="fa fa-plus"></i> Add Sample Contributions (Test)
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
