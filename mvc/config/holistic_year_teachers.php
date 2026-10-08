<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
| Class teachers to print on a PAST year's holistic report cards, per school year.
| Applied from Admin → Holistic Report → "Apply class teachers" (holisticreport/year_teachers).
| The names and a COPY of each teacher's signature (from the teacher table) are saved into that
| year's report snapshots, so later changes to the teacher master never alter those report cards.
|
| Format: [schoolyearID][classesID] = list of [teacherID, name to print]
| Classes not listed keep whatever their snapshot already has.
*/
$config['holistic_year_teachers'] = array(

    // 2025-26 (schoolyearID 3) — list provided by the school on 2026-10-08.
    3 => array(
        1  => array(array(37, 'Mrs. Lissy J. Menachery'), array(24, 'Mrs. Bhavna Ashish Band')), // NURSERY
        2  => array(array(4,  'Mrs. Sakshi Samir Wadekar')),        // PREP 1 A
        12 => array(array(38, 'Mrs. Iffat S. Fakki')),              // PREP 1 B
        3  => array(array(36, 'Ms. Payal P. Kothari')),             // PREP 2 A
        17 => array(array(39, 'Ms. Alita Braganza')),               // PREP 2 B
        4  => array(array(33, 'Mrs. Monika P. Jadhav')),            // Grade 1 A
        11 => array(array(54, 'Mrs. Ketaki S. Pingale')),           // Grade 1 B
        23 => array(array(12, 'Mrs. Samiya Sayyed')),               // Grade 1 C
        5  => array(array(18, 'Mrs. Kalpana D. Chand')),            // Grade 2 A
        13 => array(array(50, 'Mrs. Arti R. Ratnaparkhi')),         // Grade 2 B
        24 => array(array(70, 'Mrs. Bushra T. Pathan')),            // Grade 2 C
        6  => array(array(56, 'Mrs. Krishna Shilpa Kollapalli')),   // Grade 3 A
        15 => array(array(19, 'Mrs. Geetanjali Karad')),            // Grade 3 B
        7  => array(array(53, 'Mrs. Jaya Z. Kandukuri')),           // Grade 4 A
        16 => array(array(59, 'Mrs. Rashmi P. Mhamunkar')),         // Grade 4 B
        // Grade 5 A (8) and Grade 5 B (20): not provided yet.
    ),
);
