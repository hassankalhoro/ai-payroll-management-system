/* Floating AI Assistant — talks to the Laravel AI endpoints.
   Config is provided by window.AI_CONFIG (routes + csrf) in the blade partial. */
(function () {
    'use strict';

    var cfg = window.AI_CONFIG || {};
    var panel, body, input, sendBtn, history = [];

    function el(id) { return document.getElementById(id); }

    function escapeHtml(s) {
        return String(s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    // Very small Markdown renderer (bold, headings, bullets, code, links).
    function md(text) {
        var html = escapeHtml(text);
        html = html.replace(/```([\s\S]*?)```/g, function (_, c) { return '<pre><code>' + c + '</code></pre>'; });
        html = html.replace(/`([^`]+)`/g, '<code>$1</code>');
        html = html.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
        html = html.replace(/^###\s?(.*)$/gm, '<h6>$1</h6>');
        html = html.replace(/^##\s?(.*)$/gm, '<h6>$1</h6>');
        html = html.replace(/^\s*[-*]\s+(.*)$/gm, '&bull; $1');
        html = html.replace(/\[([^\]]+)\]\((https?:\/\/[^\s)]+)\)/g, '<a href="$2" target="_blank" rel="noopener">$1</a>');
        return html.replace(/\n/g, '<br>');
    }

    function addMsg(role, content) {
        var div = document.createElement('div');
        div.className = 'ai-msg ' + (role === 'user' ? 'user' : 'bot');
        div.innerHTML = role === 'user' ? escapeHtml(content) : md(content);
        body.appendChild(div);
        body.scrollTop = body.scrollHeight;
        return div;
    }

    function typing(on) {
        var t = el('ai-typing');
        if (on) {
            if (!t) {
                t = document.createElement('div');
                t.id = 'ai-typing';
                t.className = 'ai-msg bot';
                t.innerHTML = '<span class="ai-typing"><span></span><span></span><span></span></span>';
                body.appendChild(t);
                body.scrollTop = body.scrollHeight;
            }
        } else if (t) {
            t.remove();
        }
    }

    function send(text) {
        text = (text || input.value || '').trim();
        if (!text) return;
        addMsg('user', text);
        history.push({ role: 'user', content: text });
        input.value = '';
        typing(true);
        sendBtn.disabled = true;

        fetch(cfg.chatUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': cfg.csrf,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ message: text, history: history.slice(-8) })
        })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                typing(false);
                sendBtn.disabled = false;
                var reply = (data && data.ok) ? data.reply : ('⚠️ ' + ((data && data.error) || 'Something went wrong.'));
                addMsg('bot', reply);
                history.push({ role: 'assistant', content: reply });
            })
            .catch(function (err) {
                typing(false);
                sendBtn.disabled = false;
                addMsg('bot', '⚠️ Network error: ' + err.message);
            });
    }

    function toggle(open) {
        var p = panel.classList.contains('open');
        var shouldOpen = (typeof open === 'boolean') ? open : !p;
        panel.classList.toggle('open', shouldOpen);
        if (shouldOpen) setTimeout(function () { input.focus(); }, 60);
    }

    document.addEventListener('DOMContentLoaded', function () {
        panel = el('ai-panel'); body = el('ai-body');
        input = el('ai-input'); sendBtn = el('ai-send');
        if (!panel) return;

        el('ai-fab').addEventListener('click', function () { toggle(); });
        el('ai-close').addEventListener('click', function () { toggle(false); });
        sendBtn.addEventListener('click', function () { send(); });
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); send(); }
        });

        Array.prototype.forEach.call(document.querySelectorAll('.ai-chip'), function (chip) {
            chip.addEventListener('click', function () { send(chip.getAttribute('data-q')); });
        });

        addMsg('bot', "👋 Hi! I'm your **payroll AI assistant**. Ask me about employees, payroll, attendance, invoices, overtime or costs — I answer from your live data.");
    });

    window.AIAssistant = { open: function () { toggle(true); }, send: send };
})();
