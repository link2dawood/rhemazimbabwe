<div class="container-fluid ptb-60">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="status-check-container">
                    <div class="text-center mb-40">
                        <i class="fa fa-search fa-4x text-primary"></i>
                        <h1 class="page-title">Check Registration Status</h1>
                        <p class="lead">Enter your partner code or email to check your registration status</p>
                    </div>

                    <div class="search-form">
                        <form id="statusCheckForm">
                            <div class="form-group">
                                <label>Partner Code</label>
                                <input type="text" class="form-control input-lg" id="partner_code" name="partner_code" placeholder="P-YYYYMMDD-XXXXX">
                                <small class="text-muted">Example: P-20250130-00001</small>
                            </div>

                            <div class="text-center my-20">
                                <strong>OR</strong>
                            </div>

                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" class="form-control input-lg" id="email" name="email" placeholder="your@email.com">
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                <i class="fa fa-search"></i> Check Status
                            </button>
                        </form>
                    </div>

                    <!-- Results Container -->
                    <div id="statusResult" class="status-result" style="display: none;">
                        <hr>
                        <h3>Registration Details</h3>

                        <div class="alert" id="statusAlert">
                            <h4 id="statusTitle"></h4>
                        </div>

                        <table class="table table-bordered" id="statusTable">
                            <tr>
                                <th width="40%">Partner Code:</th>
                                <td id="resultCode"></td>
                            </tr>
                            <tr>
                                <th>Name:</th>
                                <td id="resultName"></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td id="resultEmail"></td>
                            </tr>
                            <tr>
                                <th>Registration Date:</th>
                                <td id="resultDate"></td>
                            </tr>
                            <tr>
                                <th>Current Status:</th>
                                <td id="resultStatus"></td>
                            </tr>
                        </table>

                        <div class="text-center mt-30">
                            <button class="btn btn-default" onclick="resetSearch()">
                                <i class="fa fa-arrow-left"></i> New Search
                            </button>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <div id="errorMessage" class="alert alert-danger" style="display: none; margin-top: 20px;">
                        <i class="fa fa-exclamation-circle"></i>
                        <span id="errorText"></span>
                    </div>
                </div>

                <div class="help-section mt-40">
                    <h4><i class="fa fa-question-circle"></i> Need Help?</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="help-box">
                                <h5>Can't find your partner code?</h5>
                                <p>Check the confirmation email we sent you after registration, or search using your email address.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="help-box">
                                <h5>Registration status showing pending?</h5>
                                <p>It typically takes 1-2 business days to review applications. You'll receive an email once approved.</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-20">
                        <p>Still need help? Contact us at: <a href="mailto:partners@rhemazimbabwe.com">partners@rhemazimbabwe.com</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.ptb-60 { padding: 60px 0; }
.mb-40 { margin-bottom: 40px; }
.mt-20 { margin-top: 20px; }
.mt-30 { margin-top: 30px; }
.mt-40 { margin-top: 40px; }
.my-20 { margin: 20px 0; }

.status-check-container {
    background: #fff;
    padding: 50px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.page-title {
    font-size: 36px;
    font-weight: bold;
    color: #333;
    margin: 20px 0 15px;
}

.search-form {
    max-width: 500px;
    margin: 0 auto;
}

.status-result {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    0% { opacity: 0; transform: translateY(20px); }
    100% { opacity: 1; transform: translateY(0); }
}

.help-section {
    background: #f9f9f9;
    padding: 30px;
    border-radius: 10px;
}

.help-section h4 {
    margin-top: 0;
    margin-bottom: 25px;
    color: #333;
}

.help-box {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.help-box h5 {
    color: #3c8dbc;
    margin-top: 0;
    margin-bottom: 10px;
}

.help-box p {
    margin: 0;
    color: #666;
}
</style>

<script>
var base_url = '<?php echo base_url(); ?>';

$(document).ready(function() {
    $('#statusCheckForm').submit(function(e) {
        e.preventDefault();

        var partner_code = $('#partner_code').val();
        var email = $('#email').val();

        if (!partner_code && !email) {
            showError('Please enter either partner code or email address');
            return;
        }

        // Hide previous results/errors
        $('#statusResult').hide();
        $('#errorMessage').hide();

        // Show loading state
        var btn = $(this).find('button[type=submit]');
        var btnText = btn.html();
        btn.html('<i class="fa fa-spinner fa-spin"></i> Searching...').prop('disabled', true);

        $.ajax({
            url: base_url + 'partnerregistration/getStatus',
            type: 'POST',
            data: {
                partner_code: partner_code,
                email: email
            },
            dataType: 'json',
            success: function(response) {
                btn.html(btnText).prop('disabled', false);

                if (response.status == 'success') {
                    displayResult(response.partner);
                } else {
                    showError(response.message);
                }
            },
            error: function() {
                btn.html(btnText).prop('disabled', false);
                showError('An error occurred. Please try again.');
            }
        });
    });
});

function displayResult(partner) {
    $('#resultCode').text(partner.code);
    $('#resultName').text(partner.name);
    $('#resultEmail').text(partner.email);
    $('#resultDate').text(new Date(partner.created_at).toLocaleDateString());

    var statusHtml = '<span class="label label-' + partner.status_class + '">' + partner.status_text + '</span>';
    $('#resultStatus').html(statusHtml);

    $('#statusAlert').removeClass().addClass('alert alert-' + partner.status_class);
    $('#statusTitle').html('<i class="fa fa-info-circle"></i> Status: ' + partner.status_text);

    $('#statusResult').fadeIn();
}

function showError(message) {
    $('#errorText').text(message);
    $('#errorMessage').fadeIn();
}

function resetSearch() {
    $('#statusCheckForm')[0].reset();
    $('#statusResult').hide();
    $('#errorMessage').hide();
}
</script>