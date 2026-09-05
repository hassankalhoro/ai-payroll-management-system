@extends('admin.layout.app')

@section('title') Create Account @endsection

@section('css')
<style type="text/css">
    .overflow-visible{
        overflow: visible !important;
    }
</style>
@endsection

@section('content')

<div class="page-header">
  <div class="row align-items-end">
     <div class="col-lg-8">
        <div class="page-header-title">
           <i class="ik ik-briefcase bg-blue"></i>
           <div class="d-inline">
              <h5>Create Account</h5>
              <span>Create Account, Please fill all field correctly.</span>
          </div>
      </div>
  </div>
  <div class="col-lg-4">
    <nav class="breadcrumb-container" aria-label="breadcrumb">
       <ol class="breadcrumb">
          <li class="breadcrumb-item">
             <a href="{{ route('admin.dashboard') }}"><i class="ik ik-home"></i></a>
         </li>
         <li class="breadcrumb-item">
             <a href="{{ route('admin.accounts.index') }}">Account</a>
         </li>
         <li class="breadcrumb-item active" aria-current="page">Create</li>
     </ol>
 </nav>
</div>
</div>
</div>

<div class="row">
    <div class="col-md-6 col-sm-12 col-xl-6 offset-md-3 offset-xl-3">

        <div class="widget overflow-visible">
            <div class="progress progress-sm progress-hi-3 hidden">
                <div class="progress-bar bg-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
            </div>
            <div class="widget-body">
                <div class="overlay hidden">
                    <i class="ik ik-refresh-ccw loading"></i>
                    <span class="overlay-text">New Account Creating...</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="position">
                        <h5 class="text-secondary">Create Account</h5>
                    </div>
                </div>

                <form action="{{ $form_store }}" method="POST" id="createAccounts">
                    @csrf
                    <div class="form-group">
                        <label for="account_type">Account Type</label>
                        <select class="form-control" name="account_type" id="account_type">
                            @if(!empty($account_types))
                                @foreach($account_types as $acc_type)
                                    <option value="{{ $acc_type->id }}">{{ $acc_type->title }}</option>
                                @endforeach
                            @endif
                        </select>
                        <small class="text-danger err" id="parent_id-err"></small>
                    </div>

                    <div class="form-group">
                        <label for="title">Name</label><small class="text-danger">*</small>
                        <input type="text" name="title" class="form-control" id="title" placeholder="ex: Services Account" autocomplete="on">
                        <small class="text-danger err" id="title-err"></small>
                    </div>
                    <div class="form-group">
                        <label for="detail_type">Account Detail Type</label>
                        <select class="form-control" name="detail_type" id="detail_type">
                            @if(!empty($account_detail_types))
                                @foreach($account_detail_types as $acc_detail_type)
                                    <option data="{{ $acc_detail_type->description }}" title="{{ $acc_detail_type->title }}" value="{{ $acc_detail_type->id }}">{{ $acc_detail_type->title }}</option>
                                @endforeach
                            @endif
                        </select>
                        <small class="text-danger err" id="detail_type-err"></small>
                    </div>
                    <div class="form-group">
                        <label for="account_type">Detail</label>
                        <textarea disabled name="description" class="form-control" id="description"  autocomplete="off" value=""></textarea>
                        <small class="text-danger err" id="description-err"></small>
                    </div>
                    <div class="form-group">
                        <label for="parent_id">Parent Account</label>
                        <select class="form-control" name="parent_id" id="parent_id">
                            <option value="0">This is the main Account</option>
                            @if(!empty($accounts))
                                @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->title }}</option>
                                @endforeach
                            @endif
                        </select>
                        <small class="text-danger err" id="parent_id-err"></small>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="ik save ik-save"></i>Submit</button>

                    <a href="{{ route('admin.accounts.index') }}" class="btn btn-light"><i class="ik arrow-left ik-arrow-left"></i> Go Back</a>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
$(document).ready(function($) {
    getDataFilled()
    $('#detail_type').on('change', function() {
        getDataFilled()
    });
    function getDataFilled()
    {
        var desc = $('option:selected', $('#detail_type')).attr('data');
        var title = $('option:selected', $('#detail_type')).attr('title');
        $('#description').val(desc);
        $('#title').val(title);
    }
  $("#createAccounts").submit(function(event){
    event.preventDefault();
    createForm("#createAccounts");
  });
});
</script>
@endsection
