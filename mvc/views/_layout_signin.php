<?php echo doctype("html5"); ?>
<html class="white-bg-login" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <title>Sign in</title>
    <link rel="SHORTCUT ICON" href="<?= base_url("uploads/images/$siteinfos->photo") ?>" />
    <!-- bootstrap 3.0.2 -->
    <link href="<?php echo base_url('assets/bootstrap/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
    <!-- font Awesome -->
    <link href="<?php echo base_url('assets/fonts/font-awesome.css'); ?>" rel="stylesheet" type="text/css">
    <!-- Style -->
    <link href="<?php echo base_url($backendThemePath . '/style.css'); ?>" rel="stylesheet" type="text/css">
    <!-- iNilabs css -->
    <link href="<?php echo base_url($backendThemePath . '/inilabs.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets/inilabs/responsive.css'); ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --auth-primary: #0D9488;
            --auth-primary-dark: #0F766E;
            --auth-panel-1: #115E59;
            --auth-panel-2: #0A3D39;
            --auth-bg: #F1F5F9;
            --auth-surface: #FFFFFF;
            --auth-text: #0F172A;
            --auth-text-muted: #64748B;
            --auth-border: #E2E8F0;
            --auth-danger: #DC2626;
            --auth-danger-bg: #FEF2F2;
            --auth-success: #059669;
            --auth-success-bg: #ECFDF5;
        }

        html, body {
            height: 100%;
        }

        body.white-bg-login {
            margin: 0;
            background: var(--auth-bg);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--auth-text);
        }

        .auth-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* -------- Left brand panel (desktop only) -------- */
        .auth-brand-panel {
            position: relative;
            flex: 0 0 42%;
            max-width: 42%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px;
            overflow: hidden;
            background: linear-gradient(160deg, var(--auth-panel-1) 0%, var(--auth-panel-2) 100%);
        }

        .auth-brand-panel::before,
        .auth-brand-panel::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(13, 148, 136, 0.35);
            filter: blur(10px);
        }

        .auth-brand-panel::before {
            width: 340px;
            height: 340px;
            top: -120px;
            left: -100px;
            background: rgba(45, 212, 191, 0.25);
        }

        .auth-brand-panel::after {
            width: 260px;
            height: 260px;
            bottom: -90px;
            right: -70px;
            background: rgba(13, 148, 136, 0.30);
        }

        .auth-brand-content {
            position: relative;
            z-index: 1;
            max-width: 380px;
            text-align: center;
            color: #ffffff;
        }

        .auth-logo-ring {
            width: 108px;
            height: 108px;
            margin: 0 auto 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 0 0 10px rgba(255, 255, 255, 0.05);
        }

        .auth-logo-ring img {
            /* width: 68px;
            height: 68px; */
            border-radius: 50%;
            object-fit: cover;
            background: #fff;
        }

        .auth-brand-content h1 {
            font-size: 26px;
            font-weight: 700;
            line-height: 1.3;
            margin: 0 0 12px;
        }

        .auth-brand-content p {
            font-size: 15px;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.78);
            margin: 0;
        }

        .auth-brand-features {
            list-style: none;
            margin: 32px 0 0;
            padding: 0;
            text-align: left;
            display: inline-block;
        }

        .auth-brand-features li {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .auth-brand-features li i {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
        }

        /* -------- Right form panel -------- */
        .auth-form-panel {
            flex: 1 1 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 20px;
        }

        .auth-form-panel-inner {
            width: 100%;
            max-width: 400px;
        }

        .auth-mobile-brand {
            display: none;
            text-align: center;
            margin-bottom: 24px;
        }

        .auth-mobile-brand img {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 4px 14px rgba(13, 148, 136, 0.25);
        }

        .auth-mobile-brand h2 {
            font-size: 18px;
            font-weight: 700;
            margin: 12px 0 0;
            color: var(--auth-text);
        }

        .auth-card {
            background: var(--auth-surface);
            border-radius: 18px;
            padding: 40px 36px;
            box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.12), 0 2px 8px rgba(15, 23, 42, 0.04);
        }

        .auth-card h3.auth-title {
            font-size: 24px;
            font-weight: 800;
            margin: 0 0 6px;
            color: var(--auth-text);
        }

        .auth-subtitle {
            font-size: 14px;
            color: var(--auth-text-muted);
            margin: 0 0 26px;
        }

        .auth-footer-note {
            text-align: center;
            font-size: 12px;
            color: var(--auth-text-muted);
            margin-top: 22px;
        }

        @media (max-width: 991px) {
            .auth-brand-panel {
                display: none;
            }

            .auth-mobile-brand {
                display: block;
            }

            .auth-card {
                padding: 32px 24px;
            }
        }
    </style>
</head>

<body class="white-bg-login">

    <div class="auth-wrapper">
        <div class="auth-brand-panel">
            <div class="auth-brand-content">
                <div class="auth-logo-ring">
                    <?php if (customCompute($siteinfos->photo)) : ?>
                        <img src="<?= base_url('uploads/images/' . $siteinfos->photo) ?>" alt="<?= htmlspecialchars($siteinfos->sname) ?>">
                    <?php else : ?>
                        <i class="fa fa-graduation-cap" style="font-size:34px;color:#fff;"></i>
                    <?php endif; ?>
                </div>
                <h1><?php echo namesorting($siteinfos->sname, 50); ?></h1>
                <p>Welcome back. Sign in to manage attendance, academics, fees and more &mdash; all in one place.</p>
                <ul class="auth-brand-features">
                    <li><i class="fa fa-check"></i> Real-time student &amp; staff records</li>
                    <li><i class="fa fa-check"></i> Secure, role-based access</li>
                    <li><i class="fa fa-check"></i> Everything synced across web &amp; app</li>
                </ul>
            </div>
        </div>

        <div class="auth-form-panel">
            <div class="auth-form-panel-inner">
                <div class="auth-mobile-brand">
                    <?php if (customCompute($siteinfos->photo)) : ?>
                        <img src="<?= base_url('uploads/images/' . $siteinfos->photo) ?>" alt="<?= htmlspecialchars($siteinfos->sname) ?>">
                    <?php endif; ?>
                    <h2><?php echo namesorting($siteinfos->sname, 50); ?></h2>
                </div>

                <?php $this->load->view($subview); ?>

                <p class="auth-footer-note">&copy; <?php echo date('Y'); ?> <?php echo namesorting($siteinfos->sname, 50); ?>. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo base_url('assets/inilabs/jquery.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/bootstrap.min.js'); ?>"></script>

</body>

</html>
