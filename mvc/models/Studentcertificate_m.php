<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Generated student certificates / letters (student_certificate, db_migration_student_certificate.sql).
 * One row per certificate: ref_no / ref_year form "PPGMIS/<ref_no>/<ref_year>" and are unique together;
 * the type-specific fields live as JSON in `data`, described by self::$types.
 * To add a type: add an entry to $types and a print view at views/studentcertificate/types/<type>.php.
 */
class Studentcertificate_m extends CI_Model
{
    const TABLE = 'student_certificate';

    /**
     * Type: label, icon + desc (cards on the Certificates page), fields, and optionally view (print file under views/studentcertificate/types/, default the
     * type key) + variant (passed to that view), so types that differ slightly can share one view.
     * Field: label, input (text|textarea|select|date), options (select), required, numeric, placeholder, help,
     * default (used on a new certificate), from (key of the student info that fills it, see student_info()).
     */
    public static $types = array(
        'lc_request' => array(
            'label'  => 'Request for Leaving Certificate',
            'icon'   => 'fa-envelope-o',
            'desc'   => 'Letter to the previous school asking for a newly admitted student\'s LC.',
            'fields' => array(
                'to_school'     => array('label' => 'Previous school', 'required' => true, 'placeholder' => 'e.g. St. Paul School, Nagpur', 'help' => 'Printed under "To, The Principal,"'),
                'to_address'    => array('label' => 'Address (2nd line)', 'placeholder' => 'Optional'),
                'salutation'    => array('label' => 'Master / Miss', 'input' => 'select', 'options' => array('Master' => 'Master', 'Miss' => 'Miss'), 'required' => true, 'from' => 'salutation'),
                'student_name'  => array('label' => 'Student name', 'required' => true, 'from' => 'name'),
                'academic_year' => array('label' => 'Admitted for academic year', 'required' => true, 'placeholder' => 'e.g. 2026-2027', 'from' => 'academic_year'),
                'grade'         => array('label' => 'Admitted in', 'required' => true, 'placeholder' => 'e.g. Grade 4', 'from' => 'grade'),
                'extra_note'    => array('label' => 'Additional request', 'input' => 'textarea', 'default' => 'Also, kindly mention Saral Id, PEN Number, APAAR ID.'),
            ),
        ),
        'fee_certificate' => array(
            'label'  => 'School Fee Certificate',
            'icon'   => 'fa-inr',
            'desc'   => 'Certifies the school fees of a student for an academic year.',
            'fields' => array(
                'salutation'    => array('label' => 'Master / Miss', 'input' => 'select', 'options' => array('Master' => 'Master', 'Miss' => 'Miss'), 'required' => true, 'help' => 'Master prints "son of … His", Miss prints "daughter of … Her".', 'from' => 'salutation'),
                'student_name'  => array('label' => 'Student name', 'required' => true, 'from' => 'name'),
                'parent_title'  => array('label' => 'Parent title', 'input' => 'select', 'options' => array('Mr.' => 'Mr.', 'Mrs.' => 'Mrs.', 'Ms.' => 'Ms.'), 'required' => true),
                'parent_name'   => array('label' => 'Parent name', 'required' => true, 'placeholder' => 'e.g. Saurabh Majethia', 'from' => 'father'),
                'grade'         => array('label' => 'Studying in', 'required' => true, 'placeholder' => 'e.g. Grade 5', 'from' => 'grade'),
                'academic_year' => array('label' => 'Academic year', 'required' => true, 'placeholder' => 'e.g. 2026-2027', 'from' => 'academic_year'),
                'fee_amount'    => array('label' => 'School fees (₹)', 'required' => true, 'numeric' => true, 'placeholder' => 'e.g. 34825'),
                'fee_words'     => array('label' => 'Amount in words', 'placeholder' => 'Leave blank to write it automatically', 'help' => 'Blank = written automatically, e.g. "Thirty-Four Thousand Eight Hundred Twenty Five".'),
            ),
        ),
        // Grade 1+ : with G.R. number and caste, DOB as 08/06/2014.
        'bonafide_grade' => array(
            'label'   => 'Bona Fide Certificate (Grade)',
            'icon'    => 'fa-graduation-cap',
            'desc'    => 'Grade 1 onwards: G.R. number, date of birth, caste and place of birth.',
            'view'    => 'bonafide',
            'variant' => 'grade',
            'fields'  => array(
                'salutation'    => array('label' => 'Master / Miss', 'input' => 'select', 'options' => array('Master' => 'Master', 'Miss' => 'Miss'), 'required' => true, 'help' => 'Sets His / Her and he / she.', 'from' => 'salutation'),
                'student_name'  => array('label' => 'Student name', 'required' => true, 'from' => 'name'),
                'grade'         => array('label' => 'Studying in', 'required' => true, 'placeholder' => 'e.g. Grade 7', 'from' => 'grade'),
                'academic_year' => array('label' => 'Year', 'required' => true, 'placeholder' => 'e.g. 2026-2027', 'from' => 'academic_year'),
                'gr_no'         => array('label' => 'G.R. Number', 'required' => true, 'from' => 'gr_no'),
                'dob'           => array('label' => 'Date of birth', 'input' => 'date', 'required' => true, 'from' => 'dob'),
                'dob_words'     => array('label' => 'Date of birth in words', 'placeholder' => 'Leave blank to write it automatically', 'help' => 'Blank = e.g. "Eighth June Two Thousand Fourteen".'),
                'caste'         => array('label' => 'Caste (as per G.R.)', 'required' => true, 'from' => 'caste'),
                'birth_place'   => array('label' => 'Place of birth', 'required' => true, 'placeholder' => 'e.g. Hyderabad, India', 'from' => 'birth_place'),
            ),
        ),
        // Nursery / Prep: no G.R. number or caste, student photo top right, DOB as 15-08-2021.
        'bonafide_preprimary' => array(
            'label'   => 'Bona Fide Certificate (Pre-Primary)',
            'icon'    => 'fa-child',
            'desc'    => 'Nursery / Prep: student photo, date of birth and place of birth.',
            'view'    => 'bonafide',
            'variant' => 'preprimary',
            'fields'  => array(
                'salutation'    => array('label' => 'Master / Miss', 'input' => 'select', 'options' => array('Master' => 'Master', 'Miss' => 'Miss'), 'required' => true, 'help' => 'Sets His / Her and he / she.', 'from' => 'salutation'),
                'student_name'  => array('label' => 'Student name', 'required' => true, 'from' => 'name'),
                'grade'         => array('label' => 'Studying in', 'required' => true, 'placeholder' => 'e.g. Prep1', 'from' => 'grade'),
                'academic_year' => array('label' => 'Year', 'required' => true, 'placeholder' => 'e.g. 2026-2027', 'from' => 'academic_year'),
                'dob'           => array('label' => 'Date of birth', 'input' => 'date', 'required' => true, 'from' => 'dob'),
                'dob_words'     => array('label' => 'Date of birth in words', 'placeholder' => 'Leave blank to write it automatically', 'help' => 'Blank = e.g. "Fifteenth August Two Thousand Twenty-One".'),
                'birth_place'   => array('label' => 'Place of birth', 'required' => true, 'placeholder' => 'e.g. Jakhania, Ghazipur, Uttar Pradesh, India', 'from' => 'birth_place'),
            ),
        ),
    );

    public function __construct()
    {
        parent::__construct();
        // Same table as db_migration_student_certificate.sql, so the module works before that is run.
        if (!$this->db->table_exists(self::TABLE)) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `" . self::TABLE . "` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `type` VARCHAR(40) NOT NULL,
                `ref_no` INT NOT NULL,
                `ref_year` VARCHAR(20) NOT NULL,
                `cert_date` DATE NOT NULL,
                `studentID` INT NULL,
                `student_name` VARCHAR(150) NOT NULL DEFAULT '',
                `data` TEXT NOT NULL,
                `create_userID` INT NULL,
                `create_username` VARCHAR(60) NULL,
                `create_date` DATETIME NOT NULL,
                `modify_date` DATETIME NOT NULL,
                UNIQUE KEY `uniq_ref` (`ref_year`, `ref_no`),
                KEY `idx_student` (`studentID`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        }
    }

    public function get_all($type = null)
    {
        if ($type) {
            $this->db->where('type', $type);
        }
        return array_map(array($this, 'decode'), $this->db->order_by('cert_date', 'DESC')->order_by('id', 'DESC')->get(self::TABLE)->result());
    }

    public function get($id)
    {
        $row = $this->db->get_where(self::TABLE, array('id' => (int) $id))->row();
        return $row ? $this->decode($row) : null;
    }

    /** Next free reference number of an academic year (shared by every certificate type). */
    public function next_ref_no($refYear)
    {
        $row = $this->db->select_max('ref_no')->where('ref_year', $refYear)->get(self::TABLE)->row();
        return ($row && $row->ref_no) ? (int) $row->ref_no + 1 : 1;
    }

    public function ref_taken($refNo, $refYear, $exceptID = null)
    {
        $this->db->where(array('ref_no' => (int) $refNo, 'ref_year' => $refYear));
        if ($exceptID) {
            $this->db->where('id !=', (int) $exceptID);
        }
        return $this->db->count_all_results(self::TABLE) > 0;
    }

    /** Insert ($id null) or update; returns the id. */
    public function save($row, $id = null)
    {
        $row['data']        = json_encode($row['data'], JSON_UNESCAPED_UNICODE);
        $row['modify_date'] = date('Y-m-d H:i:s');
        if ($id) {
            $this->db->update(self::TABLE, $row, array('id' => (int) $id));
            return (int) $id;
        }
        $row['create_date'] = $row['modify_date'];
        $this->db->insert(self::TABLE, $row);
        return (int) $this->db->insert_id();
    }

    public function delete($id)
    {
        $this->db->delete(self::TABLE, array('id' => (int) $id));
    }

    /** Active students (not in the "REMOVED" class) with their class name, for the picker. */
    public function student_options()
    {
        return $this->db->select('s.studentID, s.classesID, s.name, s.roll, c.classes')
            ->from('student s')
            ->join('classes c', 'c.classesID = s.classesID', 'left')
            ->where('s.active', 1)
            ->where("(c.classes IS NULL OR UPPER(c.classes) != 'REMOVED')", null, false)
            ->order_by('c.classesID', 'ASC')->order_by('s.name', 'ASC')
            ->get()->result();
    }

    /** What the form can pre-fill from a student; keys are referenced by a field's 'from'. */
    public function student_info($studentID, $academicYear)
    {
        $s = $this->db->select('s.*, c.classes, p.father_name AS parent_father_name')
            ->from('student s')
            ->join('classes c', 'c.classesID = s.classesID', 'left')
            ->join('parents p', 'p.parentsID = s.parentID', 'left')
            ->where('s.studentID', (int) $studentID)
            ->get()->row();
        if (!$s) {
            return null;
        }
        // Class names are like "Grade 4 A" -> "Grade 4"; Nursery / Prep classes are used as-is.
        $grade = preg_match('/grade\s*(\d+)/i', (string) $s->classes, $m) ? 'Grade ' . $m[1] : (string) $s->classes;
        // Father: the parent record's name, else the student's father name + surname.
        $father = trim((string) $s->parent_father_name);
        if ($father === '' && !empty($s->father_name)) {
            $father = trim($s->father_name . ' ' . (isset($s->surname) ? $s->surname : ''));
        }
        return array(
            'studentID'     => (int) $s->studentID,
            'name'          => trim(preg_replace('/\s+/', ' ', $s->name)),
            'salutation'    => strtolower((string) $s->sex) === 'female' ? 'Miss' : 'Master',
            'father'        => preg_replace('/\s+/', ' ', $father),
            'grade'         => $grade,
            'academic_year' => $academicYear,
            'dob'           => ($s->dob && strtotime($s->dob)) ? date('Y-m-d', strtotime($s->dob)) : '',
            'gr_no'         => isset($s->registerNO) ? $s->registerNO : '',
            'caste'         => !empty($s->caste) ? $s->caste : (isset($s->religion) ? $s->religion : ''),
            'birth_place'   => implode(', ', array_filter(array_map('trim', array(
                isset($s->birth_place) ? $s->birth_place : '', isset($s->birth_taluka) ? $s->birth_taluka : '',
                isset($s->birth_district) ? $s->birth_district : '', isset($s->birth_state) ? $s->birth_state : '',
                isset($s->birth_country) ? $s->birth_country : '',
            )), 'strlen')),
            'photo'         => (!empty($s->photo) && !in_array($s->photo, array('default.png', 'defualt.png'))) ? $s->photo : '',
            'saral_id'      => isset($s->saral_student_id) ? $s->saral_student_id : '',
            'pen_no'        => isset($s->pen_no) ? $s->pen_no : '',
            'aadhar'        => isset($s->aadhar_or_uid) ? $s->aadhar_or_uid : '',
        );
    }

    /** "2026-27" / "2026 - 2027" -> "2026-2027". */
    public static function full_year($year)
    {
        $year = preg_replace('/\s+/', '', (string) $year);
        if (preg_match('/^(\d{4})-(\d{2})$/', $year, $m)) {
            return $m[1] . '-' . substr($m[1], 0, 2) . $m[2];
        }
        return $year;
    }

    /** 34825 -> "Thirty-Four Thousand Eight Hundred Twenty Five" (Indian system: lakh, crore). */
    public static function amount_words($amount)
    {
        $n = (int) round((float) preg_replace('/[^\d.]/', '', (string) $amount));
        if ($n === 0) {
            return 'Zero';
        }
        $ones = array('', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve',
            'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen');
        $tens = array('', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety');
        $two = function ($x) use ($ones, $tens) {
            return $x < 20 ? $ones[$x] : trim($tens[intdiv($x, 10)] . ' ' . $ones[$x % 10]);
        };
        $parts = array();
        foreach (array(10000000 => 'Crore', 100000 => 'Lakh', 1000 => 'Thousand', 100 => 'Hundred') as $unit => $name) {
            if ($n >= $unit) {
                $q = intdiv($n, $unit);
                $parts[] = ($unit === 10000000 ? self::amount_words($q) : $two($q)) . ' ' . $name;
                $n %= $unit;
            }
        }
        if ($n) {
            $parts[] = $two($n);
        }
        // The school writes the thousands part hyphenated ("Thirty-Four Thousand").
        return preg_replace('/\b(Twenty|Thirty|Forty|Fifty|Sixty|Seventy|Eighty|Ninety) (One|Two|Three|Four|Five|Six|Seven|Eight|Nine) (Thousand|Lakh|Crore)\b/', '$1-$2 $3', implode(' ', $parts));
    }

    /** 2014-06-08 -> "Eighth June Two Thousand Fourteen"; 2021-08-15 -> "Fifteenth August Two Thousand Twenty-One". */
    public static function date_words($date)
    {
        $t = strtotime((string) $date);
        if (!$t) {
            return '';
        }
        $ordinals = array(1 => 'First', 'Second', 'Third', 'Fourth', 'Fifth', 'Sixth', 'Seventh', 'Eighth', 'Ninth', 'Tenth',
            'Eleventh', 'Twelfth', 'Thirteenth', 'Fourteenth', 'Fifteenth', 'Sixteenth', 'Seventeenth', 'Eighteenth', 'Nineteenth', 'Twentieth',
            'Twenty-First', 'Twenty-Second', 'Twenty-Third', 'Twenty-Fourth', 'Twenty-Fifth', 'Twenty-Sixth', 'Twenty-Seventh', 'Twenty-Eighth', 'Twenty-Ninth', 'Thirtieth', 'Thirty-First');
        $year = preg_replace('/\b(Twenty|Thirty|Forty|Fifty|Sixty|Seventy|Eighty|Ninety) (One|Two|Three|Four|Five|Six|Seven|Eight|Nine)\b/', '$1-$2', self::amount_words(date('Y', $t)));
        return $ordinals[(int) date('j', $t)] . ' ' . date('F', $t) . ' ' . $year;
    }

    private function decode($row)
    {
        $row->data = json_decode($row->data, true) ?: array();
        return $row;
    }
}
