@extends('admin.layout.app')

@section('title') {{ $accounts->account_title }} - Edit Account @endsection

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
              <h5>Account</h5>
              <span>Edit Account, Please fill all field correctly.</span>
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
             <a href="{{ route('admin.siteaccounts.index') }}">Account</a>
         </li>
         <li class="breadcrumb-item">
             <a href="#">Edit</a>
         </li>
         <li class="breadcrumb-item active" aria-current="page">{{ $accounts->account_title }}</li>
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
                    <span class="overlay-text">Account {{ $accounts->account_title }} Updating...</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="Position">
                        <h5 class="text-secondary">Edit {{ $accounts->account_title }} Account</h5>
                    </div>
                </div>

                <form action="{{ $form_update }}" method="POST" id="editAccounts">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <label for="account_number">Account Number</label><small class="text-danger">*</small>
                        <input type="text" name="account_number" class="form-control" id="account_number" placeholder="ex:53425342535342" autocomplete="on" value="{{ $accounts->account_number }}" >
                        <input type="hidden" name="id" class="form-control" id="id" value="{{ $accounts->id }}" >
                        <small class="text-danger err" id="account_number-err"></small>
                    </div>
                    <div class="form-group">
                        <label for="account_title">Account Title</label><small class="text-danger">*</small>
                        <input type="text" name="account_title" class="form-control" id="account_title" placeholder="ex:John" autocomplete="on" value="{{ $accounts->account_title }}">
                        <small class="text-danger err" id="account_title-err"></small>
                    </div>
                    <div class="form-group">
                        <label for="bank_name">Bank Name</label><small class="text-danger">*</small>
                        <input type="text" name="bank_name" class="form-control" id="bank_name" placeholder="ex:Bank of America" autocomplete="on" value="{{ $accounts->bank_name }}">
                        <small class="text-danger err" id="bank_name-err"></small>
                    </div>

                    <div class="form-group">
                        <label for="account_type">Address</label>
                        <textarea  name="address" class="form-control" id="address"  autocomplete="off" value="{{ $accounts->address }}">{{ $accounts->address }}</textarea>
                        <small class="text-danger err" id="address-err"></small>
                    </div>
                    <div class="form-group">
                        <label for="parent_id">This is the main account</label>
                        <div class="custom-control custom-checkbox pl-1 align-self-center">
                            <label class="custom-control custom-checkbox mb-0" title="This accout will be used for all transactions" data-toggle="tooltip" data-placement="right"  >
                                <input type="checkbox" {{ (!empty($accounts->is_main_account) && $accounts->is_main_account==1)?'checked':'' }} class="custom-control-input" name="is_main_account" id="is_main_account">
                                <span class="custom-control-label"></span>
                            </label>
                        </div>
                        <small class="text-danger err" id="is_main_account-err"></small>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="ik save ik-save"></i>Update</button>

                    <a href="{{ route('admin.siteaccounts.index') }}" class="btn btn-light"><i class="ik arrow-left ik-arrow-left"></i> Go Back</a>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
$(document).ready(function($) {
  $("#editAccounts").submit(function(event){
    event.preventDefault();
    editForm("#editAccounts");
  });
});
</script>
@endsection
