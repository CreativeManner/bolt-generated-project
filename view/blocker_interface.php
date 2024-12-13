<div id="booknetic-blocker-modal-content" class="hidden">
    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="block-staff-id"><?php echo __('Staff', 'booknetic-blocker'); ?></label>
            <select id="block-staff-id" class="form-control">
                <?php
                $staff = \BookneticApp\Models\Staff::fetchAll();
                foreach ($staff as $s) {
                    echo '<option value="' . esc_attr($s->id) . '">' . esc_html($s->name) . '</option>';
                }
                ?>
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="block-start-date"><?php echo __('Start Date', 'booknetic-blocker'); ?></label>
            <input type="text" id="block-start-date" class="form-control booknetic-date-input">
        </div>
        <div class="form-group col-md-6">
            <label for="block-end-date"><?php echo __('End Date', 'booknetic-blocker'); ?></label>
            <input type="text" id="block-end-date" class="form-control booknetic-date-input">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="block-start-time"><?php echo __('Start Time', 'booknetic-blocker'); ?></label>
            <input type="text" id="block-start-time" class="form-control booknetic-time-input">
        </div>
        <div class="form-group col-md-6">
            <label for="block-end-time"><?php echo __('End Time', 'booknetic-blocker'); ?></label>
            <input type="text" id="block-end-time" class="form-control booknetic-time-input">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="block-repeat"><?php echo __('Repeat', 'booknetic-blocker'); ?></label>
            <select id="block-repeat" class="form-control">
                <option value="none"><?php echo __('No repeat', 'booknetic-blocker'); ?></option>
                <option value="daily"><?php echo __('Daily', 'booknetic-blocker'); ?></option>
                <option value="weekly"><?php echo __('Weekly', 'booknetic-blocker'); ?></option>
                <option value="monthly"><?php echo __('Monthly', 'booknetic-blocker'); ?></option>
            </select>
        </div>
    </div>

    <div class="form-row mt-3">
        <div class="col-md-12 text-right">
            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                <?php echo __('Cancel', 'booknetic-blocker'); ?>
            </button>
            <button type="button" class="btn btn-primary" id="booknetic-add-block-btn">
                <?php echo __('Add Block', 'booknetic-blocker'); ?>
            </button>
        </div>
    </div>
</div>
