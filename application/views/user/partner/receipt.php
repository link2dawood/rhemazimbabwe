<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $this->lang->line('receipt'); ?> - <?php echo $contribution['receipt_no']; ?></title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            padding: 20px;
        }
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #3c8dbc;
            padding: 30px;
            background: #fff;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #3c8dbc;
        }
        .receipt-header h1 {
            color: #3c8dbc;
            margin: 10px 0;
        }
        .receipt-header p {
            margin: 5px 0;
            color: #666;
        }
        .receipt-info {
            margin-bottom: 30px;
        }
        .receipt-info table {
            width: 100%;
        }
        .receipt-info td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .receipt-info td:first-child {
            font-weight: bold;
            width: 40%;
            color: #333;
        }
        .amount-box {
            background: #f9f9f9;
            border: 2px solid #3c8dbc;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .amount-box h2 {
            margin: 0;
            color: #3c8dbc;
            font-size: 36px;
        }
        .amount-box p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .receipt-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 14px;
        }
        .status-completed {
            background: #00a65a;
            color: #fff;
        }
        .status-pending {
            background: #f39c12;
            color: #fff;
        }
        @media print {
            body {
                padding: 0;
            }
            .receipt-container {
                border: none;
                box-shadow: none;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <?php if (file_exists(FCPATH . 'uploads/school_content/admin_logo.png')): ?>
                <img src="<?php echo base_url('uploads/school_content/admin_logo.png'); ?>" alt="Logo" style="height: 60px; margin-bottom: 10px;">
            <?php endif; ?>
            <h1><?php echo $school_setting->name; ?></h1>
            <p><?php echo $school_setting->address; ?></p>
            <p>Phone: <?php echo $school_setting->phone; ?> | Email: <?php echo $school_setting->email; ?></p>
        </div>

        <!-- Receipt Title -->
        <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="color: #3c8dbc; margin: 0;">CONTRIBUTION RECEIPT</h2>
            <p style="margin: 5px 0; font-size: 18px;"><strong>Receipt #: <?php echo $contribution['receipt_no']; ?></strong></p>
        </div>

        <!-- Partner Information -->
        <div class="receipt-info">
            <h4 style="color: #3c8dbc; margin-bottom: 15px;">Partner Information</h4>
            <table>
                <tr>
                    <td>Partner Code:</td>
                    <td><strong><?php echo $partner['partner_code']; ?></strong></td>
                </tr>
                <tr>
                    <td>Partner Name:</td>
                    <td>
                        <?php
                        if ($partner['account_type'] == 'organization') {
                            echo '<strong>' . $partner['organization_name'] . '</strong><br>';
                            echo '<small>Contact: ' . $partner['firstname'] . ' ' . $partner['lastname'] . '</small>';
                        } else {
                            echo '<strong>' . $partner['firstname'] . ' ' . $partner['lastname'] . '</strong>';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><?php echo $partner['email']; ?></td>
                </tr>
                <tr>
                    <td>Phone:</td>
                    <td><?php echo $partner['mobileno']; ?></td>
                </tr>
            </table>
        </div>

        <!-- Contribution Details -->
        <div class="receipt-info">
            <h4 style="color: #3c8dbc; margin-bottom: 15px;">Contribution Details</h4>
            <table>
                <tr>
                    <td>Date:</td>
                    <td><strong><?php echo date('F d, Y', strtotime($contribution['contribution_date'])); ?></strong></td>
                </tr>
                <tr>
                    <td>Giving Type:</td>
                    <td>
                        <?php
                        if ($contribution['giving_type_id']) {
                            $giving_type = $this->giving_type_model->get($contribution['giving_type_id']);
                            echo $giving_type['name'] ?? '-';
                        } else {
                            echo '-';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Payment Method:</td>
                    <td><?php echo $contribution['payment_method']; ?></td>
                </tr>
                <?php if ($contribution['transaction_id']): ?>
                    <tr>
                        <td>Transaction ID:</td>
                        <td><?php echo $contribution['transaction_id']; ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td>Status:</td>
                    <td>
                        <?php
                        $status_class = ($contribution['status'] == 'completed') ? 'status-completed' : 'status-pending';
                        ?>
                        <span class="status-badge <?php echo $status_class; ?>">
                            <?php echo strtoupper($contribution['status']); ?>
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Amount Box -->
        <div class="amount-box">
            <p style="margin: 0; color: #666;">Amount Received</p>
            <h2><?php echo $contribution['currency']; ?> <?php echo number_format($contribution['amount'], 2); ?></h2>
            <p><strong><?php echo ucwords($this->numberToWords($contribution['amount'])); ?> <?php echo $contribution['currency']; ?> Only</strong></p>
        </div>

        <?php if ($contribution['notes']): ?>
            <div class="receipt-info">
                <h4 style="color: #3c8dbc; margin-bottom: 10px;">Notes</h4>
                <p style="padding: 15px; background: #f9f9f9; border-left: 4px solid #3c8dbc;"><?php echo nl2br($contribution['notes']); ?></p>
            </div>
        <?php endif; ?>

        <!-- Thank You Message -->
        <div style="text-align: center; margin: 30px 0; padding: 20px; background: #f0f8ff; border-radius: 5px;">
            <h3 style="color: #3c8dbc; margin: 0 0 10px 0;">Thank You for Your Partnership!</h3>
            <p style="margin: 0; color: #666;">Your generous contribution is making a difference in students' lives and helping transform education.</p>
        </div>

        <!-- Footer -->
        <div class="receipt-footer">
            <p><strong><?php echo $school_setting->name; ?></strong></p>
            <p>This is a computer-generated receipt and does not require a signature.</p>
            <p>Receipt Generated: <?php echo date('F d, Y h:i A'); ?></p>
            <p style="margin-top: 10px;">For queries, contact us at <?php echo $school_setting->email; ?> or <?php echo $school_setting->phone; ?></p>
        </div>

        <!-- Print Button -->
        <div style="text-align: center; margin-top: 30px;" class="no-print">
            <button onclick="window.print()" class="btn btn-primary btn-lg">
                <i class="fa fa-print"></i> Print Receipt
            </button>
            <button onclick="window.close()" class="btn btn-default btn-lg">
                <i class="fa fa-times"></i> Close
            </button>
        </div>
    </div>

    <script src="<?php echo base_url(); ?>backend/custom/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>backend/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Helper function to convert numbers to words (add to controller or helper)
if (!function_exists('numberToWords')) {
    function numberToWords($number) {
        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        $dictionary = array(
            0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four',
            5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen',
            14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen',
            18 => 'eighteen', 19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty', 70 => 'seventy',
            80 => 'eighty', 90 => 'ninety', 100 => 'hundred', 1000 => 'thousand',
            1000000 => 'million', 1000000000 => 'billion', 1000000000000 => 'trillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if ($number < 0) {
            return $negative . numberToWords(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . numberToWords($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = numberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= numberToWords($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $digit) {
                $words[] = $dictionary[$digit];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }
}
?>
