// Partner Portal JavaScript

$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
    
    // Form validation
    $('form').on('submit', function(e) {
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        
        // Add loading state
        submitBtn.addClass('btn-loading');
        submitBtn.prop('disabled', true);
        
        // Remove loading state after 3 seconds (fallback)
        setTimeout(function() {
            submitBtn.removeClass('btn-loading');
            submitBtn.prop('disabled', false);
        }, 3000);
    });
    
    // Contribution type amount validation
    $('.amount-input').on('input', function() {
        var value = parseFloat($(this).val());
        if (value < 0) {
            $(this).val(0);
        }
    });
    
    // Checkbox and amount synchronization
    $('input[name="giving_types[]"]').on('change', function() {
        var amountInput = $(this).closest('.contribution-type-item').find('.amount-input');
        if ($(this).is(':checked')) {
            amountInput.prop('required', true);
            if (amountInput.val() === '') {
                amountInput.val('0.00');
            }
        } else {
            amountInput.prop('required', false);
            amountInput.val('');
        }
        updateTotal();
    });
    
    // Update total amount
    function updateTotal() {
        var total = 0;
        $('input[name="giving_types[]"]:checked').each(function() {
            var amountInput = $(this).closest('.contribution-type-item').find('.amount-input');
            var amount = parseFloat(amountInput.val()) || 0;
            total += amount;
        });
        
        $('#total-amount-display').text('$' + total.toFixed(2));
        $('#total_amount').val(total);
    }
    
    // Initialize total on page load
    updateTotal();
    
    // Smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if (target.length) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 1000);
        }
    });
    
    // Confirm delete actions
    $('.btn-delete').on('click', function(e) {
        if (!confirm('Are you sure you want to delete this item?')) {
            e.preventDefault();
        }
    });
    
    // Print receipt functionality
    $('.btn-print').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        window.open(url, '_blank', 'width=800,height=600');
    });
    
    // Download receipt functionality
    $('.btn-download').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        window.location.href = url;
    });
    
    // Form field formatting
    $('.phone-input').on('input', function() {
        var value = $(this).val().replace(/\D/g, '');
        $(this).val(value);
    });
    
    $('.currency-input').on('input', function() {
        var value = $(this).val().replace(/[^0-9.]/g, '');
        $(this).val(value);
    });
    
    // Sidebar menu active state
    var currentPath = window.location.pathname;
    $('.sidebar-menu a').each(function() {
        var href = $(this).attr('href');
        if (href && currentPath.indexOf(href) !== -1) {
            $(this).parent().addClass('active');
        }
    });
    
    // Dashboard statistics animation
    $('.small-box .inner h3').each(function() {
        var $this = $(this);
        var countTo = parseInt($this.text().replace(/[^0-9]/g, ''));
        
        if (countTo > 0) {
            $({ countNum: 0 }).animate({
                countNum: countTo
            }, {
                duration: 2000,
                easing: 'swing',
                step: function() {
                    var displayValue = Math.floor(this.countNum);
                    if ($this.text().includes('$')) {
                        $this.text('$' + displayValue.toLocaleString());
                    } else {
                        $this.text(displayValue.toLocaleString());
                    }
                },
                complete: function() {
                    var displayValue = Math.floor(this.countNum);
                    if ($this.text().includes('$')) {
                        $this.text('$' + displayValue.toLocaleString());
                    } else {
                        $this.text(displayValue.toLocaleString());
                    }
                }
            });
        }
    });
    
    // Mobile menu toggle
    $('.sidebar-toggle').on('click', function() {
        $('body').toggleClass('sidebar-collapse');
    });
    
    // Search functionality
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.searchable-item').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
    
    // Data table initialization
    if ($.fn.DataTable) {
        $('.data-table').DataTable({
            "responsive": true,
            "pageLength": 25,
            "order": [[ 0, "desc" ]],
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "Showing 0 to 0 of 0 entries",
                "infoFiltered": "(filtered from _MAX_ total entries)",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                }
            }
        });
    }
});

// Global functions
window.updateTotal = function() {
    var total = 0;
    $('input[name="giving_types[]"]:checked').each(function() {
        var amountInput = $(this).closest('.contribution-type-item').find('.amount-input');
        var amount = parseFloat(amountInput.val()) || 0;
        total += amount;
    });
    
    $('#total-amount-display').text('$' + total.toFixed(2));
    $('#total_amount').val(total);
};

// AJAX form submission helper
window.submitFormAjax = function(form, successCallback, errorCallback) {
    var formData = new FormData(form[0]);
    
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (typeof response === 'string') {
                response = JSON.parse(response);
            }
            
            if (response.status) {
                if (successCallback) {
                    successCallback(response);
                } else {
                    showAlert('success', response.message);
                }
            } else {
                if (errorCallback) {
                    errorCallback(response);
                } else {
                    showAlert('error', response.message);
                }
            }
        },
        error: function(xhr, status, error) {
            if (errorCallback) {
                errorCallback({status: false, message: 'An error occurred. Please try again.'});
            } else {
                showAlert('error', 'An error occurred. Please try again.');
            }
        }
    });
};

// Show alert helper
window.showAlert = function(type, message) {
    var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    var iconClass = type === 'success' ? 'fa-check' : 'fa-ban';
    
    var alertHtml = `
        <div class="alert ${alertClass} alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon ${iconClass}"></i> ${type === 'success' ? 'Success!' : 'Error!'}</h4>
            ${message}
        </div>
    `;
    
    $('.content').prepend(alertHtml);
    
    // Auto-hide after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
};
