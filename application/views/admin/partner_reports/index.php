<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <i class="fa fa-bar-chart"></i> Partner Reports
        <small>Generate and view partner reports</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url(); ?>admin/partners">Partners</a></li>
        <li class="active">Reports</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <!-- Partner Information Report Card -->
        <div class="col-lg-6 col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-users"></i> Partner Information Report</h3>
                </div>
                <div class="box-body">
                    <p>Generate a comprehensive report of all partner information including contact details, giving preferences, and status.</p>
                    <ul class="list-unstyled">
                        <li><i class="fa fa-check text-green"></i> Partner contact information</li>
                        <li><i class="fa fa-check text-green"></i> Giving types and frequencies</li>
                        <li><i class="fa fa-check text-green"></i> Account status and permissions</li>
                        <li><i class="fa fa-check text-green"></i> Registration dates and notes</li>
                    </ul>
                </div>
                <div class="box-footer">
                    <a href="<?php echo base_url(); ?>admin/partner_reports/partner_information" class="btn btn-primary btn-block">
                        <i class="fa fa-file-text"></i> Generate Report
                    </a>
                </div>
            </div>
        </div>

        <!-- Giving Collection By Type Report Card -->
        <div class="col-lg-6 col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-pie-chart"></i> Giving Collection By Type Report</h3>
                </div>
                <div class="box-body">
                    <p>Analyze giving collections categorized by giving types with detailed breakdowns and statistics.</p>
                    <ul class="list-unstyled">
                        <li><i class="fa fa-check text-green"></i> Collection amounts by type</li>
                        <li><i class="fa fa-check text-green"></i> Contribution counts and averages</li>
                        <li><i class="fa fa-check text-green"></i> Date range filtering</li>
                        <li><i class="fa fa-check text-green"></i> Currency breakdown</li>
                    </ul>
                </div>
                <div class="box-footer">
                    <a href="<?php echo base_url(); ?>admin/partner_reports/giving_collection_by_type" class="btn btn-success btn-block">
                        <i class="fa fa-chart-pie"></i> Generate Report
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Partner Statement Report Card -->
        <div class="col-lg-6 col-md-6">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-file-text-o"></i> Partner Statement Report</h3>
                </div>
                <div class="box-body">
                    <p>Generate detailed financial statements for individual partners showing contributions and balances.</p>
                    <ul class="list-unstyled">
                        <li><i class="fa fa-check text-green"></i> Individual partner statements</li>
                        <li><i class="fa fa-check text-green"></i> Contribution history</li>
                        <li><i class="fa fa-check text-green"></i> Balance calculations</li>
                        <li><i class="fa fa-check text-green"></i> Date range filtering</li>
                    </ul>
                </div>
                <div class="box-footer">
                    <a href="<?php echo base_url(); ?>admin/partner_reports/partner_statement" class="btn btn-warning btn-block">
                        <i class="fa fa-file-text"></i> Generate Report
                    </a>
                </div>
            </div>
        </div>

        <!-- Balance Giving Report Card -->
        <div class="col-lg-6 col-md-6">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-exclamation-triangle"></i> Balance Giving Report with Remark</h3>
                </div>
                <div class="box-body">
                    <p>Monitor partner giving balances and identify partners who are behind on their contributions.</p>
                    <ul class="list-unstyled">
                        <li><i class="fa fa-check text-green"></i> Balance status tracking</li>
                        <li><i class="fa fa-check text-green"></i> Automated remarks</li>
                        <li><i class="fa fa-check text-green"></i> Priority identification</li>
                        <li><i class="fa fa-check text-green"></i> Follow-up recommendations</li>
                    </ul>
                </div>
                <div class="box-footer">
                    <a href="<?php echo base_url(); ?>admin/partner_reports/balance_giving_report" class="btn btn-danger btn-block">
                        <i class="fa fa-balance-scale"></i> Generate Report
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Panel -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-info-circle"></i> Report Information</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h5><i class="fa fa-users text-primary"></i> Partner Information Report</h5>
                            <p class="text-muted">Comprehensive partner data including contact information, giving preferences, and account status. Useful for partner management and communication.</p>
                        </div>
                        <div class="col-md-3">
                            <h5><i class="fa fa-pie-chart text-success"></i> Giving Collection By Type</h5>
                            <p class="text-muted">Financial analysis of contributions categorized by giving types. Helps identify which areas receive the most support.</p>
                        </div>
                        <div class="col-md-3">
                            <h5><i class="fa fa-file-text-o text-warning"></i> Partner Statement</h5>
                            <p class="text-muted">Individual partner financial statements showing contribution history, balances, and payment status for specific date ranges.</p>
                        </div>
                        <div class="col-md-3">
                            <h5><i class="fa fa-exclamation-triangle text-danger"></i> Balance Giving Report</h5>
                            <p class="text-muted">Monitor partner giving balances to identify those behind on contributions and prioritize follow-up actions.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Features -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-cogs"></i> Report Features</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h5><i class="fa fa-filter text-blue"></i> Advanced Filtering</h5>
                            <ul class="list-unstyled">
                                <li>• Date range selection</li>
                                <li>• Status filtering</li>
                                <li>• Giving type filtering</li>
                                <li>• Account type filtering</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h5><i class="fa fa-file-pdf-o text-red"></i> PDF Export</h5>
                            <ul class="list-unstyled">
                                <li>• Professional PDF formatting</li>
                                <li>• School branding</li>
                                <li>• Print-ready layouts</li>
                                <li>• Automatic filename generation</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h5><i class="fa fa-chart-line text-green"></i> Data Analysis</h5>
                            <ul class="list-unstyled">
                                <li>• Summary statistics</li>
                                <li>• Trend analysis</li>
                                <li>• Balance calculations</li>
                                <li>• Automated remarks</li>
                            </ul>
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
    
    // Add hover effects to report cards
    $('.box').hover(
        function() {
            $(this).addClass('box-shadow');
        },
        function() {
            $(this).removeClass('box-shadow');
        }
    );
});
</script>

<style>
.box-shadow {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
    transition: box-shadow 0.3s ease;
}

.box {
    transition: box-shadow 0.3s ease;
}

.list-unstyled li {
    margin-bottom: 5px;
}
</style>
