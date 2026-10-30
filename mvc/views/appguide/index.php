<?php
$e = function ($v) {
    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
};
$android = 'https://play.google.com/store/apps/details?id=co.in.satishsoni.ppgmis';
$ios     = 'https://apps.apple.com/app/id6503666575';
$phone   = '8459038921';
$email   = 'principalgiscbse@gmail.com';
$pageUrl = base_url('appguide');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PPGMIS Parent App</title>
<meta name="description" content="Download the <?=$e($schoolName)?> app, learn how to sign in and use it, and contact the school for help.">
<meta property="og:title" content="PPGMIS Parent App">
<meta property="og:description" content="Download the school app for attendance, fees, timetable, exams, assignments and notices.">
<?php if ($logo) { ?><meta property="og:image" content="<?=$e($logo)?>"><link rel="icon" href="<?=$e($logo)?>"><?php } ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --brand: #3b2f7a; --brand-soft: #efedf8; --accent: #d4561c; --accent-soft: #fdf0e9;
        --ink: #1e1b2e; --muted: #5f5b70; --line: #e4e2ec; --bg: #f6f5fa; --card: #fff; --ok: #1f7a5a;
    }
    * { box-sizing: border-box; }
    html { -webkit-text-size-adjust: 100%; }
    body { margin: 0; background: var(--bg); color: var(--ink); font: 15px/1.55 Poppins, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif; }
    a { color: var(--brand); }
    .wrap { max-width: 720px; margin: 0 auto; padding: 0 16px; }

    header { background: var(--brand); color: #fff; padding: 22px 0 64px; }
    .brand { display: flex; align-items: center; gap: 12px; }
    .brand img { width: 52px; height: 52px; object-fit: contain; background: #fff; border-radius: 50%; padding: 4px; flex: 0 0 auto; }
    .brand small { display: block; color: #ffd8c4; font-size: 12px; }
    .brand b { display: block; font-size: 15px; line-height: 1.3; }
    header h1 { margin: 22px 0 6px; font-size: 26px; line-height: 1.25; }
    header p { margin: 0; color: #dcd8f0; }

    .card { background: var(--card); border: 1px solid var(--line); border-radius: 14px; padding: 20px; margin: 0 0 16px; }
    .lift { margin-top: -44px; box-shadow: 0 8px 24px rgba(30, 27, 46, .08); }
    h2 { margin: 0 0 4px; font-size: 18px; }
    .sub { margin: 0 0 16px; color: var(--muted); font-size: 14px; }
    .step { display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; margin-right: 8px; border-radius: 50%; background: var(--accent); color: #fff; font-size: 13px; font-weight: 600; vertical-align: 2px; }

    .stores { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .store { display: flex; align-items: center; gap: 10px; padding: 12px 14px; border-radius: 12px; background: #111; color: #fff; text-decoration: none; border: 2px solid transparent; }
    .store svg { width: 26px; height: 26px; flex: 0 0 auto; fill: #fff; }
    .store small { display: block; font-size: 11px; opacity: .8; line-height: 1.2; }
    .store b { display: block; font-size: 16px; line-height: 1.2; }
    .store.mine { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-soft); }
    .yours { display: none; margin-top: 10px; font-size: 13px; color: var(--accent); font-weight: 500; }

    .login { display: grid; gap: 10px; }
    .field { padding: 12px 14px; border-radius: 10px; background: var(--brand-soft); }
    .field small { display: block; color: var(--muted); font-size: 12px; text-transform: uppercase; letter-spacing: .04em; }
    .field b { font-size: 15px; }
    .note { margin: 12px 0 0; padding: 10px 12px; border-left: 3px solid var(--accent); background: var(--accent-soft); border-radius: 0 8px 8px 0; font-size: 13.5px; }

    .features { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
    .feature { padding: 12px 8px; border: 1px solid var(--line); border-radius: 12px; text-align: center; font-size: 13px; font-weight: 500; }
    .feature span { display: block; font-size: 22px; line-height: 1; margin-bottom: 6px; }

    ol.how { margin: 0; padding-left: 20px; }
    ol.how li { margin-bottom: 8px; }

    details { border-top: 1px solid var(--line); padding: 12px 0; }
    details:first-of-type { border-top: 0; padding-top: 0; }
    summary { cursor: pointer; font-weight: 600; list-style: none; display: flex; justify-content: space-between; gap: 10px; }
    summary::-webkit-details-marker { display: none; }
    summary::after { content: "+"; color: var(--accent); font-size: 20px; line-height: 1; }
    details[open] summary::after { content: "–"; }
    details p, details ul { margin: 8px 0 0; color: var(--muted); font-size: 14px; }
    details ul { padding-left: 18px; }

    .contact { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .btn { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 12px; border-radius: 10px; font-weight: 600; text-decoration: none; text-align: center; border: 0; font: inherit; font-weight: 600; cursor: pointer; }
    .btn-call { background: var(--ok); color: #fff; }
    .btn-mail { background: var(--brand); color: #fff; }
    .btn-share { width: 100%; margin-top: 4px; background: var(--accent-soft); color: var(--accent); }
    .address { margin: 14px 0 0; font-size: 13.5px; color: var(--muted); }
    .tell { margin: 12px 0 0; font-size: 13.5px; }
    .tell li { margin-bottom: 2px; }

    footer { padding: 8px 0 28px; text-align: center; color: var(--muted); font-size: 12.5px; }

    @media (max-width: 420px) {
        .stores, .contact { grid-template-columns: 1fr; }
        .features { grid-template-columns: repeat(2, 1fr); }
        header h1 { font-size: 23px; }
    }
</style>
</head>
<body>

<header>
    <div class="wrap">
        <div class="brand">
            <?php if ($logo) { ?><img src="<?=$e($logo)?>" alt=""><?php } ?>
            <div><small>Khalapur Taluka Shikshan Prasarak Mandal's</small><b><?=$e($schoolName)?>, Khopoli</b></div>
        </div>
        <h1>PPGMIS Parent App</h1>
        <p>Your child's attendance, fees, timetable, exams, assignments and school notices, on your phone.</p>
    </div>
</header>

<main class="wrap">

    <section class="card lift" id="download">
        <h2><span class="step">1</span>Download the app</h2>
        <p class="sub">Free on both stores. Search <b>PPGMIS</b> or tap your phone's button.</p>
        <div class="stores">
            <a class="store" id="store-android" href="<?=$e($android)?>" target="_blank" rel="noopener">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3.6 1.8 13.3 12l-9.7 10.2c-.4-.2-.6-.7-.6-1.2V3c0-.5.2-.9.6-1.2Zm10.8 11.3 2.4 2.5-11 6.3 8.6-8.8Zm3.6-3.7 3.1 1.8c.9.5.9 1.8 0 2.3l-3.1 1.8-2.6-2.7 2.6-3.2ZM5.8 2.1l11 6.3-2.4 2.5-8.6-8.8Z"/></svg>
                <span><small>GET IT ON</small><b>Google Play</b></span>
            </a>
            <a class="store" id="store-ios" href="<?=$e($ios)?>" target="_blank" rel="noopener">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16.4 12.6c0-2.6 2.1-3.8 2.2-3.9-1.2-1.8-3.1-2-3.7-2-1.6-.2-3.1.9-3.9.9-.8 0-2.1-.9-3.4-.9-1.7 0-3.3 1-4.2 2.6-1.8 3.1-.5 7.7 1.3 10.2.8 1.2 1.8 2.6 3.1 2.6 1.3-.1 1.7-.8 3.3-.8 1.5 0 2 .8 3.3.8 1.4 0 2.3-1.3 3.1-2.5.9-1.4 1.3-2.8 1.4-2.8-.1 0-2.5-1-2.5-4.2ZM13.9 4.9c.7-.8 1.2-2 1-3.1-1 0-2.2.7-2.9 1.5-.6.7-1.2 1.9-1.1 3 1.1.1 2.3-.6 3-1.4Z"/></svg>
                <span><small>Download on the</small><b>App Store</b></span>
            </a>
        </div>
        <div class="yours" id="yours"></div>
    </section>

    <section class="card" id="login">
        <h2><span class="step">2</span>Sign in</h2>
        <p class="sub">Open the app and enter:</p>
        <div class="login">
            <div class="field"><small>Username</small><b>Your mobile number registered with the school</b><br><span style="color:var(--muted); font-size:13px;">10 digits, without +91 or 0, e.g. 9876543210</span></div>
            <div class="field"><small>Password</small><b>The password given to you by the school</b></div>
        </div>
        <p class="note"><b>Two or more children on the same number?</b> Each child has their own login. The first child uses the mobile number; the next one adds <b>-2</b> (e.g. <b>9876543210-2</b>), then <b>-3</b>. If you're not sure which is which, contact the school.</p>
    </section>

    <section class="card" id="features">
        <h2><span class="step">3</span>What you can see</h2>
        <p class="sub">Everything is on the home screen after you sign in.</p>
        <div class="features">
            <div class="feature"><span>📅</span>Attendance</div>
            <div class="feature"><span>💳</span>Fee details</div>
            <div class="feature"><span>🕘</span>Timetable</div>
            <div class="feature"><span>📝</span>Exam timetable</div>
            <div class="feature"><span>📚</span>Syllabus</div>
            <div class="feature"><span>✏️</span>Assignments</div>
            <div class="feature"><span>📢</span>Notices</div>
            <div class="feature"><span>🗓️</span>Annual planner</div>
            <div class="feature"><span>✉️</span>Leave application</div>
        </div>
    </section>

    <section class="card" id="how">
        <h2>Using the app</h2>
        <ol class="how">
            <li><b>Allow notifications</b> when the app asks, so you get notices and new assignments straight away.</li>
            <li>Tap a tile on the home screen (<b>Attendance</b>, <b>Fee Details</b>, <b>Timetable</b>…) to open it. Pull down on a page to refresh it.</li>
            <li>Latest <b>notices</b> and <b>assignments</b> show on the home screen. Tap one to read it in full.</li>
            <li>To tell the school your child will be absent, send a <b>leave application</b> from the app.</li>
            <li>Keep the app <b>updated</b> from Play Store / App Store to get new features and fixes.</li>
        </ol>
    </section>

    <section class="card" id="help">
        <h2>Having a problem?</h2>
        <p class="sub">Try these first.</p>
        <details>
            <summary>I can't sign in</summary>
            <ul>
                <li>Type the mobile number only: 10 digits, no spaces, no +91 and no 0 at the start.</li>
                <li>If more than one child is on your number, try the number with <b>-2</b> or <b>-3</b> at the end.</li>
                <li>Check the password for extra spaces, and that it is typed exactly as the school gave it.</li>
                <li>Still not working? Your number may be different in school records. Contact the school below.</li>
            </ul>
        </details>
        <details>
            <summary>I'm not getting notifications</summary>
            <ul>
                <li>Open your phone's <b>Settings → Apps → PPGMIS → Notifications</b> and turn them on.</li>
                <li>Sign out and sign in again once after turning them on.</li>
            </ul>
        </details>
        <details>
            <summary>The app is slow, blank or shows an error</summary>
            <ul>
                <li>Check your internet connection, then pull down on the page to refresh.</li>
                <li>Update the app from Play Store / App Store.</li>
                <li>Close the app fully and open it again.</li>
            </ul>
        </details>
        <details>
            <summary>My child's details, fees or attendance look wrong</summary>
            <p>Please contact the school office with your child's name and class. The app shows what is recorded at school, so the office can correct it.</p>
        </details>
    </section>

    <section class="card" id="contact">
        <h2>Contact the school</h2>
        <p class="sub">For login problems or wrong details, call or email the school office.</p>
        <div class="contact">
            <a class="btn btn-call" href="tel:+91<?=$phone?>">📞 Call <?=substr($phone, 0, 5) . ' ' . substr($phone, 5)?></a>
            <a class="btn btn-mail" href="mailto:<?=$e($email)?>?subject=<?=rawurlencode('PPGMIS app help')?>">✉️ Email the school</a>
        </div>
        <p class="tell">When you contact us, please tell us:</p>
        <ul class="tell">
            <li>Child's full name and class</li>
            <li>Your registered mobile number</li>
            <li>What is not working (a screenshot helps)</li>
        </ul>
        <p class="address">
            <b>Address:</b> At - CBSE Wing, K.T.S.P. Campus, Khopoli - 410203, Tal-Khalapur, Dist-Raigad, Maharashtra<br>
            <b>Email:</b> <a href="mailto:<?=$e($email)?>"><?=$e($email)?></a> · <b>Website:</b> <a href="https://www.ppgmis.com" target="_blank" rel="noopener">www.ppgmis.com</a>
        </p>
    </section>

    <button type="button" class="btn btn-share" id="share">🔗 Share this page with another parent</button>
</main>

<footer>© <?=date('Y')?> <?=$e($schoolName)?>, Khopoli</footer>

<script>
(function () {
    // Point the parent at their phone's store.
    var ua = navigator.userAgent || '';
    var isIOS = /iPhone|iPad|iPod/i.test(ua) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
    var isAndroid = /Android/i.test(ua);
    var yours = document.getElementById('yours');
    if (isIOS || isAndroid) {
        document.getElementById(isIOS ? 'store-ios' : 'store-android').classList.add('mine');
        yours.textContent = 'Your phone looks like ' + (isIOS ? 'an iPhone: use App Store.' : 'Android: use Google Play.');
        yours.style.display = 'block';
    }

    var url = <?=json_encode($pageUrl)?>;
    var text = 'Download the PPGMIS school app and see how to sign in:';
    document.getElementById('share').addEventListener('click', function () {
        if (navigator.share) {
            navigator.share({title: 'PPGMIS Parent App', text: text, url: url}).catch(function () {});
        } else {
            window.open('https://wa.me/?text=' + encodeURIComponent(text + ' ' + url), '_blank');
        }
    });
})();
</script>
</body>
</html>
