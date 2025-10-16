<!-- Partner Module Widget for Admin Dashboard -->
<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-handshake text-primary"></i>
                Partner Module Overview
            </h3>
            <div class="card-tools">
                <a href="<?php echo base_url('admin/partners'); ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-eye"></i> View All
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Key Statistics -->
            <div class="row">
                <div class="col-md-3">
                    <div class="info-box bg-primary">
                        <span class="info-box-icon">
                            <i class="fas fa-users"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Partners</span>
                            <span class="info-box-number"><?php echo $partner_stats['total_partners']; ?></span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                            <span class="progress-description">
                                <?php echo $partner_stats['active_partners']; ?> Active
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="info-box bg-success">
                        <span class="info-box-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Contributions</span>
                            <span class="info-box-number">$<?php echo number_format($partner_stats['total_contributions'], 2); ?></span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                            <span class="progress-description">
                                This Month: $<?php echo number_format($partner_stats['monthly_contributions'], 2); ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="info-box bg-warning">
                        <span class="info-box-icon">
                            <i class="fas fa-clock"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pending Approvals</span>
                            <span class="info-box-number"><?php echo $partner_stats['pending_approvals']; ?></span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                            <span class="progress-description">
                                New registrations
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="info-box bg-info">
                        <span class="info-box-icon">
                            <i class="fas fa-chart-line"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Growth Rate</span>
                            <span class="info-box-number"><?php echo $partner_stats['growth_rate']; ?>%</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                            <span class="progress-description">
                                This Month
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row mt-4">
                <div class="col-md-8">
                    <h5>
                        <i class="fas fa-history text-primary"></i>
                        Recent Partner Activity
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Partner</th>
                                    <th>Activity</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recent_activities)): ?>
                                    <?php foreach ($recent_activities as $activity): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $activity->partner_name; ?></strong>
                                            <br><small class="text-muted"><?php echo $activity->partner_code; ?></small>
                                        </td>
                                        <td><?php echo $activity->activity_description; ?></td>
                                        <td><?php echo date('M j, Y', strtotime($activity->created_at)); ?></td>
                                        <td>
                                            <?php if ($activity->activity_type === 'registration'): ?>
                                                <span class="badge bg-warning">New</span>
                                            <?php elseif ($activity->activity_type === 'contribution'): ?>
                                                <span class="badge bg-success">Contribution</span>
                                            <?php else: ?>
                                                <span class="badge bg-info"><?php echo ucfirst($activity->activity_type); ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No recent activity</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <h5>
                        <i class="fas fa-tasks text-success"></i>
                        Quick Actions
                    </h5>
                    <div class="list-group">
                        <a href="<?php echo base_url('admin/partners/add'); ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus text-primary"></i>
                            Add New Partner
                        </a>
                        <a href="<?php echo base_url('admin/partner_reports'); ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-bar text-info"></i>
                            View Reports
                        </a>
                        <a href="<?php echo base_url('admin/partner_settings'); ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-cog text-warning"></i>
                            Manage Settings
                        </a>
                        <a href="<?php echo base_url('admin/partners?status=pending'); ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-clock text-warning"></i>
                            Review Pending
                        </a>
                    </div>
                    
                    <h5 class="mt-4">
                        <i class="fas fa-chart-pie text-info"></i>
                        Giving Types Distribution
                    </h5>
                    <div class="chart-container">
                        <canvas id="givingTypesChart" width="300" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Partner Status Overview -->
            <div class="row mt-4">
                <div class="col-12">
                    <h5>
                        <i class="fas fa-chart-bar text-primary"></i>
                        Partner Status Overview
                    </h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h3 class="text-success"><?php echo $partner_stats['active_partners']; ?></h3>
                                    <p class="mb-0">Active Partners</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h3 class="text-warning"><?php echo $partner_stats['pending_partners']; ?></h3>
                                    <p class="mb-0">Pending Approval</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h3 class="text-danger"><?php echo $partner_stats['suspended_partners']; ?></h3>
                                    <p class="mb-0">Suspended</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h3 class="text-info"><?php echo $partner_stats['individual_partners']; ?></h3>
                                    <p class="mb-0">Individual</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Giving Types Chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('givingTypesChart').getContext('2d');
    const givingTypesData = <?php echo json_encode($giving_types_data); ?>;
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: givingTypesData.labels,
            datasets: [{
                data: givingTypesData.data,
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
