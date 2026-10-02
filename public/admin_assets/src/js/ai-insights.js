/* AI Insights page controller. Uses window.AI_ROUTES injected by the blade view. */
(function () {
    'use strict';
    var R = window.AI_ROUTES || {};

    function $(id) { return document.getElementById(id); }
    function escapeHtml(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }
    function md(text) {
        var html = escapeHtml(text);
        html = html.replace(/```([\s\S]*?)```/g, function (_, c) { return '<pre><code>' + c + '</code></pre>'; });
        html = html.replace(/`([^`]+)`/g, '<code>$1</code>');
        html = html.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
        html = html.replace(/^#{1,4}\s?(.*)$/gm, '<h5>$1</h5>');
        html = html.replace(/^\s*[-*]\s+(.*)$/gm, '<li>$1</li>');
        html = html.replace(/(<li>[\s\S]*?<\/li>)(?!\s*<li>)/g, '<ul>$1</ul>');
        html = html.replace(/\[([^\]]+)\]\((https?:\/\/[^\s)]+)\)/g, '<a href="$2" target="_blank" rel="noopener">$1</a>');
        return html.replace(/\n{2,}/g, '<br><br>').replace(/\n/g, '<br>');
    }
    function money(n) {
        n = Number(n || 0);
        return '$' + n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    function loader(node) {
        node.innerHTML = '<div class="ai-loader"><span class="ai-spinner"></span> Analyzing your data with AI…</div>';
    }
    function metrics(node, items) {
        node.innerHTML = items.map(function (m) {
            return '<div class="ai-metric"><div class="k">' + escapeHtml(m.k) + '</div><div class="v">' + escapeHtml(m.v) + '</div></div>';
        }).join('');
    }
    function post(url, data) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': R.csrf,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data || {})
        }).then(function (r) { return r.json(); });
    }

    // ---- Tabs ----
    function initTabs() {
        var tabs = document.querySelectorAll('.ai-tab');
        Array.prototype.forEach.call(tabs, function (t) {
            t.addEventListener('click', function () {
                Array.prototype.forEach.call(tabs, function (x) { x.classList.remove('active'); });
                t.classList.add('active');
                document.querySelectorAll('.ai-pane').forEach(function (p) { p.style.display = 'none'; });
                var pane = $('pane-' + t.getAttribute('data-tab'));
                if (pane) pane.style.display = 'block';
            });
        });
    }

    // ---- Report ----
    function initReport() {
        var btn = $('btn-report'); if (!btn) return;
        btn.addEventListener('click', function () {
            loader($('report-out')); $('report-metrics').innerHTML = '';
            post(R.insights).then(function (d) {
                if (!d.ok) { $('report-out').innerHTML = '<div class="alert alert-danger">' + escapeHtml(d.error) + '</div>'; return; }
                var s = d.context && d.context.snapshot ? d.context.snapshot : {};
                metrics($('report-metrics'), [
                    { k: 'Employees', v: (s.employees && s.employees.total) || 0 },
                    { k: 'Total Net Pay', v: money(s.payroll && s.payroll.total_net_pay) },
                    { k: 'Collected', v: money(s.invoices && s.invoices.paid_total) },
                    { k: 'Outstanding', v: money(s.invoices && s.invoices.unpaid_total) },
                    { k: 'Overdue Invoices', v: (s.invoices && s.invoices.overdue_count) || 0 },
                    { k: 'Attendance Hours', v: (s.attendance && s.attendance.total_hours) || 0 }
                ]);
                $('report-out').innerHTML = md(d.report);
            }).catch(function (e) { $('report-out').innerHTML = '<div class="alert alert-danger">' + escapeHtml(e.message) + '</div>'; });
        });
        btn.click();
    }

    // ---- Anomaly ----
    function initAnomaly() {
        var btn = $('btn-anomaly'); if (!btn) return;
        btn.addEventListener('click', function () {
            loader($('anomaly-out')); $('anomaly-flags').innerHTML = '';
            post(R.anomalies, { deviation: Number($('anomaly-dev').value || 40) }).then(function (d) {
                if (!d.ok) { $('anomaly-out').innerHTML = '<div class="alert alert-danger">' + escapeHtml(d.error) + '</div>'; return; }
                var flags = d.flags || [];
                $('anomaly-flags').innerHTML = flags.length
                    ? flags.map(function (f) {
                        return '<div class="ai-flag ' + escapeHtml(f.severity) + '"><div class="t">' +
                            escapeHtml((f.type || '').replace(/_/g, ' ')) + ' · ' + escapeHtml(f.severity) +
                            '</div><div>' + escapeHtml(f.detail) + '</div></div>';
                    }).join('')
                    : '<div class="alert alert-success mb-0">No anomalies detected with current thresholds.</div>';
                $('anomaly-out').innerHTML = md(d.analysis || '');
            }).catch(function (e) { $('anomaly-out').innerHTML = '<div class="alert alert-danger">' + escapeHtml(e.message) + '</div>'; });
        });
    }

    // ---- Forecast ----
    function initForecast() {
        var btn = $('btn-forecast'); if (!btn) return;
        btn.addEventListener('click', function () {
            loader($('forecast-out')); $('forecast-metrics').innerHTML = '';
            post(R.predict, { periods: Number($('forecast-periods').value || 3) }).then(function (d) {
                if (!d.ok) { $('forecast-out').innerHTML = '<div class="alert alert-danger">' + escapeHtml(d.error) + '</div>'; return; }
                var f = d.forecast || {};
                var items = [{ k: 'Trend', v: (f.trend || 'n/a') }, { k: 'Slope / month', v: money(f.slope_per_month || 0) }];
                (f.projections || []).forEach(function (p) { items.push({ k: 'Forecast ' + p.period, v: money(p.projected_net_pay) }); });
                metrics($('forecast-metrics'), items);
                $('forecast-out').innerHTML = md(d.analysis || '');
            }).catch(function (e) { $('forecast-out').innerHTML = '<div class="alert alert-danger">' + escapeHtml(e.message) + '</div>'; });
        });
    }

    // ---- Org Health ----
    function initHealth() {
        var btn = $('btn-health'); if (!btn) return;
        btn.addEventListener('click', function () {
            loader($('health-out')); $('health-metrics').innerHTML = '';
            post(R.orgHealth).then(function (d) {
                if (!d.ok) { $('health-out').innerHTML = '<div class="alert alert-danger">' + escapeHtml(d.error) + '</div>'; return; }
                var s = d.signals || {};
                metrics($('health-metrics'), [
                    { k: 'Collection Rate', v: (s.collection_rate_pct || 0) + '%' },
                    { k: 'Total Billed', v: money(s.total_billed) },
                    { k: 'Total Collected', v: money(s.total_collected) },
                    { k: 'Outstanding', v: money(s.total_outstanding) },
                    { k: 'Payroll Cost', v: money(s.total_payroll_cost) },
                    { k: 'Overall Margin', v: money(s.overall_margin) }
                ]);
                $('health-out').innerHTML = md(d.assessment || '');
            }).catch(function (e) { $('health-out').innerHTML = '<div class="alert alert-danger">' + escapeHtml(e.message) + '</div>'; });
        });
    }

    // ---- OCR ----
    function initOcr() {
        var drop = $('ocr-drop'); if (!drop) return;
        var fileInput = $('ocr-file');
        drop.addEventListener('click', function () { fileInput.click(); });
        ['dragenter', 'dragover'].forEach(function (ev) {
            drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.add('drag'); });
        });
        ['dragleave', 'drop'].forEach(function (ev) {
            drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.remove('drag'); });
        });
        drop.addEventListener('drop', function (e) {
            if (e.dataTransfer.files && e.dataTransfer.files[0]) handleFile(e.dataTransfer.files[0]);
        });
        fileInput.addEventListener('change', function () {
            if (fileInput.files && fileInput.files[0]) handleFile(fileInput.files[0]);
        });

        function handleFile(file) {
            if (!/^image\//.test(file.type)) {
                $('ocr-out').innerHTML = '<div class="alert alert-warning">Please upload an image (JPG/PNG/WEBP). PDF support coming soon.</div>';
                return;
            }
            var reader = new FileReader();
            reader.onload = function (e) {
                $('ocr-preview').innerHTML = '<img src="' + e.target.result + '" style="max-height:220px;border-radius:12px;border:1px solid #e2e6f2">';
                var fd = new FormData();
                fd.append('document', file);
                $('ocr-out').innerHTML = '<div class="ai-loader"><span class="ai-spinner"></span> Extracting fields…</div>';
                fetch(R.ocr, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': R.csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: fd
                }).then(function (r) { return r.json(); }).then(function (d) {
                    if (!d.ok) { $('ocr-out').innerHTML = '<div class="alert alert-danger">' + escapeHtml(d.error) + '</div>'; return; }
                    var rows = Object.keys(d.extracted || {}).map(function (k) {
                        var v = d.extracted[k];
                        if (typeof v === 'object') v = JSON.stringify(v);
                        return '<tr><td style="font-weight:700;color:#4a5072">' + escapeHtml(k) + '</td><td>' + escapeHtml(v) + '</td></tr>';
                    }).join('');
                    $('ocr-out').innerHTML = '<table class="table table-bordered">' + rows + '</table>';
                }).catch(function (e) { $('ocr-out').innerHTML = '<div class="alert alert-danger">' + escapeHtml(e.message) + '</div>'; });
            };
            reader.readAsDataURL(file);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        initTabs(); initReport(); initAnomaly(); initForecast(); initHealth(); initOcr();
    });
})();
