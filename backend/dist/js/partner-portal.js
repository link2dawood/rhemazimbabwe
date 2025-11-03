/**
 * Partner Portal - Custom JavaScript
 * Rhema Zimbabwe School Management System
 */

// Ensure jQuery is loaded
if (typeof jQuery === 'undefined') {
    console.error('jQuery is required for Partner Portal functionality');
}

(function($) {
    'use strict';

    // Initialize when DOM is ready
    $(document).ready(function() {
        
        // Initialize all tooltips
        initTooltips();
        
        // Initialize form validation
        initFormValidation();
        
        // Initialize delete confirmations
        initDeleteConfirmations();
        
        // Initialize auto-hide alerts
        initAutoHideAlerts();
        
        console.log('Partner Portal JS Loaded Successfully');
    });

    /**
     * Initialize Bootstrap tooltips
     */
    function initTooltips() {
        if ($.fn.tooltip) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    }

    /**
     * Initialize form validation
     */
    function initFormValidation() {
        // Add client-side validation classes
        $('form').each(function() {
            $(this).on('submit', function() {
                if (!this.checkValidity()) {
                    return false;
                }
            });
        });
    }

    /**
     * Initialize delete confirmations
     */
    function initDeleteConfirmations() {
        $('[data-confirm]').on('click', function(e) {
            if (!confirm($(this).data('confirm'))) {
                e.preventDefault();
                return false;
            }
        });
    }

    /**
     * Initialize auto-hide alerts
     */
    function initAutoHideAlerts() {
        setTimeout(function() {
            $('.alert:not(.alert-permanent)').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    }

    /**
     * Show loading state on button
     */
    window.showButtonLoading = function(button, text) {
        text = text || 'Loading...';
        $(button).data('original-text', $(button).html());
        $(button).html('<i class="fa fa-spinner fa-spin"></i> ' + text);
        $(button).prop('disabled', true);
    };

    /**
     * Restore button state
     */
    window.restoreButton = function(button) {
        var originalText = $(button).data('original-text');
        if (originalText) {
            $(button).html(originalText);
        }
        $(button).prop('disabled', false);
    };

    /**
     * Show success toast/alert
     */
    window.showSuccess = function(message, title) {
        title = title || 'Success!';
        showAlert('success', title, message);
    };

    /**
     * Show error toast/alert
     */
    window.showError = function(message, title) {
        title = title || 'Error!';
        showAlert('danger', title, message);
    };

    /**
     * Show alert
     */
    function showAlert(type, title, message) {
        var alertHtml = '<div class="alert alert-' + type + ' alert-dismissible">' +
            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
            '<h4><i class="icon fa fa-' + (type === 'success' ? 'check' : 'ban') + '"></i> ' + title + '</h4>' +
            message +
            '</div>';
        
        $('.content').prepend(alertHtml);
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    }

})(jQuery);
