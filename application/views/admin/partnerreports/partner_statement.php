<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('partner_statement_report'); ?></h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('select_partner'); ?></h3>
                    </div>

                    <div class="box-body">
                        <!-- Partner Selection -->
                        <form id="filterForm" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('partner'); ?> <small class="req">*</small></label>
                                        <select class="form-control select2" name="partner_id" id="partner_id" required>
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($partners as $partner) { ?>
                                                <option value="<?php echo $partner->id ?>">
                                                    <?php echo $partner->partner_code . ' - ' . $partner->firstname . ' ' . $partner->lastname ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('date_from'); ?></label>
                                        <input type="text" class="form-control date" name="date_from" id="date_from">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('date_to'); ?></label>
                                        <input type="text" class="form-control date" name="date_to" id="date_to">
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-primary btn-block" id="searchBtn">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <hr>

                        <!-- Statement Summary -->
                        <div id="statementSummary" style="display: none;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <h4><i class="fa fa-user"></i> <span id="partnerName"></span></h4>
                                        <p>
                                            <strong><?php echo $this->lang->line('partner_code'); ?>:</strong> <span id="partnerCode"></span><br>
                                            <strong><?php echo $this->lang->line('statement_period'); ?>:</strong>
                                            <span id="statementPeriod"><?php echo $this->lang->line('all_time'); ?></span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Summary Cards -->
                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    <div class="info-box bg-aqua">
                                        <span class="info-box-icon"><i class="fa fa-list"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><?php echo $this->lang->line('total_transactions'); ?></span>
                                            <span class="info-box-number" id="totalTransactions">0</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="info-box bg-yellow">
                                        <span class="info-box-icon"><i class="fa fa-money"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><?php echo $this->lang->line('total_amount'); ?></span>
                                            <span class="info-box-number" id="totalAmount"><?php echo $currency_symbol ?> 0.00</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="info-box bg-green">
                                        <span class="info-box-icon"><i class="fa fa-check"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><?php echo $this->lang->line('completed_amount'); ?></span>
                                            <span class="info-box-number" id="completedAmount"><?php echo $currency_symbol ?> 0.00</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="info-box bg-red">
                                        <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><?php echo $this->lang->line('pending_amount'); ?></span>
                                            <span class="info-box-number" id="pendingAmount"><?php echo $currency_symbol ?> 0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="button" class="btn btn-primary" id="exportExcel">
                                        <i class="fa fa-file-excel-o"></i> <?php echo $this->lang->line('export_to_excel'); ?>
                                    </button>
                                    <button type="button" class="btn btn-danger" id="exportPdf">
                                        <i class="fa fa-file-pdf-o"></i> <?php echo $this->lang->line('export_to_pdf'); ?>
                                    </button>
                                    <button type="button" class="btn btn-default" id="printStatement">
                                        <i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?>
                                    </button>
                                </div>
                            </div>

                            <br>

                            <!-- Statement Table -->
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="statementTable">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('date'); ?></th>
                                            <th><?php echo $this->lang->line('receipt_no'); ?></th>
                                            <th><?php echo $this->lang->line('description'); ?></th>
                                            <th><?php echo $this->lang->line('payment_method'); ?></th>
                                            <th><?php echo $this->lang->line('amount'); ?></th>
                                            <th><?php echo $this->lang->line('status'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div id="emptyState" class="text-center" style="padding: 50px;">
                            <i class="fa fa-file-text-o" style="font-size: 64px; color: #ddd;"></i>
                            <h4><?php echo $this->lang->line('select_partner_to_view_statement'); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
var base_url = '<?php echo base_url() ?>';
var statementTable;

$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2();

    // Initialize date pickers
    $('.date').datepicker({
        format: "<?php echo $this->customlib->getSchoolDateFormat() ?>",
        autoclose: true
    });

    // Initialize DataTable
    statementTable = $('#statementTable').DataTable({
        "data": [],
        "columns": [
            {"data": 0},
            {"data": 1},
            {"data": 2},
            {"data": 3},
            {"data": 4, "className": "text-right"},
            {"data": 5}
        ],
        "responsive": true,
        "autoWidth": false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel-o"></i>',
                titleAttr: 'Excel',
                title: 'Partner Statement',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fa fa-file-pdf-o"></i>',
                titleAttr: 'PDF',
                title: 'Partner Statement',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ]
    });

    // Search button
    $('#searchBtn').click(function() {
        loadStatement();
    });

    // Export buttons
    $('#exportExcel').click(function() {
        statementTable.button('.buttons-excel').trigger();
    });

    $('#exportPdf').click(function() {
        var partner_id = $('#partner_id').val();
        if (partner_id) {
            window.location.href = base_url + 'admin/partnerreports/exportPdf/partner_statement?partner_id=' + partner_id;
        }
    });

    $('#printStatement').click(function() {
        statementTable.button('.buttons-print').trigger();
    });
});

function loadStatement() {
    var partner_id = $('#partner_id').val();

    if (!partner_id) {
        alert('<?php echo $this->lang->line('please_select_partner'); ?>');
        return;
    }

    $.ajax({
        url: base_url + 'admin/partnerreports/getPartnerStatementData',
        type: 'POST',
        data: {
            partner_id: partner_id,
            date_from: $('#date_from').val(),
            date_to: $('#date_to').val()
        },
        dataType: 'json',
        success: function(response) {
            if (response.data && response.summary) {
                // Update summary
                $('#partnerName').text(response.summary.partner_name);
                $('#partnerCode').text(response.summary.partner_code);
                $('#totalTransactions').text(response.summary.total_transactions);
                $('#totalAmount').text(response.summary.currency + ' ' + response.summary.total_amount);
                $('#completedAmount').text(response.summary.currency + ' ' + response.summary.completed_amount);
                $('#pendingAmount').text(response.summary.currency + ' ' + response.summary.pending_amount);

                // Update period
                var period = '<?php echo $this->lang->line('all_time'); ?>';
                if ($('#date_from').val() && $('#date_to').val()) {
                    period = $('#date_from').val() + ' to ' + $('#date_to').val();
                } else if ($('#date_from').val()) {
                    period = 'From ' + $('#date_from').val();
                } else if ($('#date_to').val()) {
                    period = 'Up to ' + $('#date_to').val();
                }
                $('#statementPeriod').text(period);

                // Update table
                statementTable.clear();
                statementTable.rows.add(response.data);
                statementTable.draw();

                // Show statement
                $('#emptyState').hide();
                $('#statementSummary').show();
            }
        },
        error: function() {
            alert('<?php echo $this->lang->line('error_loading_statement'); ?>');
        }
    });
}
</script>