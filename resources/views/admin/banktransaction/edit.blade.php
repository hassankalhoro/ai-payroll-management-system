@extends('admin.layout.app')

@section('title') {{ $banktransaction->catrgory }} - Edit Bank Transaction @endsection

@section('css')
<style type="text/css">
    .overflow-visible{
        overflow: visible !important;
    }
    .modal-sm{
      width: auto;
      max-width: 356px !important;
    }
</style>
@endsection

@section('content')

<div class="page-header">
  <div class="row align-items-end">
     <div class="col-lg-8">
        <div class="page-header-title">
           <i class="ik ik-watch bg-blue"></i>
           <div class="d-inline">
              <h5>Bank Transaction</h5>
              <span>Edit Bank Transaction, Please fill all field correctly.</span>
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
             <a href="{{ route('admin.banktransaction.index') }}">Bank Transaction</a>
         </li>
         <li class="breadcrumb-item">
             <a href="#">Edit</a>
         </li>
         <li class="breadcrumb-item active" aria-current="page">{{ $banktransaction->category }}</li>
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
                    <span class="overlay-text">Bank Transaction {{ $banktransaction->category }} Updating...</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="state">
                        <h5 class="text-secondary"><i class="ik ik-at-sign"></i>{!! $banktransaction->category !!} Edit</h5>
                    </div>
                </div>

                <form action="{{ $form_update }}" method="POST" enctype="multipart/form-data" id="editBanktransaction">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="account_id">Account </label><small class="text-danger">*</small>
                                <select class="form-control" id="account_id" name="account_id">
                                    <option selected value disabled>choose</option>
                                    @foreach($accounts as $account)
                                        <option {{ (!empty($banktransaction->account_id) && $banktransaction->account_id==$account->id)?"selected":"" }} value="{{ $account->id }}">{{ $account->account_number.'-'.$account->account_title }}</option>
                                    @endforeach
                                </select>
                                <small class="text-danger err" id="account_id-err"></small>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-12">
                            <div class="form-group">
                                <label for="date">Transaction Date</label><small class="text-danger">*</small>
                                <input value="{{$banktransaction->transaction_date}}" type="text" class="form-control datetimepicker-input" name="transaction_date" id="transaction_date" data-toggle="datetimepicker" data-target="#transaction_date" autocomplete="off">
                                <small class="text-danger err" id="transaction_date-err"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="category">Category</label><small class="text-danger">*</small>
                                <input value="{{$banktransaction->category}}" type="text" name="category" class="form-control" id="category" placeholder="Iban" autocomplete="off">
                                <small class="text-danger err" id="category-err">It's important for tansaction.</small>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="amount">Amount</label><small class="text-danger">*</small>
                                <input value="{{$banktransaction->amount}}" step=".01" type="number" name="amount" class="form-control" id="amount" placeholder="0.00" autocomplete="off">
                                <small class="text-danger err" id="amount-err">It's important for tansaction.</small>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="remaining_balance">Remaining Balance</label><small class="text-danger">*</small>
                                <input value="{{$banktransaction->remaining_balance}}" step=".01" type="number" name="remaining_balance" class="form-control" id="remaining_balance" placeholder="0.00" autocomplete="off">
                                <small class="text-danger err" id="amount-err">It's important for tansaction.</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="form-group">
                                <label for="description">Description</label>  <small class="text-secondary">(Optional)</small>
                                <textarea class="form-control" id="description" name="description" rows="3">{{$banktransaction->description}}</textarea>
                            </div>
                        </div>
                    </div>
            <div class="row">
              <div class="col-md-12 col-lg-12 col-sm-12">
                <button type="submit" class="btn btn-primary"><i class="ik save ik-save"></i>Update</button>
                  @if(!empty($banktransaction->account_id))
                      <a href="{{ url('banktransaction/listing/'.$banktransaction->account_id) }}" class="btn btn-light"><i class="ik arrow-left ik-arrow-left"></i> Go Back</a>
                  @else
                      <a href="{{ route('admin.banktransaction.index') }}" class="btn btn-light"><i class="ik arrow-left ik-arrow-left"></i> Go Back</a>
                  @endif              </div>
            </div>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection

@section('js')

<script type="text/javascript">
$(document).ready(function($) {

  let date = $("#transaction_date").data("value");
  $('#transaction_date').datetimepicker({
    defaultDate: date,
    format: 'LL',
  });

  $("#editBanktransaction").submit(function(event){
    event.preventDefault();
    editForm("#editBanktransaction");
  });
});
</script>
@endsection
