(function($) {
    "use strict";

    $(document).ready(function() {
        // Initialize on page load
        initBlocker();

        // Re-initialize after AJAX calls
        $(document).ajaxComplete(function(event, xhr, settings) {
            if (settings.url && (
                settings.url.indexOf('booknetic_calendar_load') !== -1 || 
                settings.url.indexOf('booknetic_get_appointments') !== -1
            )) {
                initBlocker();
            }
        });

        // Handle block button click
        $(document).on('click', '#booknetic-blocker-btn', function() {
            openBlockerModal();
        });
    });

    function initBlocker() {
        if ($('.booknetic-calendar').length && !$('#booknetic-blocker-btn').length) {
            insertBlockerButton();
        }
    }

    function insertBlockerButton() {
        const $toolbar = $('.booknetic-calendar-toolbar-container');
        if ($toolbar.length) {
            $toolbar.append(`
                <button type="button" class="btn btn-primary" id="booknetic-blocker-btn">
                    <i class="fa fa-ban"></i> ${BookneticBlockerData.texts.blockTime}
                </button>
            `);
        }
    }

    function openBlockerModal() {
        if (typeof booknetic !== 'undefined' && typeof booknetic.modal === 'function') {
            booknetic.modal({
                title: BookneticBlockerData.texts.blockTime,
                content: $('#booknetic-blocker-modal-content').html(),
                onShow: function() {
                    // Initialize date and time pickers
                    if (typeof booknetic.initDatepicker === 'function') {
                        booknetic.initDatepicker('.booknetic-date-input');
                    }
                    if (typeof booknetic.initTimepicker === 'function') {
                        booknetic.initTimepicker('.booknetic-time-input');
                    }
                }
            });
        }
    }

    // ... rest of the code remains the same ...

})(jQuery);
