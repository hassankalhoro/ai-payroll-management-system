<div class="modal fade show-invoice-modal edit-layout-modal pr-0" id="showModel" tabindex="-1" role="dialog" aria-labelledby="showModelLable" aria-hidden="true" data-show="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="showModelLable">
                    <i class="ik ik-x-circle"></i> Payment Cancelled
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="card p-3 text-center">
                    <h4>Your payment was not completed.</h4>
                    <p>Please try again or contact support.</p>

                    <p class="text-muted mt-3">
                        This window will close automatically in <span id="cancelCountdown">10</span> seconds.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    let seconds = 10;
    const countdownSpan = document.getElementById('cancelCountdown'); // Corrected here

    const countdownInterval = setInterval(() => {
        seconds--;
        countdownSpan.textContent = seconds;

        if (seconds <= 0) {
            clearInterval(countdownInterval);

            // Attempt to close the current tab/window
            window.open('', '_self'); // This sometimes helps browsers allow closing
            window.close();

            // If it doesn't close, show alert after 500ms
            setTimeout(() => {
                alert("Please close this tab manually.");
            }, 500);
        }
    }, 1000);
</script>
