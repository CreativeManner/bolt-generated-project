<?php
defined('ABSPATH') or die();

use BookneticApp\Providers\Helpers\Helper;
?>

<div id="booknetic_settings_area">
    <link rel="stylesheet" href="<?php echo plugins_url('assets/css/blocker.css', dirname(__FILE__, 2)); ?>">
    <script type="text/javascript" src="<?php echo plugins_url('assets/js/blocker.js', dirname(__FILE__, 2)); ?>"></script>

    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="booknetic_blocker_enabled"><?php echo bkntc__('Enable Blocker'); ?></label>
            <div class="form-control-checkbox">
                <label class="switch">
                    <input type="checkbox" id="booknetic_blocker_enabled" <?php echo Helper::getOption('blocker_enabled', '0') == '1' ? 'checked' : ''; ?>>
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="booknetic_blocker_threshold"><?php echo bkntc__('Blocking Threshold'); ?></label>
            <input type="number" class="form-control" id="booknetic_blocker_threshold" value="<?php echo Helper::getOption('blocker_threshold', '3'); ?>">
        </div>
        <div class="form-group col-md-6">
            <label for="booknetic_blocker_duration"><?php echo bkntc__('Blocking Duration (days)'); ?></label>
            <input type="number" class="form-control" id="booknetic_blocker_duration" value="<?php echo Helper::getOption('blocker_duration', '30'); ?>">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="booknetic_blocker_message"><?php echo bkntc__('Blocking Message'); ?></label>
            <textarea class="form-control" id="booknetic_blocker_message" rows="3"><?php echo Helper::getOption('blocker_message', 'You have been blocked from making appointments due to multiple no-shows.'); ?></textarea>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-12">
            <button type="button" class="btn btn-primary" id="booknetic_save_blocker_settings"><?php echo bkntc__('Save Settings'); ?></button>
        </div>
    </div>
</div>
