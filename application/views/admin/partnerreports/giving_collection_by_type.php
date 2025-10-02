<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-pie-chart"></i> <?php echo $this->lang->line('giving_collection_by_type_report'); ?></h1>
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

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('date_from'); ?></label>
                                        <input type="text" class="form-control date" name="date_from" id="date_from" placeholder="<?php echo $this->lang->line('start_date'); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('date_to'); ?></label>
                                        <input type="text" class="form-control date" name="date_to" id="date_to" placeholder="<?php echo $this->lang->line('end_date'); ?>">
                                    </div>
                                </div>

                                <div class="col-md-2">
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

                        <!-- Summary Cards -->
                        <div class="row" id="summaryCards">
                            <div class="col-md-3 col-sm-6">
                                <div class="info-box bg-aqua">
                                    <span class="info-box-icon"><i class="fa fa-users"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"><?php echo $this->lang->line('total_partners'); ?></span>
                                        <span class="info-box-number" id="totalPartners">0</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <div class="info-box bg-green">
                                    <span class="info-box-icon"><i class="fa fa-money"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"><?php echo $this->lang->line('total_collections'); ?></span>
                                        <span class="info-box-number" id="totalCollections"><?php echo $currency_symbol ?> 0.00</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <div class="info-box bg-yellow">
                                    <span class="info-box-icon"><i class="fa fa-list"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"><?php echo $this->lang->line('total_transactions'); ?></span>
                                        <span class="info-box-number" id="totalTransactions">0</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <div class="info-box bg-red">
                                    <span class="info-box-icon"><i class="fa fa-calculator"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"><?php echo $this->lang->line('average_per_partner'); ?></span>
                                        <span class="info-box-number" id="avgPerPartner"><?php echo $currency_symbol ?> 0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chart -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box box-success">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><?php echo $this->lang->line('collection_by_type_chart'); ?></h3>
                                    </div>
                                    <div class="box-body">
                                        <canvas id="typeChart" height="100"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Report Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="collectionTable">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('giving_type'); ?></th>
                                        <th><?php echo $this->lang->line('number_of_partners'); ?></th>
                                        <th><?php echo $this->lang->line('number_of_transactions'); ?></th>
                                        <th><?php echo $this->lang->line('total_amount'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th><?php echo $this->lang->line('total'); ?></th>
                                        <th id="footerPartners"></th>
                                        <th id="footerTransactions"></th>
                                        <th id="footerAmount"></th>
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
var collectionTable;
var typeChart;

$(document).ready(function() {
    // Initialize date pickers
    $('.date').datepicker({
        format: "<?php echo $this->customlib->getSchoolDateFormat() ?>",
        autoclose: true
    });

    // Initialize DataTable
    initDataTable();

    // Initialize Chart
    initChart();

    // Search button
    $('#searchBtn').click(function() {
        collectionTable.ajax.reload(null, false);
    });

    // Export buttons
    $('#exportExcel').click(function() {
        collectionTable.button('.buttons-excel').trigger();
    });

    $('#exportPdf').click(function() {
        window.location.href = base_url + 'admin/partnerreports/exportPdf/giving_collection_by_type';
    });
});

function initDataTable() {
    collectionTable = $('#collectionTable').DataTable({
        "processing": true,
        "serverSide": false,
        "ajax": {
            "url": base_url + "admin/partnerreports/getGivingCollectionByTypeData",
            "type": "POST",
            "data": function(d) {
                d.giving_type_id = $('#giving_type_id').val();
                d.date_from = $('#date_from').val();
                d.date_to = $('#date_to').val();
            },
            "dataSrc": function(json) {
                updateSummary(json.data);
                updateChart(json.data);
                return json.data;
            }
        },
        "columns": [
            {"data": 0},
            {"data": 1, "className": "text-center"},
            {"data": 2, "className": "text-center"},
            {"data": 3, "className": "text-right"}
        ],
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
                title: 'Giving Collection By Type Report',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fa fa-file-pdf-o"></i>',
                titleAttr: 'PDF',
                title: 'Giving Collection By Type Report',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ]
    });
}

function calculateFooter() {
    var api = collectionTable;
    var totalPartners = 0;
    var totalTransactions = 0;
    var totalAmount = 0;

    api.rows().every(function() {
        var data = this.data();
        totalPartners += parseInt(data[1]) || 0;
        totalTransactions += parseInt(data[2]) || 0;
        // Extract number from formatted amount string
        var amount = data[3].replace(/[^0-9.-]+/g, "");
        totalAmount += parseFloat(amount) || 0;
    });

    $('#footerPartners').html(totalPartners);
    $('#footerTransactions').html(totalTransactions);
    $('#footerAmount').html('USD ' + totalAmount.toFixed(2));
}

function updateSummary(data) {
    var totalPartners = 0;
    var totalTransactions = 0;
    var totalAmount = 0;

    data.forEach(function(row) {
        totalPartners += parseInt(row[1]) || 0;
        totalTransactions += parseInt(row[2]) || 0;
        var amount = row[3].replace(/[^0-9.-]+/g, "");
        totalAmount += parseFloat(amount) || 0;
    });

    var avgPerPartner = totalPartners > 0 ? totalAmount / totalPartners : 0;

    $('#totalPartners').text(totalPartners);
    $('#totalCollections').text('USD ' + totalAmount.toFixed(2));
    $('#totalTransactions').text(totalTransactions);
    $('#avgPerPartner').text('USD ' + avgPerPartner.toFixed(2));
}

function initChart() {
    var ctx = document.getElementById('typeChart').getContext('2d');
    typeChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Total Amount',
                data: [],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
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

function updateChart(data) {
    var labels = [];
    var amounts = [];

    data.forEach(function(row) {
        labels.push(row[0]);
        var amount = row[3].replace(/[^0-9.-]+/g, "");
        amounts.push(parseFloat(amount) || 0);
    });

    typeChart.data.labels = labels;
    typeChart.data.datasets[0].data = amounts;
    typeChart.update();
}
</script>