<div class="modal fade show-invoice-modal edit-layout-modal pr-0" id="showModel" tabindex="-1" role="dialog" aria-labelledby="showModelLable" aria-hidden="true" data-show="true">
    <div class="modal-dialog" role="document">
{{--        {{ $invoice_url }}--}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showModelLable"><i class="ik ik-at-sign"></i>Create Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

                <div class="card">

                            <div class="widget overflow-visible">
                                <div class="progress progressm progress-sm progress-hi-3 hidden">
                                    <div class="progress-bar-popup bg-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <div class="widget-body">
                    <form enctype="multipart/form-data" action="{{ $form_store_popup }}" method="POST" id="createTenantPopup">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label for="title">Title</label><small class="text-danger">*</small>
                                    <input type="text" name="title" class="form-control" id="title" placeholder="VF Corp" autocomplete="off" value="">
                                    <input type="hidden" name="is_ajax" class="form-control" id="is_ajax" value="{{ !empty($ajax)?$ajax:0 }}">
                                    <small class="text-danger err" id="title-err-popup"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label for="email">Email</label><small class="text-danger">*</small>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="john@example.com" autocomplete="off" value="">
                                    <small class="text-danger err" id="email-err-popup"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea  name="address" class="form-control" id="address"  autocomplete="off" value=""></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label for="tax">Tax (%)</label>
                                    <input type="number" step=".01" name="tax" class="form-control" id="tax" placeholder="0" autocomplete="off" value="">
                                    <small class="text-danger err" id="tax-err-popup"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label for="email">Logo</label><small class="text-danger">*</small>
                                    <input type="file" name="logo" class="form-control">
                                    <small class="text-danger err" id="logo-err-popup"></small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="errorBlock">
                            <ul id="showErrors" class="text-danger"></ul>
                        </div>
                        <button type="button" id="createTenantBtn" class="btn btn-primary"><i class="ik save ik-save"></i>Submit</button>

                    </form>
                </div>
                </div>
                </div>


            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

    $(document).ready(function($) {
        $("#createTenantBtn").click(function(event){
            var formIdPopup = "#createTenantPopup";
            var form_url_popup = $(formIdPopup).attr('action');
            $.ajax({
                url: form_url_popup,
                type: 'POST',
                data : new FormData($(formIdPopup)[0]),
                dataType:'JSON',
                processData: false,
                contentType: false,
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progressm", function(evt) {
                        $(".progressm,.overlaym").toggleClass('hidden');
                        $("#createTenantBtn").prop('disabled',true);
                        $("small.err").text('');
                        if (evt.lengthComputable) {
                            var percentComplete = (evt.loaded / evt.total) * 100;
                            $(".progress-bar-popup").width(percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                success:function(data){
                    $("#errorBlock").addClass('hidden');
                    showToast('Created',data.message,'success');
                    if(data.is_ajax)
                    {
                        $('#showModel').modal('toggle');
                    }
                },
                error: function (error) {
                    showToast('Error',error.responseJSON.message,'error');
                    //$(window).scrollTop(0);
                    $("#showErrors li").replaceWith(' ');
                    $("#errorBlock").removeClass('hidden');
                    $("#showErrors").html('');
                    $.each(error.responseJSON.errors, function (key, val) {
                        $("#"+key+"-err-popup").text(val[0]);
                        $("#showErrors").append("<li>"+ val[0] +"</li>");
                    });

                },
                complete : function(){
                    $(".progressm,.overlaym").toggleClass('hidden');
                    setTimeout(()=>{
                        $("#createTenantBtn").prop('disabled',false);
                    },3000);
                    $(".progress-bar-popup").width(0 + '%');
                }
            });
        });
    });

</script>










