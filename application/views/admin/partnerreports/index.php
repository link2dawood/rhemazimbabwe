<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-bar-chart"></i> <?php echo $this->lang->line('partner_reports'); ?></h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('select_report'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <!-- Partner Information Report -->
                            <div class="col-md-6 col-sm-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"><?php echo $this->lang->line('partner_information_report'); ?></span>
                                        <span class="info-box-number">
                                            <small><?php echo $this->lang->line('comprehensive_partner_details'); ?></small>
                                        </span>
                                        <a href="<?php echo base_url() ?>admin/partnerreports/partner_information" class="btn btn-info btn-sm">
                                            <i class="fa fa-eye"></i> <?php echo $this->lang->line('view_report'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Giving Collection By Type Report -->
                            <div class="col-md-6 col-sm-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-green"><i class="fa fa-pie-chart"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"><?php echo $this->lang->line('giving_collection_by_type_report'); ?></span>
                                        <span class="info-box-number">
                                            <small><?php echo $this->lang->line('contributions_grouped_by_type'); ?></small>
                                        </span>
                                        <a href="<?php echo base_url() ?>admin/partnerreports/giving_collection_by_type" class="btn btn-success btn-sm">
                                            <i class="fa fa-eye"></i> <?php echo $this->lang->line('view_report'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Partner Statement Report -->
                            <div class="col-md-6 col-sm-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-yellow"><i class="fa fa-file-text-o"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"><?php echo $this->lang->line('partner_statement_report'); ?></span>
                                        <span class="info-box-number">
                                            <small><?php echo $this->lang->line('detailed_transaction_history'); ?></small>
                                        </span>
                                        <a href="<?php echo base_url() ?>admin/partnerreports/partner_statement" class="btn btn-warning btn-sm">
                                            <i class="fa fa-eye"></i> <?php echo $this->lang->line('view_report'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Balance Giving Report -->
                            <div class="col-md-6 col-sm-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-red"><i class="fa fa-balance-scale"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"><?php echo $this->lang->line('balance_giving_report'); ?></span>
                                        <span class="info-box-number">
                                            <small><?php echo $this->lang->line('outstanding_contributions_with_remarks'); ?></small>
                                        </span>
                                        <a href="<?php echo base_url() ?>admin/partnerreports/balance_giving_report" class="btn btn-danger btn-sm">
                                            <i class="fa fa-eye"></i> <?php echo $this->lang->line('view_report'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>