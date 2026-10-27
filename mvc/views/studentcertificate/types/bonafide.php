<?php
// Shared by bonafide_grade and bonafide_preprimary ($variant 'grade' | 'preprimary').
$v = function ($key) use ($d) {
    return isset($d[$key]) ? $d[$key] : '';
};
$isMiss  = $v('salutation') === 'Miss';
$his     = $isMiss ? 'Her' : 'His';
$isGrade = $variant === 'grade';
$dobTime = strtotime($v('dob'));
$dob     = $dobTime ? date($isGrade ? 'd/m/Y' : 'd-m-Y', $dobTime) : $v('dob');
$words   = $v('dob_words') !== '' ? $v('dob_words') : Studentcertificate_m::date_words($v('dob'));
?>
<?php if (!$isGrade) { ?>
    <div class="photo-box">
        <?php if ($photo) { ?><img src="<?=$e($photo)?>" alt=""><?php } else { ?><span>Affix<br>photo</span><?php } ?>
    </div>
<?php } ?>
<div class="body">
    <p class="subject" style="text-align:center; font-style:<?=$isGrade ? 'italic' : 'normal'?>; text-decoration:underline; font-size:20px; margin:<?=$isGrade ? '50px' : '10px'?> 0 26px !important;">BONA FIDE CERTIFICATE</p>

    <p style="text-align:justify; font-size:17px;">
        This is to certify that <b><?=$e($v('salutation'))?><?=$isGrade ? '' : '.'?> <?=$e($v('student_name'))?></b>
        is a bonafide student of this school studying in <b><?=$e($v('grade'))?></b> in the year <b><u><?=$e($v('academic_year'))?></u></b>.
        <?php if ($isGrade) { ?>
            <?=$his?> General Register (G.R.) Number is <b><?=$e($v('gr_no'))?></b> and date of birth as recorded in the General Register of this school is
            <b><?=$e($dob)?> (<?=$e($words)?>)</b>. <?=$his?> caste, as per the General Register, is <b><?=$e($v('caste'))?></b>.
        <?php } else { ?>
            <?=$his?> date of birth as recorded in the General Register of this school is
            <b><?=$e($dob)?> (<?=$e($words)?>)</b>.
        <?php } ?>
    </p>

    <p style="text-align:justify; font-size:17px; margin-top:<?=$isGrade ? '0' : '24px'?>;">
        To the best of my knowledge, <?=$isMiss ? 'she' : 'he'?> bears a good moral character. <?=$his?>
        place of birth is <b><?=$e($v('birth_place'))?>.</b>
    </p>

    <div class="sign" style="margin-top:40px;">
        Thanks and Regards,
        <div class="space" style="height:80px;"></div>
        <b>Principal</b>
        <b>P.P. Gagangiri Maharaj<br>International School<br>Khopoli</b>
    </div>
</div>
