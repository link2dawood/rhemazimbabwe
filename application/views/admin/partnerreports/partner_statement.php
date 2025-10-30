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
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">
                                <i class="fa fa-print"></i> Print
                            </button>
                        </div>
                    </div>

                    <div class="box-body">
                        <!-- Partner Selection -->
                        <form id="filterForm" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('partner'); ?> <small class="req">*</small></label>
                                        <select class="form-control" name="partner_id" id="partner_id" required>
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

                        <!-- Loading Indicator -->
                        <div id="loadingIndicator" style="display:none; text-align:center; padding:20px;">
                            <i class="fa fa-spinner fa-spin fa-3x"></i>
                            <p>Loading data...</p>
                        </div>

                        <!-- Statement Summary -->
                        <div id="statementSummary" style="display: none;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <h4><i class="fa fa-user"></i> <span id="partnerName"></span></h4>
                                        <p>
                                            <strong><?php echo $this->lang->line('partner_code'); ?>:</strong> <span id="partnerCode"></span><br>
                                            <strong>Currency:</strong> <span id="currency"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Summary Cards -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="info-box bg-aqua">
                                        <span class="info-box-icon"><i class="fa fa-list"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Transactions</span>
                                            <span class="info-box-number" id="totalTransactions">0</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-box bg-green">
                                        <span class="info-box-icon"><i class="fa fa-money"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Completed Amount</span>
                                            <span class="info-box-number" id="completedAmount">0.00</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-box bg-yellow">
                                        <span class="info-box-icon"><i class="fa fa-calculator"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Amount</span>
                                            <span class="info-box-number" id="totalAmountSummary">0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Statement Table -->
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('date'); ?></th>
                                            <th><?php echo $this->lang->line('receipt_no'); ?></th>
                                            <th><?php echo $this->lang->line('description'); ?></th>
                                            <th><?php echo $this->lang->line('payment_method'); ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('amount'); ?></th>
                                            <th><?php echo $this->lang->line('status'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="statementTableBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
var base_url = '<?php echo base_url() ?>';

$(document).ready(function() {
    // Initialize date pickers
    $('.date').datepicker({
        format: "<?php echo $this->customlib->getSchoolDateFormat() ?>",
        autoclose: true
    });

    // Search button
    $('#searchBtn').click(function() {
        var partner_id = $('#partner_id').val();
        if (!partner_id) {
            alert('Please select a partner');
            return;
        }
        loadStatementData();
    });
});

function loadStatementData() {
    var partner_id = $('#partner_id').val();

    // Show loading
    $('#loadingIndicator').show();
    $('#statementSummary').hide();

    // Get filter values
    var filterData = {
        partner_id: partner_id,
        date_from: $('#date_from').val(),
        date_to: $('#date_to').val()
    };

    // Make AJAX request
    $.ajax({
        url: base_url + "admin/partnerreports/getPartnerStatementData",
        type: "POST",
        data: filterData,
        dataType: 'json',
        success: function(response) {
            $('#loadingIndicator').hide();
            $('#statementSummary').show();

            if (response.summary) {
                // Update summary info
                $('#partnerName').text(response.summary.partner_name);
                $('#partnerCode').text(response.summary.partner_code);
                $('#currency').text(response.summary.currency);
                $('#totalTransactions').text(response.summary.total_transactions);
                $('#completedAmount').text(response.summary.completed_amount);
                $('#totalAmountSummary').text(response.summary.total_amount);
            }

            if (response.data && response.data.length > 0) {
                var html = '';

                $.each(response.data, function(index, row) {
                    html += '<tr>';
                    html += '<td>' + row[0] + '</td>'; // Date
                    html += '<td>' + row[1] + '</td>'; // Receipt No
                    html += '<td>' + row[2] + '</td>'; // Description/Notes
                    html += '<td>' + row[3] + '</td>'; // Payment Method
                    html += '<td class="text-right">' + row[4] + '</td>'; // Amount
                    html += '<td>' + row[5] + '</td>'; // Status
                    html += '</tr>';
                });

                $('#statementTableBody').html(html);
            } else {
                $('#statementTableBody').html('<tr><td colspan="6" class="text-center text-muted">No transactions found for this partner</td></tr>');
            }
        },
        error: function(xhr, status, error) {
            $('#loadingIndicator').hide();
            alert('Error loading statement: ' + error);
        }
    });
}
</script>
