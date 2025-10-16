<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <i class="fa fa-pie-chart"></i> Giving Collection By Type Report
        <small>Financial analysis by giving types</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url(); ?>admin/partners">Partners</a></li>
        <li><a href="<?php echo base_url(); ?>admin/partner_reports">Reports</a></li>
        <li class="active">Giving Collection By Type</li>
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

    <!-- Summary Cards -->
    <div class="row">
        <?php foreach ($total_collections as $total): ?>
        <div class="col-lg-4 col-md-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?php echo $total->currency . ' ' . number_format($total->total_amount, 2); ?></h3>
                    <p>Total Collections (<?php echo $total->currency; ?>)</p>
                </div>
                <div class="icon">
                    <i class="fa fa-money"></i>
                </div>
                <div class="small-box-footer">
                    <?php echo $total->total_count; ?> contributions
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Report Results -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-chart-pie"></i> Giving Collection By Type Report
                        <small class="pull-right">Period: <?php echo date('d M Y', strtotime($start_date)) . ' - ' . date('d M Y', strtotime($end_date)); ?></small>
                    </h3>
                </div>
                <div class="box-body">
                    <?php if (!empty($collections)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Giving Type</th>
                                    <th>Type Code</th>
                                    <th>Contribution Count</th>
                                    <th>Total Amount</th>
                                    <th>Average Amount</th>
                                    <th>Currency</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $grand_total = 0;
                                foreach ($total_collections as $total) {
                                    $grand_total += $total->total_amount;
                                }
                                ?>
                                <?php foreach ($collections as $collection): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo $collection->type_name ? $collection->type_name : 'No Type Assigned'; ?></strong>
                                    </td>
                                    <td>
                                        <span class="label label-info"><?php echo $collection->type_code ? $collection->type_code : 'N/A'; ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-blue"><?php echo $collection->contribution_count; ?></span>
                                    </td>
                                    <td>
                                        <strong><?php echo $collection->currency . ' ' . number_format($collection->total_amount, 2); ?></strong>
                                    </td>
                                    <td>
                                        <?php echo $collection->currency . ' ' . number_format($collection->average_amount, 2); ?>
                                    </td>
                                    <td>
                                        <span class="label label-success"><?php echo $collection->currency; ?></span>
                                    </td>
                                    <td>
                                        <?php 
                                        $percentage = $grand_total > 0 ? ($collection->total_amount / $grand_total) * 100 : 0;
                                        ?>
                                        <div class="progress progress-xs">
                                            <div class="progress-bar progress-bar-success" style="width: <?php echo $percentage; ?>%"></div>
                                        </div>
                                        <small><?php echo number_format($percentage, 1); ?>%</small>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Chart Section -->
                    <div class="row" style="margin-top: 30px;">
                        <div class="col-md-6">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Collection Distribution</h3>
                                </div>
                                <div class="box-body">
                                    <canvas id="collectionChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box box-success">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Summary Statistics</h3>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Total Giving Types:</strong> <?php echo count($collections); ?></p>
                                            <p><strong>Total Contributions:</strong> <?php echo array_sum(array_column($collections, 'contribution_count')); ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Average per Type:</strong> <?php echo $grand_total > 0 ? number_format($grand_total / count($collections), 2) : '0'; ?></p>
                                            <p><strong>Highest Collection:</strong> 
                                                <?php 
                                                $highest = max(array_column($collections, 'total_amount'));
                                                echo $collections[array_search($highest, array_column($collections, 'total_amount'))]->currency . ' ' . number_format($highest, 2);
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fa fa-warning"></i> No collection data found for the selected period.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
$(document).ready(function() {
    // Create pie chart
    <?php if (!empty($collections)): ?>
    var ctx = document.getElementById('collectionChart').getContext('2d');
    var collectionChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: [
                <?php foreach ($collections as $collection): ?>
                '<?php echo $collection->type_name ? $collection->type_name : 'No Type'; ?>',
                <?php endforeach; ?>
            ],
            datasets: [{
                data: [
                    <?php foreach ($collections as $collection): ?>
                    <?php echo $collection->total_amount; ?>,
                    <?php endforeach; ?>
                ],
                backgroundColor: [
                    '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc',
                    '#d2d6de', '#ffc107', '#28a745', '#dc3545', '#6f42c1'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            }
        }
    });
    <?php endif; ?>
});
</script>
