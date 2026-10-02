{{-- Floating AI Assistant: included in the admin layout for authenticated users --}}
@if(!empty(config('ai.key')))
<button id="ai-fab" class="ai-fab" title="AI Assistant" aria-label="AI Assistant">
    <span class="ai-pulse"></span>
    <i class="ik ik-compass"></i>
</button>

<div id="ai-panel" class="ai-panel" role="dialog" aria-label="AI Assistant">
    <div class="ai-head">
        <div>
            <div class="ai-title">Payroll AI Assistant</div>
            <div class="ai-sub">Answers from your live data</div>
        </div>
        <button id="ai-close" class="ai-close" aria-label="Close">&times;</button>
    </div>

    <div id="ai-body" class="ai-body"></div>

    <div class="ai-suggest">
        <span class="ai-chip" data-q="Summarize payroll costs this quarter.">Payroll summary</span>
        <span class="ai-chip" data-q="Who has the most overtime recently?">Top overtime</span>
        <span class="ai-chip" data-q="How many invoices are overdue?">Overdue invoices</span>
        <span class="ai-chip" data-q="Is the company growing or declining financially?">Company health</span>
    </div>

    <div class="ai-input">
        <textarea id="ai-input" placeholder="Ask about payroll, attendance, invoices..."></textarea>
        <button id="ai-send">Send</button>
    </div>
</div>

<script>
    window.AI_CONFIG = {
        chatUrl: "{{ route('admin.ai.chat') }}",
        csrf: "{{ csrf_token() }}"
    };
</script>
<script src="{{ asset('admin_assets/src/js/ai-assistant.js') }}"></script>
@endif
