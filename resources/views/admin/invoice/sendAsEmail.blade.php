{{--{{ $invoice }}--}}
<div class="modal fade show-invoice-modal" id="showModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Send Invoice ({{ $invoice->invoice_id }})</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Left Side - Form -->
                        <form enctype="multipart/form-data" action="{{ $sendEmail }}" method="GET" id="sendEmailForm">
                            @csrf
                            <div class="mb-3">
                                <label for="mail_to" class="form-label">To Cc/Bcc</label>
                                <input type="email" class="form-control" id="mail_to" name="mail_to" autocomplete="off" value="">
                                <small class="text-danger err" id="mail_to-err"></small>
                            </div>
                            <div class="mb-3">
                                <label for="email_subject" class="form-label">Subject</label>
                                <input name="email_subject" class="form-control" id="email_subject" autocomplete="off" value="">
                                <small class="text-danger err" id="email_subject-err"></small>
                            </div>
                            <div class="mb-3">
                                <label for="email_body" class="form-label">Body</label>
                                <textarea class="form-control" id="email_body" name="email_body" rows="3"></textarea>
                                <small class="text-danger err" id="email_body-err"></small>
                            </div>

                            <br>
{{--                            <button type="submit" class="btn btn-primary" id="submitForm">Save changes</button>--}}

{{--                            <button type="submit" class="btn btn-primary"><i class="ik save ik-save"></i>Submit</button>--}}

{{--                            <a href="{{ route('admin.invoice.index') }}" class="btn btn-light"><i class="ik arrow-left ik-arrow-left"></i> Go Back</a>--}}
                        </form>

                    </div>
{{--                    <div class="col-md-6">--}}
{{--                        <!-- Right Side - Preview -->--}}

{{--                        <div class="card">--}}
{{--                            <div class="card-header">--}}
{{--                                <h5 class="card-title">Email Preview</h5>--}}
{{--                            </div>--}}
{{--                            <div class="card-body">--}}
{{--                                <h5 class="card-title">Subject: <span id="subject-preview"></span></h5>--}}
{{--                                <p class="card-text" id="body-preview"></p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
            <div class="modal-footer">
{{--                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>--}}
                <button type="submit" class="btn btn-primary" id="submitForm">Send</button>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function(){

        $('#submitForm').click(function(){

            console.log('here');
            $('#sendEmailForm').submit(); // submit the form
        });

    });
</script>


