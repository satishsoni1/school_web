<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Daily class timetable (classwise_timetable) — what the app shows on its "Timetable" page
 * (api/v10/classwisetimetable). One row per class + day + slot.
 *   slot_type  PERIOD | BREAK (Assembly / Short Break / Long Break are BREAK rows)
 *   start_time / end_time stored as "08:15 AM"; the app orders slots by start time.
 *   period_no / sort_order are numbered automatically per class + day (periods 1..n, breaks 0).
 */
class Classtimetable_m extends CI_Model
{
    const TABLE = 'classwise_timetable';

    public static $days      = array('MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY');
    public static $slotTypes = array('PERIOD' => 'Period', 'BREAK' => 'Break / Assembly');

    /** All slots of a class, grouped by day in week order, each day sorted by start time. */
    public function get_week($classesID)
    {
        $rows = $this->db->where('classesID', (int) $classesID)
            ->order_by("STR_TO_DATE(start_time, '%h:%i %p')", '', false)
            ->order_by('id', 'ASC')
            ->get(self::TABLE)->result();
        $week = array_fill_keys(self::$days, array());
        foreach ($rows as $row) {
            $week[strtoupper($row->day)][] = $row;
        }
        return $week;
    }

    public function get_slot($id)
    {
        return $this->db->get_where(self::TABLE, array('id' => (int) $id))->row();
    }

    /** Slot counts per class (for the class list). */
    public function counts_by_class()
    {
        $rows = $this->db->select('classesID, COUNT(*) n')->group_by('classesID')->get(self::TABLE)->result();
        return array_column($rows, 'n', 'classesID');
    }

    /** Insert ($id null) or update one slot, then renumber that class's day(s). */
    public function save_slot($classesID, $className, $day, $slotType, $start, $end, $subject, $id = null)
    {
        $data = array(
            'classesID'  => (int) $classesID,
            'class_name' => mb_substr($className, 0, 10),
            'day'        => $day,
            'slot_type'  => $slotType,
            'start_time' => $start,
            'end_time'   => $end,
            'subject'    => $subject,
            'period_no'  => null,
            'sort_order' => 0,
        );
        $oldDay = null;
        if ($id) {
            $old = $this->get_slot($id);
            $oldDay = $old ? $old->day : null;
            $this->db->where('id', (int) $id)->update(self::TABLE, $data);
        } else {
            $this->db->insert(self::TABLE, $data);
        }
        $this->renumber($classesID, $day);
        if ($oldDay && $oldDay !== $day) {
            $this->renumber($classesID, $oldDay);
        }
    }

    public function delete_slot($id)
    {
        $slot = $this->get_slot($id);
        if ($slot) {
            $this->db->where('id', (int) $id)->delete(self::TABLE);
            $this->renumber($slot->classesID, $slot->day);
        }
        return $slot;
    }

    /** Replace $toDays of the class with a copy of $fromDay. Returns slots copied per day. */
    public function copy_day($classesID, $fromDay, array $toDays)
    {
        $source = $this->db->get_where(self::TABLE, array('classesID' => (int) $classesID, 'day' => $fromDay))->result_array();
        foreach ($toDays as $day) {
            if ($day === $fromDay) {
                continue;
            }
            $this->db->where(array('classesID' => (int) $classesID, 'day' => $day))->delete(self::TABLE);
            foreach ($source as $row) {
                unset($row['id']);
                $row['day'] = $day;
                $this->db->insert(self::TABLE, $row);
            }
        }
        return count($source);
    }

    /** Replace the whole timetable of $toClassID with a copy of $fromClassID's. Returns slots copied. */
    public function copy_class($fromClassID, $toClassID, $toClassName)
    {
        $source = $this->db->get_where(self::TABLE, array('classesID' => (int) $fromClassID))->result_array();
        $this->db->where('classesID', (int) $toClassID)->delete(self::TABLE);
        foreach ($source as $row) {
            unset($row['id']);
            $row['classesID']  = (int) $toClassID;
            $row['class_name'] = mb_substr($toClassName, 0, 10);
            $this->db->insert(self::TABLE, $row);
        }
        return count($source);
    }

    public function clear_day($classesID, $day)
    {
        $this->db->where(array('classesID' => (int) $classesID, 'day' => $day))->delete(self::TABLE);
        return $this->db->affected_rows();
    }

    /** Number periods 1..n by start time for one class + day; breaks get no number. */
    private function renumber($classesID, $day)
    {
        $rows = $this->db->where(array('classesID' => (int) $classesID, 'day' => $day))
            ->order_by("STR_TO_DATE(start_time, '%h:%i %p')", '', false)
            ->order_by('id', 'ASC')
            ->get(self::TABLE)->result();
        $n = 0;
        foreach ($rows as $row) {
            $isPeriod = ($row->slot_type === 'PERIOD');
            $periodNo = $isPeriod ? ++$n : null;
            $this->db->where('id', $row->id)->update(self::TABLE, array(
                'period_no'  => $periodNo,
                'sort_order' => $isPeriod ? $periodNo : 0,
            ));
        }
    }

    /** "13:05" (time input) -> "01:05 PM" (stored format); "" when invalid. */
    public static function to_ampm($hhmm)
    {
        $ts = strtotime('2000-01-01 ' . $hhmm);
        return $ts ? date('h:i A', $ts) : '';
    }

    /** "01:05 PM" -> "13:05" for <input type="time">. */
    public static function to_24h($ampm)
    {
        $ts = strtotime('2000-01-01 ' . $ampm);
        return $ts ? date('H:i', $ts) : '';
    }
}
