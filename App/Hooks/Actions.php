<?php

namespace BookneticAddon\Blocker\Hooks;

use BookneticApp\Providers\Helpers\Helper;
use BookneticAddon\Blocker\Models\BlockedSlot;

class Actions
{
    public static function init()
    {
        add_action('booknetic_appointment_before_save', [self::class, 'checkBlockedSlot'], 10, 2);
        add_action('booknetic_settings_saved', [self::class, 'clearBlockerCache']);
        add_action('booknetic_staff_deleted', [self::class, 'cleanupBlockedSlots']);
    }

    public static function checkBlockedSlot($data, $parameters)
    {
        if (!Helper::getOption('booknetic_blocker_enabled', '0')) {
            return;
        }

        $staff_id = $parameters['staff_id'];
        $date = $parameters['date'];
        $time = $parameters['start_time'];

        $blocked = BlockedSlot::where('staff_id', $staff_id)
            ->where('date', $date)
            ->where('start_time', '<=', $time)
            ->where('end_time', '>', $time)
            ->fetch();

        if ($blocked) {
            throw new \Exception(bkntc__('This time slot is blocked and not available for booking.'));
        }
    }

    public static function clearBlockerCache()
    {
        wp_cache_delete('booknetic_blocked_slots');
    }

    public static function cleanupBlockedSlots($staff_id)
    {
        BlockedSlot::where('staff_id', $staff_id)->delete();
    }
}
