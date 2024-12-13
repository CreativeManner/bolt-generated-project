<?php

namespace BookneticAddon\Blocker\Providers;

use BookneticApp\Providers\Helpers\Helper;
use BookneticAddon\Blocker\Models\BlockedSlot;

class Core
{
    public static function init()
    {
        add_action('booknetic_calendar_events_filter', [self::class, 'addBlockedEvents'], 10, 2);
        add_action('booknetic_appointment_add_before', [self::class, 'preventBookingOnBlockedSlots'], 10, 2);
        add_filter('booknetic_calendar_load_data', [self::class, 'addBlockerData']);
    }

    public static function addBlockedEvents($events, $parameters)
    {
        $staff_id = $parameters['staff_id'];
        $start_date = $parameters['start_date'];
        $end_date = $parameters['end_date'];

        $blocked_slots = BlockedSlot::getByStaffAndDateRange($staff_id, $start_date, $end_date);

        foreach ($blocked_slots as $slot) {
            $events[] = [
                'id' => 'block_' . $slot->id,
                'title' => bkntc__('Blocked', [], false, 'blocker'),
                'start' => $slot->date . ' ' . $slot->start_time,
                'end' => $slot->date . ' ' . $slot->end_time,
                'color' => '#FF0000',
                'isBlocked' => true
            ];
        }

        return $events;
    }

    public static function preventBookingOnBlockedSlots($data, $parameters)
    {
        $staff_id = $parameters['staff_id'];
        $date = $parameters['date'];
        $time = $parameters['time'];

        $is_blocked = BlockedSlot::where('staff_id', $staff_id)
            ->where('date', $date)
            ->where('start_time', '<=', $time)
            ->where('end_time', '>', $time)
            ->count();

        if ($is_blocked) {
            throw new \Exception(bkntc__('This time slot is blocked and not available for booking.', [], false, 'blocker'));
        }
    }

    public static function addBlockerData($data)
    {
        $data['booknetic_blocker_enabled'] = Helper::getOption('booknetic_blocker_enabled', '0') == '1';
        return $data;
    }
}
