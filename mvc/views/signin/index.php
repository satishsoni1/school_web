<div class="auth-card" id="login-box">
    <h3 class="auth-title"><?= $this->lang->line('signin') ?></h3>
    <p class="auth-subtitle">Enter your credentials to access your dashboard.</p>

    <form method="post" id="signinForm" novalidate>

        <?php
            if ($form_validation == "No") {
            } else {
                if (customCompute($form_validation)) {
                    echo "<div class=\"auth-alert auth-alert-danger\" role=\"alert\">
                        <i class=\"fa fa-exclamation-circle\"></i>
                        <div class=\"auth-alert-msg\">$form_validation</div>
                        <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"auth-alert-close\" type=\"button\">&times;</button>
                    </div>";
                }
            }
            if ($this->session->flashdata('reset_success')) {
                $message = $this->session->flashdata('reset_success');
                echo "<div class=\"auth-alert auth-alert-success\" role=\"alert\">
                    <i class=\"fa fa-check-circle\"></i>
                    <div class=\"auth-alert-msg\">$message</div>
                    <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"auth-alert-close\" type=\"button\">&times;</button>
                </div>";
            }
        ?>

        <div class="auth-field">
            <label for="signinUsername">Username</label>
            <div class="auth-input-group">
                <i class="fa fa-user auth-input-icon"></i>
                <input class="auth-input" id="signinUsername" placeholder="Enter your username" name="username" type="text" autocomplete="username" autofocus value="<?= set_value('username') ?>">
            </div>
        </div>

        <div class="auth-field">
            <label for="signinPassword">Password</label>
            <div class="auth-input-group">
                <i class="fa fa-lock auth-input-icon"></i>
                <input class="auth-input" id="signinPassword" placeholder="Enter your password" name="password" type="password" autocomplete="current-password">
                <button type="button" class="auth-toggle-password" id="togglePassword" aria-label="Show password" tabindex="-1">
                    <i class="fa fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="auth-row">
            <label class="auth-checkbox">
                <input type="checkbox" value="Remember Me" name="remember">
                <span class="auth-checkbox-box"></span>
                <span class="auth-checkbox-label">Remember me</span>
            </label>
            <a class="auth-link" href="<?= base_url('reset/index') ?>">Forgot password?</a>
        </div>

        <?php if (isset($siteinfos->captcha_status) && $siteinfos->captcha_status == 0) { ?>
            <div class="auth-field">
                <?php echo $recaptcha['widget']; echo $recaptcha['script']; ?>
            </div>
        <?php } ?>

        <button type="submit" class="auth-submit" id="signinSubmit">
            <span class="auth-submit-label">Sign In</span>
            <i class="fa fa-arrow-right"></i>
        </button>

    </form>
</div>

<style>
    .auth-alert {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 13px 14px;
        border-radius: 10px;
        font-size: 13.5px;
        line-height: 1.5;
        margin-bottom: 20px;
    }

    .auth-alert-danger {
        background: var(--auth-danger-bg);
        color: var(--auth-danger);
        border: 1px solid rgba(220, 38, 38, 0.18);
    }

    .auth-alert-success {
        background: var(--auth-success-bg);
        color: var(--auth-success);
        border: 1px solid rgba(5, 150, 105, 0.18);
    }

    .auth-alert i {
        margin-top: 2px;
    }

    .auth-alert-msg {
        flex: 1;
    }

    .auth-alert-msg p {
        margin: 0 0 4px;
    }

    .auth-alert-msg p:last-child {
        margin-bottom: 0;
    }

    .auth-alert-close {
        background: none;
        border: 0;
        font-size: 16px;
        line-height: 1;
        color: inherit;
        opacity: 0.55;
        cursor: pointer;
        padding: 0;
    }

    .auth-alert-close:hover {
        opacity: 1;
    }

    .auth-field {
        margin-bottom: 18px;
    }

    .auth-field label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--auth-text);
        margin-bottom: 7px;
    }

    .auth-input-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .auth-input-icon {
        position: absolute;
        left: 14px;
        color: var(--auth-text-muted);
        font-size: 14px;
        pointer-events: none;
    }

    .auth-input {
        width: 100%;
        height: 46px;
        padding: 0 14px 0 40px;
        border: 1px solid var(--auth-border);
        border-radius: 10px;
        background: #F8FAFC;
        font-size: 14px;
        color: var(--auth-text);
        box-sizing: border-box;
        transition: border-color 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
    }

    .auth-input:focus {
        outline: none;
        border-color: var(--auth-primary);
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.14);
    }

    .auth-toggle-password {
        position: absolute;
        right: 6px;
        background: none;
        border: 0;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        color: var(--auth-text-muted);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .auth-toggle-password:hover {
        background: #EEF2F6;
        color: var(--auth-text);
    }

    .auth-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 22px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .auth-checkbox {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13.5px;
        color: var(--auth-text-muted);
        cursor: pointer;
        user-select: none;
        margin: 0;
    }

    .auth-checkbox input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .auth-checkbox-box {
        width: 18px;
        height: 18px;
        border-radius: 5px;
        border: 1.5px solid var(--auth-border);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: background 0.15s ease, border-color 0.15s ease;
    }

    .auth-checkbox-box::after {
        content: '';
        width: 5px;
        height: 9px;
        border: solid #fff;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg) scale(0);
        transition: transform 0.1s ease;
        margin-top: -2px;
    }

    .auth-checkbox input:checked+.auth-checkbox-box {
        background: var(--auth-primary);
        border-color: var(--auth-primary);
    }

    .auth-checkbox input:checked+.auth-checkbox-box::after {
        transform: rotate(45deg) scale(1);
    }

    .auth-checkbox input:focus-visible+.auth-checkbox-box {
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.25);
    }

    .auth-link {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--auth-primary);
        text-decoration: none;
    }

    .auth-link:hover {
        color: var(--auth-primary-dark);
        text-decoration: underline;
    }

    .auth-submit {
        width: 100%;
        height: 48px;
        border: 0;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--auth-primary) 0%, var(--auth-primary-dark) 100%);
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 0.2px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        box-shadow: 0 8px 18px -6px rgba(13, 148, 136, 0.55);
        transition: transform 0.12s ease, box-shadow 0.12s ease, opacity 0.12s ease;
    }

    .auth-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 22px -6px rgba(13, 148, 136, 0.6);
    }

    .auth-submit:active {
        transform: translateY(0);
    }

    .auth-submit:disabled {
        opacity: 0.75;
        cursor: default;
        transform: none;
    }

    .auth-submit .fa-spinner {
        animation: auth-spin 0.7s linear infinite;
    }

    @keyframes auth-spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }
</style>

<script>
    (function () {
        var toggleBtn = document.getElementById('togglePassword');
        var passwordInput = document.getElementById('signinPassword');

        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function () {
                var isHidden = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isHidden ? 'text' : 'password');
                toggleBtn.innerHTML = isHidden ? '<i class="fa fa-eye-slash"></i>' : '<i class="fa fa-eye"></i>';
                toggleBtn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
            });
        }

        document.querySelectorAll('.auth-alert-close').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var alertEl = btn.closest('.auth-alert');
                if (alertEl) {
                    alertEl.style.display = 'none';
                }
            });
        });

        var form = document.getElementById('signinForm');
        var submitBtn = document.getElementById('signinSubmit');
        if (form && submitBtn) {
            form.addEventListener('submit', function () {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa fa-spinner"></i><span class="auth-submit-label">Signing in&hellip;</span>';
            });
        }
    })();
</script>
