<!-- Giving Settings Content -->
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Giving Settings</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <form id="givingSettingsForm" method="post" action="<?php echo base_url('partnerdashboard/update-giving-settings'); ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Select Type(s) of Contribution <span class="text-danger">*</span></label>
                                <div class="contribution-types">
                                    <?php foreach ($giving_types as $type): ?>
                                    <div class="contribution-type-item">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="giving_types[]" value="<?php echo $type->id; ?>" 
                                                       <?php 
                                                       $is_checked = false;
                                                       if (isset($current_settings) && !empty($current_settings)) {
                                                           foreach ($current_settings as $setting) {
                                                               if ($setting->giving_type_id == $type->id) {
                                                                   $is_checked = true;
                                                                   break;
                                                               }
                                                           }
                                                       }
                                                       echo $is_checked ? 'checked' : '';
                                                       ?>
                                                       onchange="updateTotal()">
                                                <strong><?php echo $type->name; ?></strong>
                                                <br><small class="text-muted"><?php echo $type->description; ?></small>
                                            </label>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="number" class="form-control amount-input" 
                                                   name="amounts[]" placeholder="0.00" 
                                                   min="0" step="0.01" onchange="updateTotal()"
                                                   value="<?php 
                                                   $amount_value = '';
                                                   if (isset($current_settings) && !empty($current_settings)) {
                                                       foreach ($current_settings as $setting) {
                                                           if ($setting->giving_type_id == $type->id) {
                                                               $amount_value = $setting->amount;
                                                               break;
                                                           }
                                                       }
                                                   }
                                                   echo $amount_value;
                                                   ?>">
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="giving_frequency_id">Frequency of Contributions <span class="text-danger">*</span></label>
                                <select class="form-control" id="giving_frequency_id" name="giving_frequency_id" required>
                                    <option value="">Select Frequency</option>
                                    <?php foreach ($giving_frequencies as $frequency): ?>
                                    <option value="<?php echo $frequency->id; ?>"
                                            <?php 
                                            $is_selected = false;
                                            // Check from partner data first, then from current settings
                                            if (isset($current_frequency) && !empty($current_frequency)) {
                                                $is_selected = ($current_frequency == $frequency->id);
                                            } elseif (isset($partner['giving_frequency_id']) && !empty($partner['giving_frequency_id'])) {
                                                $is_selected = ($partner['giving_frequency_id'] == $frequency->id);
                                            }
                                            echo $is_selected ? 'selected' : '';
                                            ?>>
                                        <?php echo $frequency->name; ?>
                                        <?php if ($frequency->days_interval): ?>
                                        (Every <?php echo $frequency->days_interval; ?> days)
                                        <?php endif; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="currency">Currency</label>
                                <select class="form-control" id="currency" name="currency">
                                    <?php 
                                    $current_currency = 'USD'; // Default
                                    if (isset($current_settings) && !empty($current_settings) && isset($current_settings[0]->currency)) {
                                        $current_currency = $current_settings[0]->currency;
                                    } elseif (isset($partner['currency']) && !empty($partner['currency'])) {
                                        $current_currency = $partner['currency'];
                                    }
                                    ?>
                                    <option value="USD" <?php echo ($current_currency == 'USD') ? 'selected' : ''; ?>>USD</option>
                                    <option value="ZWL" <?php echo ($current_currency == 'ZWL') ? 'selected' : ''; ?>>ZWL</option>
                                    <option value="ZAR" <?php echo ($current_currency == 'ZAR') ? 'selected' : ''; ?>>ZAR</option>
                                </select>
                            </div>
                            
                            <div class="total-amount text-center">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <h4>Total Contribution Amount</h4>
                                    </div>
                                    <div class="panel-body">
                                        <h2 id="total-amount-display">$0.00</h2>
                                        <input type="hidden" id="total_amount" name="total_amount" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="notes">Additional Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any additional information about your giving preferences..."><?php echo isset($partner['notes']) ? $partner['notes'] : ''; ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fa fa-save"></i> Save Giving Settings
                                </button>
                                <a href="<?php echo base_url('partnerdashboard'); ?>" class="btn btn-default btn-lg">
                                    <i class="fa fa-arrow-left"></i> Back to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Current Settings Summary -->
<?php if (isset($current_settings) && !empty($current_settings)): ?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Current Giving Settings</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Giving Type</th>
                                <th>Amount</th>
                                <th>Frequency</th>
                                <th>Currency</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($current_settings as $setting): ?>
                            <tr>
                                <td><?php echo $setting->type_name; ?></td>
                                <td>$<?php echo number_format($setting->amount, 2); ?></td>
                                <td><?php echo $setting->frequency_name ?? 'N/A'; ?></td>
                                <td><?php echo $setting->currency; ?></td>
                                <td><?php echo date('M d, Y H:i', strtotime($setting->updated_at)); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<style>
.contribution-type-item {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
    transition: all 0.3s ease;
}

.contribution-type-item:hover {
    border-color: #3c8dbc;
    background-color: #f8f9ff;
}

.total-amount .panel {
    margin-bottom: 0;
}

.total-amount .panel-heading {
    background-color: #5cb85c;
    color: white;
}

.total-amount .panel-body {
    background-color: #dff0d8;
}
</style>

<script>
function updateTotal() {
    let total = 0;
    document.querySelectorAll('input[name="giving_types[]"]:checked').forEach(checkbox => {
        const amountInput = checkbox.closest('.contribution-type-item').querySelector('.amount-input');
        if (amountInput && amountInput.value) {
            total += parseFloat(amountInput.value) || 0;
        }
    });
    
    document.getElementById('total-amount-display').textContent = '$' + total.toFixed(2);
    document.getElementById('total_amount').value = total;
}

// Initialize total on page load
document.addEventListener('DOMContentLoaded', function() {
    updateTotal();
});

// Form submission with AJAX
document.getElementById('givingSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Client-side validation
    const checkedTypes = document.querySelectorAll('input[name="giving_types[]"]:checked');
    const frequencySelect = document.getElementById('giving_frequency_id');
    
    if (checkedTypes.length === 0) {
        alert('Please select at least one giving type');
        return;
    }
    
    if (!frequencySelect.value) {
        alert('Please select a giving frequency');
        return;
    }
    
    // Check if all checked types have amounts
    let hasValidAmounts = true;
    checkedTypes.forEach(checkbox => {
        const amountInput = checkbox.closest('.contribution-type-item').querySelector('.amount-input');
        if (!amountInput.value || parseFloat(amountInput.value) <= 0) {
            hasValidAmounts = false;
        }
    });
    
    if (!hasValidAmounts) {
        alert('Please enter valid amounts for all selected giving types');
        return;
    }
    
    const formData = new FormData(this);
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving...';
    submitBtn.disabled = true;
    
    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            // Show success message
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible';
            alertDiv.innerHTML = `
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Success!</h4>
                ${data.message}
            `;
            document.querySelector('.content').insertBefore(alertDiv, document.querySelector('.content').firstChild);
            
            // Reload page after 2 seconds
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            // Show error message
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible';
            alertDiv.innerHTML = `
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
                ${data.message}
            `;
            document.querySelector('.content').insertBefore(alertDiv, document.querySelector('.content').firstChild);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-dismissible';
        alertDiv.innerHTML = `
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-ban"></i> Error!</h4>
            An error occurred while saving your settings. Please try again.
        `;
        document.querySelector('.content').insertBefore(alertDiv, document.querySelector('.content').firstChild);
    })
    .finally(() => {
        // Restore button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});
</script>
