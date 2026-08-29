// DWPS Progress Card – App JS

document.addEventListener('DOMContentLoaded', function() {

    // Auto-dismiss alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s';
            setTimeout(function() { alert.remove(); }, 500);
        }, 4000);
    });

    // Image preview on file select
    document.querySelectorAll('input[type="file"]').forEach(function(input) {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            const prev = this.previousElementSibling;
            if (prev && prev.tagName === 'IMG') {
                prev.src = URL.createObjectURL(file);
            } else {
                const img = document.createElement('img');
                img.className = 'preview-img';
                img.src = URL.createObjectURL(file);
                this.parentNode.insertBefore(img, this);
            }
        });
    });

    // Confirm delete
    document.querySelectorAll('[data-confirm]').forEach(function(el) {
        el.addEventListener('click', function(e) {
            if (!confirm(this.dataset.confirm || 'Are you sure?')) {
                e.preventDefault();
            }
        });
    });

    // Attendance auto-calculate percentage
    document.querySelectorAll('.att-input').forEach(function(input) {
        if (input.name && input.name.includes('working_days')) {
            // Skip – handled below
        }
    });

    // Attendance percentage auto-calculate
    const months = ['Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar'];
    months.forEach(function(m) {
        const wdInput = document.querySelector(`input[name="attendance[${m}][working_days]"]`);
        const daInput = document.querySelector(`input[name="attendance[${m}][days_attended]"]`);
        const pcInput = document.querySelector(`input[name="attendance[${m}][attendance_percentage]"]`);
        
        function calcPct() {
            if (wdInput && daInput && pcInput) {
                const wd = parseFloat(wdInput.value) || 0;
                const da = parseFloat(daInput.value) || 0;
                if (wd > 0) {
                    pcInput.value = ((da / wd) * 100).toFixed(1);
                }
            }
        }
        if (wdInput) wdInput.addEventListener('input', calcPct);
        if (daInput) daInput.addEventListener('input', calcPct);
    });

    // Section smooth scroll from nav
    document.querySelectorAll('[data-scroll]').forEach(function(el) {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.dataset.scroll);
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    // Form section collapsible toggle
    document.querySelectorAll('.section-header').forEach(function(header) {
        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.innerHTML = '▾';
        toggle.className = 'section-toggle';
        toggle.style.cssText = 'margin-left:auto;background:rgba(255,255,255,0.3);border:none;border-radius:4px;padding:2px 8px;cursor:pointer;font-size:1rem;color:#fff;font-weight:bold;';
        header.appendChild(toggle);
        
        let collapsed = false;
        toggle.addEventListener('click', function() {
            collapsed = !collapsed;
            const section = header.parentElement;
            const content = Array.from(section.children).filter(c => c !== header);
            content.forEach(c => c.style.display = collapsed ? 'none' : '');
            toggle.innerHTML = collapsed ? '▸' : '▾';
        });
    });
});
