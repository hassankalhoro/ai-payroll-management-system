@extends('admin.layout.app')

@section('title') {{ !empty($ratecard->id)?"Edit":"Create" }} Rate Card @endsection

@section('css')

<style type="text/css">
    .overflow-visible{
        overflow: visible !important;
    }
    .modal-sm{
      width: auto;
      max-width: 356px !important;
    }
    .select2-container--default {
      display: block;
      width: auto !important;
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
              <h5>Rate Card</h5>
              <span>{{ !empty($ratecard->id)?"Edit":"Create" }} Rate Card, Please fill all field correctly.</span>
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
             <a href="{{ route('admin.employeeratecard.index') }}">Rate Card</a>
         </li>
         <li class="breadcrumb-item active" aria-current="page">{{ !empty($ratecard->id)?"Edit":"Create" }}</li>
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
                    <span class="overlay-text">Rate Card {{ !empty($ratecard->id)?"Editing":"Creating" }}...</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="state">
                        <h5 class="text-secondary">{{ !empty($ratecard->id)?"Edit":"Create" }} Rate Card</h5>
                    </div>
                </div>

                <form action="{{ $form_store }}" method="POST" enctype="multipart/form-data" id="createRateCard">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="employee_id">Employee</label><small class="text-danger">*</small>
                                <select required class="form-control" name="employee_id" id="employee_id" >
                                    @foreach($employees as $employee)
                                        <option data-rate="{{ !empty($employee->rate_per_hour)?$employee->rate_per_hour:0 }}" {{ (!empty($ratecard->employee_id) && $ratecard->employee_id==$employee->id)?"selected":"" }} value="{{ $employee->id }}">{{ $employee->first_name." ".$employee->last_name." (#".$employee->employee_id.")" }}</option>
                                    @endforeach
                                </select>
                                <small class="text-danger err" id="employee_id-err"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-4 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="year">Year</label><small class="text-danger">*</small>
                                <input  type="hidden" name="id" class="form-control" id="id" value="{{ !empty($ratecard->id)?$ratecard->id:0 }}" >
                                <input required type="number" name="year" class="form-control" id="year" value="{{ !empty($ratecard->year)?$ratecard->year:date('Y') }}" placeholder=" {{date('Y')}} " autocomplete="off">
                                <small class="text-danger err" id="year-err">Numeric Year value {{date('Y')}} .</small>
                            </div>
                        </div>
                      <div class="col-md-4 col-lg-6 col-sm-12">
                          <label for="month">Month</label><small class="text-danger">*</small>
                          <select required class="form-control" name="month" id="month">
                              <option {{ !empty($ratecard->month) && $ratecard->month=='jan' ? 'selected' : '' }} value="jan">January</option>
                              <option {{ !empty($ratecard->month) && $ratecard->month=='feb' ? 'selected' : '' }} value="feb">February</option>
                              <option {{ !empty($ratecard->month) && $ratecard->month=='mar' ? 'selected' : '' }} value="mar">March</option>
                              <option {{ !empty($ratecard->month) && $ratecard->month=='apr' ? 'selected' : '' }} value="apr">April</option>
                              <option {{ !empty($ratecard->month) && $ratecard->month=='may' ? 'selected' : '' }} value="may">May</option>
                              <option {{ !empty($ratecard->month) && $ratecard->month=='jun' ? 'selected' : '' }} value="jun">June</option>
                              <option {{ !empty($ratecard->month) && $ratecard->month=='jul' ? 'selected' : '' }} value="jul">July</option>
                              <option {{ !empty($ratecard->month) && $ratecard->month=='aug' ? 'selected' : '' }} value="aug">August</option>
                              <option {{ !empty($ratecard->month) && $ratecard->month=='sep' ? 'selected' : '' }} value="sep">September</option>
                              <option {{ !empty($ratecard->month) && $ratecard->month=='oct' ? 'selected' : '' }} value="oct">October</option>
                              <option {{ !empty($ratecard->month) && $ratecard->month=='nov' ? 'selected' : '' }} value="nov">November</option>
                              <option {{ !empty($ratecard->month) && $ratecard->month=='dec' ? 'selected' : '' }} value="dec">December</option>

                          </select>
                          <small class="text-danger err" id="hour-err"></small>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 col-lg-6 col-sm-12">
                       <div class="form-group">
                        <label for="rate">Rate Per Hour</label><small class="text-danger">*</small>
                        <input type="number" readonly step=".01" name="rate" class="form-control" id="rate" placeholder="200.00" autocomplete="off" value="{{ !empty($ratecard->employee->rate_per_hour)?$ratecard->employee->rate_per_hour:"0" }}" >
                        <small class="text-danger err" id="rate-err">It's important for amount calculation.</small>
                      </div>
                      </div>
                      <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="hours">Hours</label><small class="text-danger">*</small>
                                <input type="number" step=".01" name="hours" class="form-control" id="hours" placeholder="200.00" autocomplete="off" value="{{ !empty($ratecard->hours)?$ratecard->hours:"0" }}">
                                <small class="text-danger err" id="hours-err"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="charges">Other Charges</label><small class="text-danger">*</small>
                                <input type="number" step=".01" name="charges" class="form-control" id="charges" placeholder="200.00" autocomplete="off" value="{{ !empty($ratecard->charges)?$ratecard->charges:"0" }}">
                                <small class="text-danger err" id="hours-err"></small>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="total">Total</label><small class="text-danger">*</small>
                                <input type="number" readonly step=".01" name="total" class="form-control" id="total" placeholder="200.00" autocomplete="off" value="{{ !empty($ratecard->total)?$ratecard->total:"0" }}">
                                <small class="text-danger err" id="total-err"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 col-lg-12 col-sm-12">
                        <button type="submit" class="btn btn-primary"><i class="ik save ik-save"></i>Submit</button>
                        <a href="{{ route('admin.employeeratecard.index') }}" class="btn btn-light"><i class="ik arrow-left ik-arrow-left"></i> Go Back</a>
                      </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
    calculate();
function calculate()
{
    var rate = $('#rate').val();
    var hours = $('#hours').val();
    var charges = $('#charges').val();

    rate =  parseFloat(rate).toFixed(2);
    hours = parseFloat(hours).toFixed(2);
    charges = parseFloat(charges).toFixed(2);
    var ratePer = rate*hours;
    ratePer = parseFloat(ratePer).toFixed(2);
    var final_payment = ratePer+charges;
    final_payment = parseFloat(final_payment).toFixed(2);
    $('#total').val(final_payment);
}
$(document).ready(function($) {

    $("#employee_id").select2();
    $('#rate').val($('#employee_id').find(':selected').data('rate'));
    $('#employee_id').change(function(){
        $('#rate').val($(this).children('option:selected').data('rate'));
    });
    $('#rate, #hours, #charges').change(function(){
        calculate();
    });



  $("#createRateCard").submit(function(event){
    event.preventDefault();
    createForm("#createRateCard");
  });
});
</script>
@endsection
