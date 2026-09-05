@extends('admin.layout.app')

@section('title') Transaction @endsection

@section('css')
<style type="text/css">
    .overflow-visible{
        overflow: visible !important;
    }
    td.p-0 img.img-thumbnail{
      width: 140px;
    }
    button.h-33{
      height: 33px !important;
    }
    #map{
      height: 500px;
      border: 2px solid #00000054;
      border-radius: 11px;
    }s
</style>
@endsection

@section('content')

<div class="page-header">
  <div class="row align-items-end">
    <div class="col-lg-8">
      <div class="page-header-title">
        <i class="ik ik-users bg-blue"></i>
        <div class="d-inline">
          <h5>Import Transaction</h5>
          <span>You can import Transaction from here.(xls file)</span>
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
            <a href="{{ route('admin.banktransaction.import_new',['id'=>$account_id]) }}">Import Transaction</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">List of Transaction</li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 mt-4">
      <div class="card">

        <!--Tab content-->
        <div class="loader br-4 hidden">
          <i class="ik ik-refresh-cw loading"></i>
          <span class="loader-text">Data Fetching....</span>
        </div>
        <div class="tabs_contant">
          <div class="card-header">
            <h5>List of Transaction</h5>
          </div>
          <div class="card-body">
              <div style="margin-right:0 !important;margin-left:0 !important;" class="row">


                  <div class="col-md-6 col-lg-6 col-sm-12">
                      <div class="form-group">
                          <label for="deposite_amount">Sample File</label><br>
                          <a href="{{ asset('admin_assets/default/SampleBankTransaction.xlsx') }}"  class="btn btn-primary mb-2 h-33 float-left" >Download Sample file</a>

                          <small class="text-danger err" id="deposite_amount-err">Import xls file of same data please import data with dibanktransactionffrent Data !</small>

                      </div>
                  </div>

              </div>

              <form action="{{ $form_store }}" method="POST" id="importExcelFile">
                  @csrf
                  <div class="form-group">
                      <label for="xlsTransactionFile">Transaction XLS File</label><small class="text-danger">*</small>
                      <input type="file" name="xlsTransactionFile" id="xlsTransactionFile" class="form-control">
                      <input type="hidden" name="account_id" id="account_id" value="{{ $account_id }}" class="form-control">
                      <small class="text-danger err" id="xlsTransactionFile-err"></small>
                  </div>
                  <div class="form-group" id="errorBlock">
                      <ul id="showErrors" class="text-danger"></ul>
                  </div>
                  <button type="submit" class="btn btn-primary"><i class="ik save ik-save"></i>Submit</button>

                  @if(!empty($account_id))
                      <a href="{{ url('banktransaction/listing/'.$account_id) }}" class="btn btn-light"><i class="ik arrow-left ik-arrow-left"></i> Go Back</a>
                  @else
                      <a href="{{ route('admin.banktransaction.index') }}" class="btn btn-light"><i class="ik arrow-left ik-arrow-left"></i> Go Back</a>
                  @endif
              </form>
          </div>
        </div>
        <!--End Tab Content-->
      </div>
    </div>
  </div>

</div>


@endsection

@section('js')

<script type="text/javascript">

$(document).ready(function() {

    $("#importExcelFile").submit(function(event){
        event.preventDefault();
        createForm("#importExcelFile");
    });

});
</script>
@endsection
