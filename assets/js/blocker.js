(function($) {
    "use strict";

    // Debug helper
    function debug(message) {
        if (window.console && console.log) {
            console.log('Booknetic Blocker:', message);
        }
    }

    function initBlocker() {
        debug('Initializing blocker...');
        
        // If button doesn't exist and we're on the calendar page
        if (!$('#booknetic-blocker-btn').length && $('.booknetic-calendar').length) {
            debug('Inserting blocker button...');
            insertBlockerButton();
        }
    }

    function insertBlockerButton() {
        // Try different insertion points
        const $toolbar = $('.booknetic-calendar-toolbar');
        if ($toolbar.length) {
            debug('Found toolbar, inserting button...');
            $toolbar.append(`
                <button type="button" class="btn btn-primary" id="booknetic-blocker-btn">
                    <i class="fa fa-ban"></i> ${BookneticBlockerData.texts.blockTime}
                </button>
            `);
        } else {
            debug('Toolbar not found');
        }
    }

    // Initialize on document ready
    $(document).ready(function() {
        debug('Document ready');
        initBlocker();

        // Handle block button click
        $(document).on('click', '#booknetic-blocker-btn', function(e) {
            e.preventDefault();
            debug('Block button clicked');
            openBlockerModal();
        });
    });

    // Re-initialize on AJAX complete
    $(document).ajaxComplete(function(event, xhr, settings) {
        if (settings.url && (
            settings.url.indexOf('booknetic_calendar_load') !== -1 || 
            settings.url.indexOf('booknetic_get_appointments') !== -1
        )) {
            debug('Ajax complete, reinitializing...');
            initBlocker();
        }
    });

    function openBlockerModal() {
        if (typeof booknetic !== 'undefined' && typeof booknetic.modal === 'function') {
            debug('Opening modal...');
            booknetic.modal({
                title: BookneticBlockerData.texts.blockTime,
                content: $('#booknetic-blocker-modal-content').html()
            });

            // Initialize date and time pickers
            setTimeout(function() {
                if (typeof booknetic.initDatepicker === 'function') {
                    booknetic.initDatepicker('.booknetic-date-input');
                }
                if (typeof booknetic.initTimepicker === 'function') {
                    booknetic.initTimepicker('.booknetic-time-input');
                }
            }, 300);
        } else {
            debug('Booknetic modal function not available');
        }
    }

    // ... rest of the code remains the same ...

})(jQuery);
