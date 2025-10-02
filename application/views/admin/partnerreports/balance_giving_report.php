<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-balance-scale"></i> <?php echo $this->lang->line('balance_giving_report'); ?></h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('filter_criteria'); ?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-primary btn-sm" id="exportExcel">
                                <i class="fa fa-file-excel-o"></i> <?php echo $this->lang->line('export_to_excel'); ?>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" id="exportPdf">
                                <i class="fa fa-file-pdf-o"></i> <?php echo $this->lang->line('export_to_pdf'); ?>
                            </button>
                        </div>
                    </div>

                    <div class="box-body">
                        <!-- Filters -->
                        <form id="filterForm" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('giving_type'); ?></label>
                                        <select class="form-control" name="giving_type_id" id="giving_type_id">
                                            <option value=""><?php echo $this->lang->line('all'); ?></option>
                                            <?php foreach ($giving_types as $type) { ?>
                                                <option value="<?php echo $type->id ?>"><?php echo $type->name ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('giving_frequency'); ?></label>
                                        <select class="form-control" name="giving_frequency_id" id="giving_frequency_id">
                                            <option value=""><?php echo $this->lang->line('all'); ?></option>
                                            <?php foreach ($giving_frequencies as $frequency) { ?>
                                                <option value="<?php echo $frequency->id ?>"><?php echo $frequency->name ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-primary btn-block" id="searchBtn">
                                            <i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <hr>

                        <!-- Alert Box -->
                        <div class="alert alert-warning">
                            <i class="fa fa-info-circle"></i>
                            <strong><?php echo $this->lang->line('note'); ?>:</strong>
                            <?php echo $this->lang->line('balance_report_note'); ?>
                        </div>

                        <!-- Summary Cards -->
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="info-box bg-aqua">
                                    <span class="info-box-icon"><i class="fa fa-users"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"><?php echo $this->lang->line('partners_with_balance'); ?></span>
                                        <span class="info-box-number" id="partnersWithBalance">0</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <div class="info-box bg-yellow">
                                    <span class="info-box-icon"><i class="fa fa-calculator"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"><?php echo $this->lang->line('total_expected'); ?></span>
                                        <span class="info-box-number" id="totalExpected"><?php echo $currency_symbol ?> 0.00</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <div class="info-box bg-green">
                                    <span class="info-box-icon"><i class="fa fa-check"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"><?php echo $this->lang->line('total_contributed'); ?></span>
                                        <span class="info-box-number" id="totalContributed"><?php echo $currency_symbol ?> 0.00</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <div class="info-box bg-red">
                                    <span class="info-box-icon"><i class="fa fa-warning"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"><?php echo $this->lang->line('total_balance'); ?></span>
                                        <span class="info-box-number" id="totalBalance"><?php echo $currency_symbol ?> 0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chart -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="box box-danger">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><?php echo $this->lang->line('balance_by_remark'); ?></h3>
                                    </div>
                                    <div class="box-body">
                                        <canvas id="remarkChart" height="200"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="box box-warning">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><?php echo $this->lang->line('top_10_outstanding_partners'); ?></h3>
                                    </div>
                                    <div class="box-body">
                                        <canvas id="topPartnersChart" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Legend -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box box-solid">
                                    <div class="box-header">
                                        <h3 class="box-title"><?php echo $this->lang->line('remark_legend'); ?></h3>
                                    </div>
                                    <div class="box-body">
                                        <span class="label label-danger">Critical - 75%+ Outstanding</span>
                                        <span class="label label-warning">High - 50-74% Outstanding</span>
                                        <span class="label label-info">Moderate - 25-49% Outstanding</span>
                                        <span class="label label-success">Low - 0-24% Outstanding</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Report Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="balanceTable">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('partner_code'); ?></th>
                                        <th><?php echo $this->lang->line('partner_name'); ?></th>
                                        <th><?php echo $this->lang->line('email'); ?></th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th><?php echo $this->lang->line('giving_type'); ?></th>
                                        <th><?php echo $this->lang->line('frequency'); ?></th>
                                        <th><?php echo $this->lang->line('expected_amount'); ?></th>
                                        <th><?php echo $this->lang->line('contributed_amount'); ?></th>
                                        <th><?php echo $this->lang->line('balance'); ?></th>
                                        <th><?php echo $this->lang->line('remark'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" class="text-right"><?php echo $this->lang->line('total'); ?>:</th>
                                        <th id="footerExpected"></th>
                                        <th id="footerContributed"></th>
                                        <th id="footerBalance"></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
var base_url = '<?php echo base_url() ?>';
var balanceTable;
var remarkChart;
var topPartnersChart;

$(document).ready(function() {
    // Initialize DataTable
    initDataTable();

    // Initialize Charts
    initCharts();

    // Search button
    $('#searchBtn').click(function() {
        balanceTable.ajax.reload(null, false);
    });

    // Export buttons
    $('#exportExcel').click(function() {
        balanceTable.button('.buttons-excel').trigger();
    });

    $('#exportPdf').click(function() {
        window.location.href = base_url + 'admin/partnerreports/exportPdf/balance_giving';
    });
});

function initDataTable() {
    balanceTable = $('#balanceTable').DataTable({
        "processing": true,
        "serverSide": false,
        "ajax": {
            "url": base_url + "admin/partnerreports/getBalanceGivingData",
            "type": "POST",
            "data": function(d) {
                d.giving_type_id = $('#giving_type_id').val();
                d.giving_frequency_id = $('#giving_frequency_id').val();
            },
            "dataSrc": function(json) {
                updateSummary(json.data);
                updateCharts(json.data);
                return json.data;
            }
        },
        "columns": [
            {"data": 0},
            {"data": 1},
            {"data": 2},
            {"data": 3},
            {"data": 4},
            {"data": 5},
            {"data": 6, "className": "text-right"},
            {"data": 7, "className": "text-right"},
            {"data": 8, "className": "text-right"},
            {"data": 9}
        ],
        "order": [[8, 'desc']],
        "responsive": true,
        "autoWidth": false,
        "drawCallback": function(settings) {
            calculateFooter();
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel-o"></i>',
                titleAttr: 'Excel',
                title: 'Balance Giving Report',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fa fa-file-pdf-o"></i>',
                titleAttr: 'PDF',
                title: 'Balance Giving Report',
                orientation: 'landscape',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ]
    });
}

function calculateFooter() {
    var api = balanceTable;
    var totalExpected = 0;
    var totalContributed = 0;
    var totalBalance = 0;

    api.rows().every(function() {
        var data = this.data();
        totalExpected += parseFloat(data[6].replace(/[^0-9.-]+/g, "")) || 0;
        totalContributed += parseFloat(data[7].replace(/[^0-9.-]+/g, "")) || 0;
        totalBalance += parseFloat(data[8].replace(/[^0-9.-]+/g, "").replace(/<[^>]*>/g, '')) || 0;
    });

    $('#footerExpected').html('USD ' + totalExpected.toFixed(2));
    $('#footerContributed').html('USD ' + totalContributed.toFixed(2));
    $('#footerBalance').html('<strong class="text-danger">USD ' + totalBalance.toFixed(2) + '</strong>');
}

function updateSummary(data) {
    var totalExpected = 0;
    var totalContributed = 0;
    var totalBalance = 0;

    data.forEach(function(row) {
        totalExpected += parseFloat(row[6].replace(/[^0-9.-]+/g, "")) || 0;
        totalContributed += parseFloat(row[7].replace(/[^0-9.-]+/g, "")) || 0;
        totalBalance += parseFloat(row[8].replace(/[^0-9.-]+/g, "").replace(/<[^>]*>/g, '')) || 0;
    });

    $('#partnersWithBalance').text(data.length);
    $('#totalExpected').text('USD ' + totalExpected.toFixed(2));
    $('#totalContributed').text('USD ' + totalContributed.toFixed(2));
    $('#totalBalance').text('USD ' + totalBalance.toFixed(2));
}

function initCharts() {
    // Remark Chart
    var ctx1 = document.getElementById('remarkChart').getContext('2d');
    remarkChart = new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: [
                    'rgba(217, 83, 79, 0.8)',   // Danger/Critical
                    'rgba(240, 173, 78, 0.8)',  // Warning/High
                    'rgba(91, 192, 222, 0.8)',  // Info/Moderate
                    'rgba(92, 184, 92, 0.8)'    // Success/Low
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true
        }
    });

    // Top Partners Chart
    var ctx2 = document.getElementById('topPartnersChart').getContext('2d');
    topPartnersChart = new Chart(ctx2, {
        type: 'horizontalBar',
        data: {
            labels: [],
            datasets: [{
                label: 'Outstanding Balance',
                data: [],
                backgroundColor: 'rgba(217, 83, 79, 0.6)',
                borderColor: 'rgba(217, 83, 79, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });
}

function updateCharts(data) {
    // Count by remark category
    var remarkCounts = {
        'Critical': 0,
        'High': 0,
        'Moderate': 0,
        'Low': 0
    };

    var partnerBalances = [];

    data.forEach(function(row) {
        var remarkHtml = row[9];
        if (remarkHtml.includes('Critical')) {
            remarkCounts['Critical']++;
        } else if (remarkHtml.includes('High')) {
            remarkCounts['High']++;
        } else if (remarkHtml.includes('Moderate')) {
            remarkCounts['Moderate']++;
        } else if (remarkHtml.includes('Low')) {
            remarkCounts['Low']++;
        }

        partnerBalances.push({
            name: row[1],
            balance: parseFloat(row[8].replace(/[^0-9.-]+/g, "").replace(/<[^>]*>/g, '')) || 0
        });
    });

    // Update remark chart
    remarkChart.data.labels = Object.keys(remarkCounts);
    remarkChart.data.datasets[0].data = Object.values(remarkCounts);
    remarkChart.update();

    // Update top partners chart (top 10)
    partnerBalances.sort((a, b) => b.balance - a.balance);
    var top10 = partnerBalances.slice(0, 10);

    topPartnersChart.data.labels = top10.map(p => p.name);
    topPartnersChart.data.datasets[0].data = top10.map(p => p.balance);
    topPartnersChart.update();
}
</script>