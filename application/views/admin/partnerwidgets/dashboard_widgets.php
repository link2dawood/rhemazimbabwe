<?php
/**
 * Partner Dashboard Widgets
 * To be included in the main admin dashboard
 */

// Get partner statistics
$partner_stats = $this->Partner_model->getDashboardStats();
$contribution_stats = $this->Contribution_model->getMonthlyStats();

// Calculate percentages
$active_percentage = $partner_stats['total_partners'] > 0
    ? ($partner_stats['active_partners'] / $partner_stats['total_partners']) * 100
    : 0;

$monthly_target = $partner_stats['monthly_expected'];
$monthly_collected = $contribution_stats['month_total'];
$collection_percentage = $monthly_target > 0
    ? ($monthly_collected / $monthly_target) * 100
    : 0;
?>

<!-- Partner Active Status Widget -->
<?php if ($this->rbac->hasPrivilege('partners', 'can_view')) { ?>
<div class="<?php echo isset($std_graphclass) ? $std_graphclass : 'col-md-3'; ?>">
    <div class="topprograssstart shadow">
        <p class="mt5 clearfix font14">
            <i class="fa fa-handshake-o ftlayer"></i>
            <?php echo $this->lang->line('active_partners'); ?>
            <span class="pull-right">
                <?php echo $partner_stats['active_partners']; ?>/<?php echo $partner_stats['total_partners']; ?>
            </span>
        </p>
        <div class="progress-group">
            <div class="progress progress-minibar">
                <div class="progress-bar progress-bar-aqua" style="width: <?php echo $active_percentage; ?>%"></div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<!-- Monthly Collections Widget -->
<?php if ($this->rbac->hasPrivilege('partner_contributions', 'can_view')) { ?>
<div class="<?php echo isset($std_graphclass) ? $std_graphclass : 'col-md-3'; ?>">
    <div class="topprograssstart shadow">
        <p class="mt5 clearfix font14">
            <i class="fa fa-money ftlayer"></i>
            <?php echo $this->lang->line('partner_monthly_collections'); ?>
            <span class="pull-right">
                <?php echo $currency_symbol . number_format($monthly_collected, 2); ?>/<?php echo $currency_symbol . number_format($monthly_target, 2); ?>
            </span>
        </p>
        <div class="progress-group">
            <div class="progress progress-minibar">
                <div class="progress-bar progress-bar-green" style="width: <?php echo min($collection_percentage, 100); ?>%"></div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<!-- Partners with Outstanding Balance Widget -->
<?php if ($this->rbac->hasPrivilege('partner_reports', 'can_view')) { ?>
<div class="<?php echo isset($std_graphclass) ? $std_graphclass : 'col-md-3'; ?>">
    <div class="topprograssstart shadow">
        <p class="mt5 clearfix font14">
            <i class="fa fa-warning ftlayer"></i>
            <?php echo $this->lang->line('partners_with_outstanding'); ?>
            <span class="pull-right">
                <?php echo $partner_stats['partners_with_balance']; ?>
            </span>
        </p>
        <div class="progress-group">
            <div class="progress progress-minibar">
                <div class="progress-bar progress-bar-red" style="width: 100%"></div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<!-- Partner Statistics Box (Full Width) -->
<?php if ($this->rbac->hasPrivilege('partners', 'can_view')) { ?>
<div class="col-md-12">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-bar-chart"></i> <?php echo $this->lang->line('partner_statistics'); ?>
            </h3>
            <div class="box-tools pull-right">
                <a href="<?php echo base_url('admin/partnerreports') ?>" class="btn btn-sm btn-primary">
                    <i class="fa fa-line-chart"></i> <?php echo $this->lang->line('view_reports'); ?>
                </a>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <!-- Stats Boxes -->
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-aqua">
                        <span class="info-box-icon"><i class="fa fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><?php echo $this->lang->line('total_partners'); ?></span>
                            <span class="info-box-number"><?php echo $partner_stats['total_partners']; ?></span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                            <span class="progress-description">
                                <?php echo $partner_stats['new_this_month']; ?> <?php echo $this->lang->line('new_this_month'); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-green">
                        <span class="info-box-icon"><i class="fa fa-money"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><?php echo $this->lang->line('this_month_collections'); ?></span>
                            <span class="info-box-number"><?php echo $currency_symbol . number_format($monthly_collected, 2); ?></span>
                            <div class="progress">
                                <div class="progress-bar" style="width: <?php echo min($collection_percentage, 100); ?>%"></div>
                            </div>
                            <span class="progress-description">
                                <?php echo number_format($collection_percentage, 1); ?>% <?php echo $this->lang->line('of_target'); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-yellow">
                        <span class="info-box-icon"><i class="fa fa-calculator"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><?php echo $this->lang->line('total_contributions'); ?></span>
                            <span class="info-box-number"><?php echo $currency_symbol . number_format($partner_stats['total_contributions'], 2); ?></span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                            <span class="progress-description">
                                <?php echo $this->lang->line('all_time'); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-red">
                        <span class="info-box-icon"><i class="fa fa-warning"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><?php echo $this->lang->line('outstanding_balance'); ?></span>
                            <span class="info-box-number"><?php echo $currency_symbol . number_format($partner_stats['total_outstanding'], 2); ?></span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                            <span class="progress-description">
                                <?php echo $partner_stats['partners_with_balance']; ?> <?php echo $this->lang->line('partners'); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Charts -->
                <div class="col-md-6">
                    <h4><?php echo $this->lang->line('monthly_contribution_trend'); ?></h4>
                    <canvas id="partnerMonthlyChart" height="200"></canvas>
                </div>

                <div class="col-md-6">
                    <h4><?php echo $this->lang->line('giving_type_distribution'); ?></h4>
                    <canvas id="partnerTypeChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
$(document).ready(function() {
    // Get data from PHP
    var monthlyData = <?php echo json_encode($contribution_stats['monthly_trend']); ?>;
    var typeData = <?php echo json_encode($partner_stats['type_distribution']); ?>;

    // Monthly Trend Chart
    if (document.getElementById('partnerMonthlyChart')) {
        var ctx1 = document.getElementById('partnerMonthlyChart').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: monthlyData.labels,
                datasets: [{
                    label: '<?php echo $this->lang->line('contributions'); ?>',
                    data: monthlyData.amounts,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Type Distribution Chart
    if (document.getElementById('partnerTypeChart')) {
        var ctx2 = document.getElementById('partnerTypeChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: typeData.labels,
                datasets: [{
                    data: typeData.counts,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(153, 102, 255, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true
            }
        });
    }
});
</script>