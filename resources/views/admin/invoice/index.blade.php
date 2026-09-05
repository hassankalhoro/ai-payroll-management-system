@extends('admin.layout.app')

@section('title') Invoice @endsection


@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
          <h5>{{ !empty($customerDetail->title)?'Customer ':'' }} Invoices</h5>
          <span>You can show and manage Invoices {{ !empty($customerDetail->title)?'of '.$customerDetail->title:'' }} from here.</span>
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
            <a href="{{ route('admin.invoice.index') }}">Invoices</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">List of Invoices</li>
        </ol>
      </nav>
    </div>
  </div>
    @if(!empty($customerDetail))
    <div class="row">
        <div class="col-lg-12 col-md-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-4">
                        <table  class="total-table">
                            <tbody><tr class="bold">
                                <td colspan="4">Customer Name : </td>

                                <td>{{ $customerDetail->title }}</td>
                            </tr>

                            <tr>
                                <td colspan="4">Email : </td>

                                <td>{{ $customerDetail->email }}</td>
                            </tr>
                            @if(!empty($customerDetail->logo))
                            <tr>
                                <td colspan="4">Logo : </td>

                                <td><img src="{{ asset('admin_assets/tenant_logos/'.$customerDetail->logo) }}" class="circle-temp" id="avatar-logo"></td>
                            </tr>
                            @endif

                            <tr class="bold">
                                <td colspan="4">Tax (%) : </td>

                                <td>{{ $customerDetail->tax }}</td>
                            </tr>

                            </tbody></table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endif

  <div class="row">
    <div class="col-lg-12 col-md-12 mt-4">
      <div class="card">
          <div class="card-header">
              <div class="col-md-6 d-block">
                  <a href="{{ $add_new }}" class="btn btn-sm btn-dark float-left"><i class="ik plus-square ik-plus-square"></i> Create New Invoice</a>
              </div>

          </div>
          <div class="container-fluid">
              <div class="row clearfix">
                  <div class="col-lg-6 col-md-6 col-sm-12 cursure-pointer">
                      <a href="#">
                          <div id="c3-pie-chart-invoice" class="d-flex justify-content-between align-items-center">

                          </div>
                      </a>
                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 cursure-pointer">
                      <a href="#">
                          <div id="c3-line-chart-invoice" class="d-flex justify-content-between align-items-center">

                          </div>
                      </a>
                  </div>
              </div>
          </div>
          <div class="card-header">
            <div class="col-md-5">
                <div class="input-group mb-0">
                    <span class="input-group-prepend">
                        <label class="input-group-text"><i class="ik ik-calendar"></i></label>
                    </span>
                    <input type="text" class="form-control form-control-bold text-center" placeholder="From date - To date" id="date">
                    <span class="input-group-append">
                        <label class="input-group-text"><i class="ik ik-calendar"></i></label>
                    </span>
                </div>
            </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="customer_selected_id">Customers</label>
                      <select class="form-control select2" name="customer_selected_id" id="customer_selected_id">
                          <option value="">Select Customer</option>
                          @foreach($customers as $customer)
                              <option value="{{ $customer->id }}" {{ (!empty($customer_id) && $customer_id == $customer->id) ? 'selected' : ''}}>{{ $customer->title.'-'.$customer->email }}</option>
                          @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="merged_invoices">Show Merged Invoices</label>
                      <select class="form-control select2" name="merged_invoices" id="merged_invoices">
                          <option value=""  >Show All</option>
                          <option value="2" {{ (!empty($merged) && $merged==2)?"selected":"" }} >Show Merged Only</option>
                          <option value="1" {{ (!empty($merged) && $merged==1)?"selected":"" }} >Show Individual</option>
                      </select>
                  </div>
              </div>

          </div>
          <div class="card-header">
            <div class="col-md-2">
                <div class="row clearfix">
                    <a href="javascript:daterangepickerfn('2')">
                        <div class="widget bg-primary">
                            <div class="widget-body">
                                <h6>{{date('F', strtotime("-2 month"))}}</h6>
                            </div>
                        </div>
                    </a>

                </div>
            </div>
            <div class="col-md-2">
                <div class="row clearfix">
                    <a href="javascript:daterangepickerfn('1')">
                        <div class="widget bg-primary">
                            <div class="widget-body">
                                <h6>{{date('F', strtotime("-1 month"))}}</h6>
                            </div>
                        </div>
                    </a>

                </div>
            </div>
            <div class="col-md-2">
                <div class="row clearfix">
                    <a href="javascript:daterangepickerfn('0')">
                        <div class="widget bg-success">
                            <div class="widget-body">
                                <h6>{{date('F')}}</h6>
                            </div>
                        </div>
                    </a>

                </div>
            </div>
              <div class="col-md-2">
                  <div class="row clearfix">
                      <a href="javascript:daterangepickerfn('-1')">
                          <div class="widget bg-warning">
                              <div class="widget-body">
                                  <h6>{{date('F', strtotime("+1 month"))}}</h6>
                              </div>
                          </div>
                      </a>

                  </div>
              </div>
              <div class="col-md-2">
                  <div class="row clearfix">
                      <a href="javascript:daterangepickerfn('-2')">
                          <div class="widget bg-warning">
                              <div class="widget-body">
                                  <h6>{{date('F', strtotime("+2 month"))}}</h6>
                              </div>
                          </div>
                      </a>

                  </div>
              </div>
              <div class="col-md-2">
                  <div class="row clearfix">
                      <a href="javascript:daterangepickerfn('-3')">
                          <div class="widget bg-danger">
                              <div class="widget-body">
                                  <h6>{{date('F', strtotime("+3 month"))}}</h6>
                              </div>
                          </div>
                      </a>

                  </div>
              </div>

          </div>

        <!--Tab content-->
        <div class="loader br-4 hidden">
          <i class="ik ik-refresh-cw loading"></i>
          <span class="loader-text">Data Fetching....</span>
        </div>
        <div class="tabs_contant">
          <div class="card-header">
            <h5>List of Invoices</h5>
          </div>
          <div class="card-body">

          </div>
        </div>
        <!--End Tab Content-->
      </div>
    </div>
  </div>

</div>

<div class="row">
    <div class="col-md-12">
        <form id="deleteForm" method="post">
            @csrf
            @method('DELETE')
            <input type="submit" name="submit" class="hidden">
        </form>
    </div>
</div>

<div class="showModel">

</div>

@endsection

@section('js')

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript">
    var datePickerPlug=null;
$(document).ready(function() {
    function cb(start, end) {
        console.log(start.format('MMMM D, YYYY'))
        var startDate = start.format('YYYY-M-D');
        var endDate = end.format('YYYY-M-D');
        const getDataUrl = "{{ $get_data }}&startDate="+startDate+"&endDate="+endDate;
        getData(getDataUrl);
        //$('#reportrange span').html(start.format('M-D-YYYY') + ' - ' + end.format('M-D-YYYY'));
    }
    var crntDate = moment().format('MMMM DD, YYYY');
    var lastDate = moment().subtract(30, 'days').format('MMMM DD, YYYY');
    datePickerPlug = $('#date').daterangepicker({
        "startDate": lastDate,
        "endDate": crntDate,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: {format: 'MMMM DD, YYYY'},
    }, cb);

  // get data from serve ajax
  const getDataUrl = "{{ $get_data }}"
  getData(getDataUrl);


  //show employee
  $(document).on('click','a.show-invoice',function(){
    var showUrl = $(this).data('href');
    showDetails(showUrl);
  });
    $('#customer_selected_id').on('change', function() {
        var selected_customer = $(this).find(":selected").val();
        var merged_invoices = $('#merged_invoices').find(":selected").val();
        if(selected_customer>0)
        {
            if(merged_invoices>0)
            {
                window.location = "{{ url('invoice/listing') }}/"+selected_customer+"/"+merged_invoices;
            }
            else
            {
                window.location = "{{ url('invoice/listing') }}/"+selected_customer;
            }

        }
        else
        {
            window.location = "{{ url('invoice') }}";
        }

    });
    $('#merged_invoices').on('change', function() {
        var merged_invoices = $(this).find(":selected").val();
        var selected_customer = $('#customer_selected_id').find(":selected").val();
        if(merged_invoices>0)
        {
            merged_invoices = $.trim(merged_invoices);
            if(selected_customer>0)
            {
                window.location = "{{ url('invoice/listing') }}/"+selected_customer+"/"+merged_invoices;
            }
            else
            {
                window.location = "{{ url('invoice/listing') }}/0/"+merged_invoices;
            }

        }
        else
        {
            window.location = "{{ url('invoice') }}";
        }

    });

});
function daterangepickerfn(dateVar)
{
    var date = new Date(), y = date.getFullYear(), m = date.getMonth();
    var firstDay = new Date(y, m-dateVar, 1);
    var lastDay = new Date(y, (m-dateVar) + 1, 0);
    const firstformattedDate = `${firstDay.getFullYear()}-${firstDay.getMonth() + 1}-${firstDay.getDate()}`;
    const lastformattedDate = `${lastDay.getFullYear()}-${lastDay.getMonth() + 1}-${lastDay.getDate()}`;
    const getDataUrl = "{{ $get_data }}&startDate="+firstformattedDate+"&endDate="+lastformattedDate;

    getData(getDataUrl);
    $('#date').data('daterangepicker').setStartDate(firstDay);
    $('#date').data('daterangepicker').setEndDate(lastDay);
}
</script>
@endsection
