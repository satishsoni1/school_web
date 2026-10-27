<?php
$v = function ($key) use ($d) {
    return isset($d[$key]) ? $d[$key] : '';
};
$his = $v('salutation') === 'Miss' ? 'her' : 'his';
?>
<div class="body">
    <p style="line-height:1.6;">
        To,<br>
        The Principal,<br>
        <span class="fill"><?=$e($v('to_school'))?></span>
        <?php if ($v('to_address') !== '') { ?><br><span class="fill"><?=$e($v('to_address'))?></span><?php } ?>
    </p>

    <p class="subject">Subject: Request for Leaving Certificate.</p>

    <p>Sir/Ma'am,</p>

    <p>
        <?=$e($v('salutation'))?>. <span class="fill"><?=$e($v('student_name'))?></span> has taken admission in our school
        for the academic year <?=$e($v('academic_year'))?> in <span class="fill"><?=$e($v('grade'))?></span>.
        Herewith, we request you to please issue <?=$his?> leaving certificate at your earliest.
        <?php if ($v('extra_note') !== '') { ?><br><?=nl2br($e($v('extra_note')))?><?php } ?>
    </p>

    <div class="sign">
        Thanks &amp; Regards,
        <div class="space"></div>
        <b>Principal</b>
        <b>P.P. Gagangiri Maharaj<br>International School<br>Khopoli</b>
    </div>
</div>
