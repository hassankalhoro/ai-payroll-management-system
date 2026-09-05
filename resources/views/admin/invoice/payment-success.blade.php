<div class="container text-center mt-5">
    <h2>✅ Payment Successful</h2>
    <p>Your payment for Invoice #{{ $invoiceId }} was successful. Thank you!</p>

    <p id="closing-message" class="text-muted mt-3">
        This tab will close automatically in <span id="countdown">10</span> seconds.
    </p>
</div>

<script>
    let seconds = 10;
    const countdownSpan = document.getElementById('countdown');

    const countdownInterval = setInterval(() => {
        seconds--;
        countdownSpan.textContent = seconds;

        if (seconds <= 0) {
            clearInterval(countdownInterval);

            // Try to close the current tab/window
            window.open('', '_self'); // Some browsers require this to allow closing
            window.close();

            // If it fails to close, alert the user
            setTimeout(() => {
                alert("Please close this tab manually.");
            }, 500);
        }
    }, 1000);
</script>
