<?php

namespace BookneticAddon\Blocker\Backend;

use BookneticApp\Providers\Helpers\Helper;
use BookneticApp\Providers\Core\Permission;
use BookneticAddon\Blocker\Models\BlockedSlot;

class Controller
{
    public function saveSettings()
    {
        Permission::checkAdminSettings();

        $enabled = Helper::_post('enabled', '0', 'int');
        
        Helper::setOption('booknetic_blocker_enabled', $enabled);

        return Helper::response(true, ['message' => bkntc__('Settings saved successfully!')]);
    }

    public function addBlock()
    {
        Permission::checkAdminSettings();

        $staff_id = Helper::_post('staff_id', '0', 'int');
        $start_date = Helper::_post('start_date', '', 'string');
        $end_date = Helper::_post('end_date', '', 'string');
        $start_time = Helper::_post('start_time', '', 'string');
        $end_time = Helper::_post('end_time', '', 'string');
        $repeat = Helper::_post('repeat', 'none', 'string');

        if (!$staff_id || !$start_date || !$end_date || !$start_time || !$end_time) {
            return Helper::response(false, ['message' => bkntc__('Please fill all required fields!')]);
        }

        try {
            BlockedSlot::add($staff_id, $start_date, $start_time, $end_time, $repeat);
            return Helper::response(true, ['message' => bkntc__('Time block added successfully!')]);
        } catch (\Exception $e) {
            return Helper::response(false, ['message' => $e->getMessage()]);
        }
    }

    public function getBlocks()
    {
        Permission::checkAdminSettings();

        $staff_id = Helper::_post('staff_id', '0', 'int');
        $start_date = Helper::_post('start_date', '', 'string');
        $end_date = Helper::_post('end_date', '', 'string');

        if (!$staff_id || !$start_date || !$end_date) {
            return Helper::response(false, ['message' => bkntc__('Invalid parameters!')]);
        }

        $blocks = BlockedSlot::getByStaffAndDateRange($staff_id, $start_date, $end_date);
        
        return Helper::response(true, ['blocks' => $blocks]);
    }

    public function deleteBlock()
    {
        Permission::checkAdminSettings();

        $block_id = Helper::_post('id', '0', 'int');

        if (!$block_id) {
            return Helper::response(false, ['message' => bkntc__('Invalid block ID!')]);
        }

        try {
            BlockedSlot::deleteById($block_id);
            return Helper::response(true, ['message' => bkntc__('Block deleted successfully!')]);
        } catch (\Exception $e) {
            return Helper::response(false, ['message' => $e->getMessage()]);
        }
    }
}
