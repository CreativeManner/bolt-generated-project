<?php
// booknetic-blocker/view/settings.php

defined('ABSPATH') or die();

use BookneticApp\Providers\Helpers\Helper;
?>

<div class="form-row">
    <div class="form-group col-md-12">
        <label for="booknetic_blocker_enabled"><?php echo bkntc__('Enable Time Blocker', [], false, 'blocker') ?></label>
        <div class="form-control-checkbox">
            <label class="switch">
                <input type="checkbox" id="booknetic_blocker_enabled" <?php echo Helper::getOption('booknetic_blocker_enabled', '0') == '1' ? 'checked' : ''; ?>>
                <span class="slider round"></span>
            </label>
        </div>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-12">
        <button type="button" class="btn btn-primary" id="booknetic-save-blocker-settings"><?php echo bkntc__('Save Settings', [], false, 'blocker') ?></button>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#booknetic-save-blocker-settings').on('click', function() {
        var enabled = $('#booknetic_blocker_enabled').is(':checked') ? 1 : 0;

        booknetic.ajax('save_blocker_settings', {
            enabled: enabled
        }, function(result) {
            if (result.status === 'ok') {
                booknetic.toast(result.message, 'success');
            } else {
                booknetic.toast(result.message, 'error');
            }
        });
    });
});
</script>
