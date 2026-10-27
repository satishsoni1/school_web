<?php
$v = function ($key) use ($d) {
    return isset($d[$key]) ? $d[$key] : '';
};
$isMiss = $v('salutation') === 'Miss';
$amount = (float) $v('fee_amount');
// Indian grouping: 34825 -> 34,825; 125000 -> 1,25,000.
$digits = (string) (int) $amount;
$grouped = strlen($digits) > 3
    ? preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', substr($digits, 0, -3)) . ',' . substr($digits, -3)
    : $digits;
if ($amount != (int) $amount) {
    $grouped .= '.' . substr(number_format($amount, 2, '.', ''), -2);
}
$words = $v('fee_words') !== '' ? $v('fee_words') : Studentcertificate_m::amount_words($amount);
?>
<div class="body">
    <p class="subject" style="text-align:center; text-decoration:underline; font-size:19px; margin:40px 0 36px !important;">TO WHOMSOEVER IT MAY CONCERN</p>

    <p style="text-align:justify; text-indent:60px; font-size:17px;">
        This is to certify that <b><u><?=$e($v('salutation'))?>. <?=$e($v('student_name'))?></u></b>
        <?=$isMiss ? 'daughter' : 'son'?> of <b><u><?=$e($v('parent_title'))?> <?=$e($v('parent_name'))?></u></b>
        is studying in our school in <b><?=$e($v('grade'))?></b>.
        <?=$isMiss ? 'Her' : 'His'?> school fees for the academic year <?=$e($v('academic_year'))?>
        is &#8377;<?=$e($grouped)?> (&#8377; <?=$e($words)?> only).
    </p>

    <div class="sign" style="margin-top:110px;">
        <div class="space"></div>
        <b>Principal</b>
        <b>P.P. Gagangiri Maharaj<br>International School<br>Khopoli</b>
    </div>
</div>
