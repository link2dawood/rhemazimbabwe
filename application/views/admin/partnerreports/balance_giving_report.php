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
                            <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">
                                <i class="fa fa-print"></i> Print
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

                        <!-- Loading Indicator -->
                        <div id="loadingIndicator" style="display:none; text-align:center; padding:20px;">
                            <i class="fa fa-spinner fa-spin fa-3x"></i>
                            <p>Loading data...</p>
                        </div>

                        <!-- Report Table -->
                        <div class="table-responsive" id="reportTableContainer">
                            <table class="table table-striped table-bordered table-hover" id="balanceTable">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('partner_code'); ?></th>
                                        <th><?php echo $this->lang->line('partner_name'); ?></th>
                                        <th><?php echo $this->lang->line('email'); ?></th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th><?php echo $this->lang->line('giving_type'); ?></th>
                                        <th><?php echo $this->lang->line('frequency'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('expected_amount'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('total_contributed'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('balance'); ?></th>
                                        <th><?php echo $this->lang->line('remark'); ?></th>
                                    </tr>
                                </thead>
                                <tbody id="reportTableBody">
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">
                                            Click "Search" button to load data
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot id="reportTableFooter" style="display:none;">
                                    <tr>
                                        <th colspan="6" class="text-right">Total:</th>
                                        <th class="text-right" id="totalExpected">0.00</th>
                                        <th class="text-right" id="totalContributed">0.00</th>
                                        <th class="text-right" id="totalBalance">0.00</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Record Count -->
                        <div class="row" id="recordInfo" style="display:none;">
                            <div class="col-sm-12">
                                <p class="text-muted">
                                    Showing <span id="recordCount">0</span> partner(s) with balance
                                </p>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h4><i class="fa fa-info-circle"></i> About This Report</h4>
                            <p><strong>This report shows partners who have outstanding contributions based on their pledge amount and frequency.</strong></p>
                            <p>Only partners with a balance greater than 0 are displayed.</p>
                            <p><strong>Remark Legend:</strong></p>
                            <ul>
                                <li><span class="label label-danger">Critical</span> - Balance exceeds 75% of expected amount</li>
                                <li><span class="label label-warning">High</span> - Balance between 50-75% of expected amount</li>
                                <li><span class="label label-info">Moderate</span> - Balance between 25-50% of expected amount</li>
                                <li><span class="label label-success">Low</span> - Balance less than 25% of expected amount</li>
                            </ul>
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
    // Search button
    $('#searchBtn').click(function() {
        loadReportData();
    });

    // Load data on page load
    loadReportData();
});

function loadReportData() {
    // Show loading
    $('#loadingIndicator').show();
    $('#reportTableContainer').hide();
    $('#recordInfo').hide();

    // Get filter values
    var filterData = {
        giving_type_id: $('#giving_type_id').val(),
        giving_frequency_id: $('#giving_frequency_id').val()
    };

    // Make AJAX request
    $.ajax({
        url: base_url + "admin/partnerreports/getBalanceGivingData",
        type: "POST",
        data: filterData,
        dataType: 'json',
        success: function(response) {
            $('#loadingIndicator').hide();
            $('#reportTableContainer').show();

            if (response.data && response.data.length > 0) {
                var html = '';
                var totalExpected = 0;
                var totalContributed = 0;
                var totalBalance = 0;

                $.each(response.data, function(index, row) {
                    html += '<tr>';
                    html += '<td>' + row[0] + '</td>'; // Partner Code
                    html += '<td>' + row[1] + '</td>'; // Partner Name
                    html += '<td>' + row[2] + '</td>'; // Email
                    html += '<td>' + row[3] + '</td>'; // Phone
                    html += '<td>' + row[4] + '</td>'; // Giving Type
                    html += '<td>' + row[5] + '</td>'; // Frequency
                    html += '<td class="text-right">' + row[6] + '</td>'; // Expected Amount
                    html += '<td class="text-right">' + row[7] + '</td>'; // Total Contributed
                    html += '<td class="text-right">' + row[8] + '</td>'; // Balance
                    html += '<td>' + row[9] + '</td>'; // Remark
                    html += '</tr>';

                    // Calculate totals (extract numeric values from formatted strings)
                    var expected = parseFloat(row[6].replace(/[^0-9.-]+/g, ""));
                    var contributed = parseFloat(row[7].replace(/[^0-9.-]+/g, ""));
                    var balance = parseFloat(row[8].replace(/[^0-9.-]+/g, "").replace(/<[^>]*>/g, ""));

                    totalExpected += expected || 0;
                    totalContributed += contributed || 0;
                    totalBalance += balance || 0;
                });

                $('#reportTableBody').html(html);

                // Update footer totals
                $('#totalExpected').text(totalExpected.toFixed(2));
                $('#totalContributed').text(totalContributed.toFixed(2));
                $('#totalBalance').text(totalBalance.toFixed(2));
                $('#reportTableFooter').show();

                // Update record count
                $('#recordCount').text(response.data.length);
                $('#recordInfo').show();
            } else {
                $('#reportTableBody').html('<tr><td colspan="10" class="text-center text-muted">No partners with balance found. All partners are up to date!</td></tr>');
                $('#reportTableFooter').hide();
                $('#recordInfo').hide();
            }
        },
        error: function(xhr, status, error) {
            $('#loadingIndicator').hide();
            $('#reportTableContainer').show();
            $('#reportTableBody').html('<tr><td colspan="10" class="text-center text-danger">Error loading data: ' + error + '</td></tr>');
            $('#reportTableFooter').hide();
            $('#recordInfo').hide();
        }
    });
}
</script>
