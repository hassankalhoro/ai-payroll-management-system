@extends('admin.layout.app')

@section('title') Employees @endsection

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
          <h5>Import Employees</h5>
          <span>You can import Employees from here.(xls file)</span>
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
            <a href="{{ route('admin.employee.employeeImport') }}">Import Employees</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">List of Employees</li>
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
            <h5>List of Employees</h5>
          </div>
          <div class="card-body">
              <div style="margin-right:0 !important;margin-left:0 !important;" class="row">


                  <div class="col-md-6 col-lg-6 col-sm-12">
                      <div class="form-group">
                          <label for="deposite_amount">Sample File</label><br>
                          <a href="{{ asset('admin_assets/default/SampleEmployees.xlsx') }}"  class="btn btn-primary mb-2 h-33 float-left" >Download Sample file</a>

                          <small class="text-danger err" id="deposite_amount-err">Import xls file of same data please import data with diffrent email/phone/ssn !</small>

                      </div>
                  </div>

              </div>

              <form action="{{ $form_store }}" method="POST" id="importExcelFile">
                  @csrf
                  <div class="form-group">
                      <label for="title">Employees XLS File</label><small class="text-danger">*</small>
                      <input type="file" name="xlsEmployeeFile" id="xlsEmployeeFile" class="form-control">
                      <small class="text-danger err" id="title-err"></small>
                  </div>
                  <div class="form-group" id="errorBlock">
                      <ul id="showErrors" class="text-danger"></ul>
                  </div>
                  <button type="submit" class="btn btn-primary"><i class="ik save ik-save"></i>Submit</button>

                  <a href="{{ route('admin.employee.index') }}" class="btn btn-light"><i class="ik arrow-left ik-arrow-left"></i> Go Back</a>
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
