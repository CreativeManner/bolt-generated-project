<?php

namespace BookneticAddon\Blocker\Models;

use BookneticApp\Providers\DB\Model;

class BlockedSlot extends Model
{
    protected static $table = 'booknetic_blocked_slots';

    public static function add($staff_id, $date, $start_time, $end_time, $repeat = 'none')
    {
        return self::insert([
            'staff_id' => $staff_id,
            'date' => $date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'repeat' => $repeat
        ]);
    }

    public static function getByStaffAndDateRange($staff_id, $start_date, $end_date)
    {
        return self::where('staff_id', $staff_id)
            ->whereBetween('date', [$start_date, $end_date])
            ->fetchAll();
    }

    public static function deleteById($id)
    {
        return self::where('id', $id)->delete();
    }
}
