<div class="modal fade show-invoice-modal edit-layout-modal pr-0" id="showModelService" tabindex="-1" role="dialog" aria-labelledby="showModelServiceLable" aria-hidden="true" data-show="true">
    <div class="modal-dialog" role="document">
{{--        {{ $invoice_url }}--}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showModelServiceLable"><i class="ik ik-at-sign"></i>Create Service</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

                <div class="card">

                            <div class="widget overflow-visible">
                                <div class="progress progressm progress-sm progress-hi-3 hidden">
                                    <div class="progress-bar-popup bg-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                                <div class="widget-body">
                     <form enctype="multipart/form-data" action="{{ $form_store_popup }}" method="POST" id="createServicePopup">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 col-lg-12 col-sm-12">
                                                <div class="form-group">
                                                    <label for="title">Title</label><small class="text-danger">*</small>
                                                    <input type="text" name="title" class="form-control" id="title" placeholder="IT Development" autocomplete="off" value="">
                                                    <input type="hidden" name="is_ajax" class="form-control" id="is_ajax" value="{{ !empty($ajax)?$ajax:0 }}">
                                                    <small class="text-danger err" id="title-err-popup"></small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-md-6 col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="item_type">Item Type</label>
                                                    <select class="form-control" name="item_type" id="item_type">
                                                        <option value="0">Select Type</option>
                                                        @if(!empty($itemTypes))
                                                            @foreach($itemTypes as $item)
                                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <small class="text-danger err" id="item_type-err-popup"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="sku">SKU</label><small class="text-danger">*</small>
                                                    <input type="text" name="sku" class="form-control" id="sku" placeholder="001SKU" autocomplete="off" value="">
                                                    <small class="text-danger err" id="sku-err-popup"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="category_id">Category</label>
                                            <select class="form-control" name="category_id" id="category_id">
                                                <option value="0">Select Category</option>
                                                @if(!empty($categories))
                                                    @foreach($categories as $cat)
                                                        <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <small class="text-danger err" id="category_id-err-popup"></small>
                                        </div>
                                        <div class="form-group">
                                            <label for="income_account_id">Income Account</label>
                                            <select class="form-control" name="income_account_id" id="income_account_id">
                                                <option value="0">Select Account</option>
                                                @if(!empty($accounts))
                                                    @foreach($accounts as $acc)
                                                        <option value="{{ $acc->id }}">{{ $acc->title }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <small class="text-danger err" id="income_account_id-err-popup"></small>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-lg-12 col-sm-12">
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea  name="description" class="form-control" id="description"  autocomplete="off" value=""></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-lg-12 col-sm-12">
                                                <div class="form-group">
                                                    <label for="price_rate">Price/Rate</label>
                                                    <input  type="number" step=".01" name="price_rate" class="form-control" id="price_rate" placeholder="0" autocomplete="off" >
                                                    <small class="text-danger err" id="price_rate-err-popup"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-lg-12 col-sm-12">
                                                <div class="form-group">
                                                    <label for="is_sell">I sell this service to the customer
                                                        <input type="checkbox" class="form-control" id="is_sell"  name="is_sell">
                                                    </label>
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
                                                    <label for="email">Picture</label>
                                                    <input type="file" name="logo" class="form-control">
                                                    <small class="text-danger err" id="logo-err-popup"></small>
                                                </div>
                                            </div>
                                        </div>
                                     <div class="form-group" id="errorBlock">
                                         <ul id="showErrors" class="text-danger"></ul>
                                     </div>
                                        <button type="button" class="btn btn-primary" id="createServiceBtn" ><i class="ik save ik-save"></i>Submit</button>

                                        <a href="{{ route('admin.service.index') }}" class="btn btn-light"><i class="ik arrow-left ik-arrow-left"></i> Go Back</a>
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
        $("#createServiceBtn").click(function(event){
            var formIdPopup = "#createServicePopup";
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
                        $("#createServiceBtn").prop('disabled',true);
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
                        $('#showModelService').modal('toggle');
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
                        $("#createServiceBtn").prop('disabled',false);
                    },3000);
                    $(".progress-bar-popup").width(0 + '%');
                }
            });
        });
    });

</script>










