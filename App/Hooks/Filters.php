<?php

namespace BookneticAddon\Blocker\Hooks;

use BookneticApp\Providers\Helpers\Helper;
use BookneticAddon\Blocker\Models\BlockedSlot;

class Filters
{
    public static function init()
    {
        add_filter('booknetic_calendar_events', [self::class, 'addBlockedSlotsToCalendar'], 10, 2);
        add_filter('booknetic_available_times', [self::class, 'filterBlockedTimes'], 10, 2);
        add_filter('booknetic_localization', [self::class, 'addBlockerTranslations']);
    }

    public static function addBlockedSlotsToCalendar($events, $parameters)
    {
        if (!Helper::getOption('booknetic_blocker_enabled', '0')) {
            return $events;
        }

        $staff_id = isset($parameters['staff_id']) ? $parameters['staff_id'] : 0;
        $start_date = isset($parameters['start']) ? date('Y-m-d', strtotime($parameters['start'])) : date('Y-m-d');
        $end_date = isset($parameters['end']) ? date('Y-m-d', strtotime($parameters['end'])) : date('Y-m-d');

        $blocked_slots = BlockedSlot::getByStaffAndDateRange($staff_id, $start_date, $end_date);

        foreach ($blocked_slots as $slot) {
            $events[] = [
                'id' => 'block_' . $slot->id,
                'title' => bkntc__('Blocked Time'),
                'start' => $slot->date . ' ' . $slot->start_time,
                'end' => $slot->date . ' ' . $slot->end_time,
                'className' => 'booknetic-blocked-slot',
                'color' => '#ff4d4d',
                'is_blocked' => true
            ];
        }

        return $events;
    }

    public static function filterBlockedTimes($available_times, $parameters)
    {
        if (!Helper::getOption('booknetic_blocker_enabled', '0')) {
            return $available_times;
        }

        $staff_id = $parameters['staff_id'];
        $date = $parameters['date'];

        $blocked_slots = BlockedSlot::where('staff_id', $staff_id)
            ->where('date', $date)
            ->fetchAll();

        foreach ($blocked_slots as $slot) {
            $start = strtotime($slot->start_time);
            $end = strtotime($slot->end_time);

            foreach ($available_times as $key => $time) {
                $time_stamp = strtotime($time['start_time']);
                if ($time_stamp >= $start && $time_stamp < $end) {
                    unset($available_times[$key]);
                }
            }
        }

        return array_values($available_times);
    }

    public static function addBlockerTranslations($translations)
    {
        $translations['BLOCKED_TIME'] = bkntc__('Blocked Time');
        $translations['BLOCK_TIME'] = bkntc__('Block Time');
        $translations['TIME_SLOT_BLOCKED'] = bkntc__('This time slot is blocked');
        
        return $translations;
    }
}
