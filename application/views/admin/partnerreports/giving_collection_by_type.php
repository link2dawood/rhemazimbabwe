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

                        <!-- Loading Indicator -->
                        <div id="loadingIndicator" style="display:none; text-align:center; padding:20px;">
                            <i class="fa fa-spinner fa-spin fa-3x"></i>
                            <p>Loading data...</p>
                        </div>

                        <!-- Summary Cards -->
                        <div class="row" id="summaryCards" style="display:none;">
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

                        <!-- Report Table -->
                        <div class="table-responsive" id="reportTableContainer">
                            <table class="table table-striped table-bordered table-hover" id="collectionTable">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('giving_type'); ?></th>
                                        <th><?php echo $this->lang->line('number_of_partners'); ?></th>
                                        <th><?php echo $this->lang->line('number_of_transactions'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('total_amount'); ?></th>
                                    </tr>
                                </thead>
                                <tbody id="reportTableBody">
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            Click "Search" button to load data
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot id="reportTableFooter" style="display:none;">
                                    <tr>
                                        <th><?php echo $this->lang->line('total'); ?></th>
                                        <th id="footerPartners">0</th>
                                        <th id="footerTransactions">0</th>
                                        <th class="text-right" id="footerAmount">0.00</th>
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
        loadReportData();
    });

    // Load data on page load
    loadReportData();
});

function loadReportData() {
    // Show loading
    $('#loadingIndicator').show();
    $('#reportTableContainer').hide();
    $('#summaryCards').hide();

    // Get filter values
    var filterData = {
        giving_type_id: $('#giving_type_id').val(),
        date_from: $('#date_from').val(),
        date_to: $('#date_to').val()
    };

    // Make AJAX request
    $.ajax({
        url: base_url + "admin/partnerreports/getGivingCollectionByTypeData",
        type: "POST",
        data: filterData,
        dataType: 'json',
        success: function(response) {
            $('#loadingIndicator').hide();
            $('#reportTableContainer').show();
            $('#summaryCards').show();

            if (response.data && response.data.length > 0) {
                var html = '';
                var totalPartners = 0;
                var totalTransactions = 0;
                var totalAmount = 0;

                $.each(response.data, function(index, row) {
                    html += '<tr>';
                    html += '<td>' + row[0] + '</td>'; // Giving Type
                    html += '<td>' + row[1] + '</td>'; // Number of Partners
                    html += '<td>' + row[2] + '</td>'; // Number of Transactions
                    html += '<td class="text-right">' + row[3] + '</td>'; // Total Amount
                    html += '</tr>';

                    totalPartners += parseInt(row[1]) || 0;
                    totalTransactions += parseInt(row[2]) || 0;

                    // Extract numeric value from formatted string
                    var amount = parseFloat(row[3].replace(/[^0-9.-]+/g, ""));
                    totalAmount += amount || 0;
                });

                $('#reportTableBody').html(html);

                // Update footer totals
                $('#footerPartners').text(totalPartners);
                $('#footerTransactions').text(totalTransactions);
                $('#footerAmount').text(totalAmount.toFixed(2));
                $('#reportTableFooter').show();

                // Update summary cards
                $('#totalPartners').text(totalPartners);
                $('#totalTransactions').text(totalTransactions);
                $('#totalCollections').text('<?php echo $currency_symbol ?> ' + totalAmount.toFixed(2));

                var avgPerPartner = totalPartners > 0 ? (totalAmount / totalPartners) : 0;
                $('#avgPerPartner').text('<?php echo $currency_symbol ?> ' + avgPerPartner.toFixed(2));

            } else {
                $('#reportTableBody').html('<tr><td colspan="4" class="text-center text-muted">No data available. Try adjusting your filters.</td></tr>');
                $('#reportTableFooter').hide();

                // Reset summary cards
                $('#totalPartners').text('0');
                $('#totalTransactions').text('0');
                $('#totalCollections').text('<?php echo $currency_symbol ?> 0.00');
                $('#avgPerPartner').text('<?php echo $currency_symbol ?> 0.00');
            }
        },
        error: function(xhr, status, error) {
            $('#loadingIndicator').hide();
            $('#reportTableContainer').show();
            $('#reportTableBody').html('<tr><td colspan="4" class="text-center text-danger">Error loading data: ' + error + '</td></tr>');
            $('#reportTableFooter').hide();
        }
    });
}
</script>
