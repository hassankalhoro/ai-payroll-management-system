@extends('employee.layout.app')

@section('title') Dashboard @endsection

@section('css')
<style type="text/css">

</style>
@endsection

@section('content')

<div class="page-header">
  <div class="row align-items-end">
   <div class="col-lg-8">
    <div class="page-header-title">
     <i id="showMenuBar" class="ik ik-bar-chart bg-blue"></i>
     <div class="d-inline">
      <h5>Dashboard</h5>
      <span>This is dashboard of the PeSystem.</span>
    </div>
  </div>
</div>
<div class="col-lg-4">
  <nav class="breadcrumb-container" aria-label="breadcrumb">
   <ol class="breadcrumb">
    <li class="breadcrumb-item">
     <a href="../../index.html"><i class="ik ik-home"></i></a>
   </li>
   <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
 </ol>
</nav>
</div>
</div>
</div>

<div class="container-fluid">
  <div class="row clearfix">
    <div class="col-lg-3 col-md-6 col-sm-12 cursure-pointer">
      <a href="#">
        <div class="widget bg-primary">
          <div class="widget-body">
            <div class="d-flex justify-content-between align-items-center">
              <div class="state">
                <h6>Total Employees</h6>
                <h2>{{ !empty($counts['employees'])?$counts['employees']:0 }}</h2>
              </div>
              <div class="icon">
                <i class="ik ik-users"></i>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 cursure-pointer">
      <a href="#">
        <div class="widget bg-success">
          <div class="widget-body">
            <div class="d-flex justify-content-between align-items-center">
              <div class="state">
                <h6>On Time Percentage</h6>
                <h2>{{ !empty($counts['on_time_perc'])?$counts['on_time_perc']:0 }}%</h2>
              </div>
              <div class="icon">
                <i class="ik ik-pie-chart"></i>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 cursure-pointer">
      <a href="#">
        <div class="widget bg-warning">
          <div class="widget-body">
            <div class="d-flex justify-content-between align-items-center">
              <div class="state">
                <h6>On Time Today</h6>
                <h2>{{ !empty($counts['on_time_attendance'])?$counts['on_time_attendance']:0 }}</h2>
              </div>
              <div class="icon">
                <i class="ik ik-clock"></i>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 cursure-pointer">
      <a href=#>
        <div class="widget bg-danger">
          <div class="widget-body">
            <div class="d-flex justify-content-between align-items-center">
              <div class="state">
                <h6>Late Today</h6>
                <h2>{{ !empty($counts['late_attendance'])?$counts['late_attendance']:0 }}</h2>
              </div>
              <div class="icon">
                <i class="ik ik-alert-circle"></i>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>
    <div class="row clearfix">
    <div class="col-lg-3 col-md-6 col-sm-12 cursure-pointer">
      <a href="#">
        <div class="widget bg-primary">
          <div class="widget-body">
            <div class="d-flex justify-content-between align-items-center">
              <div class="state">
                <h6>Total Past Payrolls</h6>
                <h2>{{ $employees_past_payroll }}</h2>
              </div>
              <div class="icon">
                <i class="ik ik-dollar-sign bg-blue"></i>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 cursure-pointer">
            <a href="#">
                <div class="widget bg-success">
                    <div class="widget-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="state">
                                <h6>Total Current Month Payrolls</h6>
                                <h2>{{ $amount_payroll_total }}</h2>
                            </div>
                            <div class="icon">
                                <i class="ik ik-dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
  </div>
<div class="container-fluid">
  <div class="row clearfix">
    <div class="col-lg-3 col-md-6 col-sm-12 cursure-pointer">
      <a href="#">
          <div id="c3-pie-chart-dashboard" class="d-flex justify-content-between align-items-center">

          </div>
      </a>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 cursure-pointer">
      <a href="#">
          <div id="c3-donut-chart" class="d-flex justify-content-between align-items-center">

          </div>
      </a>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 cursure-pointer">
      <a href="#">
          <div id="c3-line-chart" class="d-flex justify-content-between align-items-center">

          </div>
      </a>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 cursure-pointer">
      <a href="#">
          <div id="c3-bar-chart" class="d-flex justify-content-between align-items-center">

          </div>
      </a>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-6 col-md-6 col-sm-12">
      <div class="card table-card">
        <div class="card-header">
          <h3>Somthing about Deductions </h3>
          <div class="card-header-right"></div>
        </div>
        <div class="card-block pb-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0 without-header">
              <tbody>
                @foreach($deductions as $deduction)
                <tr>
                  <td>
                    <h3>{{ $deduction->amount }}</h3>
                  </td>
                  <td>
                    <p class="font-weight-bold">{{ $deduction->name }}</p>
                    <p>{{ $deduction->description }}</p>
                  </td>
                </tr>
                @endforeach
                <tr>
                  <td colspan="2" class="text-right text-primary">Rs.{{ $total_deduction }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12">
      <div class="card latest-update-card">
        <div class="card-header">
          <h3>Latest Positions</h3>
          <div class="card-header-right"></div>
        </div>
        <div class="card-block">
          <div class="scroll-widget">
            <div class="latest-update-box">
              @foreach($positions as $position)
              <div class="row pt-20 pb-30">
                <div class="col-auto text-right update-meta pr-0">
                  <i class="b-primary update-icon ring"></i>
                </div>
                <div class="col pl-5">
                  <a href="#!"><h6>{{ $position->title }}</h6></a>
                  <p class="text-muted mb-0">{{ $position->description }}</p>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('js')
<script type="text/javascript" src="{{ asset('admin_assets/js/charts.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
    var c3PieChartInvoice = c3.generate({
        bindto: '#c3-pie-chart-dashboard',
        data: {
            // iris data from R
            columns: [
                ['data1', 30],
                ['data2', 120],
            ],
            type: 'pie',
            pie: {
                label: {
                    format: function(value, ratio, id) {
                        return value;
                    }
                }
            },
            onclick: function(d, i) {
                //console.log("onclick", d, i);
            },
            onmouseover: function(d, i) {
                //d.value="1000";

                // console.log("onmouseover", d.value, i);
            },
            onmouseout: function(d, i) {
                //console.log("onmouseout", d, i);
            }
        },
        /*tooltip: {
            format: {

                value: function (value, ratio, id) {

                    return value;
                }
            }
//            value: d3.format(',') // apply this format to both y and y2
            },*/
        pie: {
            label: {
                format: function(value, ratio, id) {
                    return d3.format('$')(value);
                }
            }
        },
        color: {
            pattern: ['#6153F9', '#8E97FC', '#A7B3FD']
        },
        padding: {
            top: 0,
            right: 0,
            bottom: 30,
            left: 0,
        }
    });
    setTimeout(function() {
        c3PieChartInvoice.load({
            columns: [
                ["Paid Amount", {{ !empty($total_paid)?$total_paid:0 }}],
                ["UnPaid Amount", {{ !empty($total_unpaid)?$total_unpaid:0 }}],
                ["Partial Paid Amount", {{ !empty($total_remaining)?$total_remaining:0 }}],
                ["Total Amount", {{ (!empty($total_paid)?$total_paid:0)+(!empty($total_unpaid)?$total_unpaid:0)+(!empty($total_remaining)?$total_remaining:0) }}],
            ],

        });
    }, 1500);
    setTimeout(function() {
        c3PieChartInvoice.unload({
            ids: 'data1'
        });
        c3PieChartInvoice.unload({
            ids: 'data2'
        });
        c3PieChartInvoice.unload({
            ids: 'data3'
        });
    }, 2500);
    });
</script>
@endsection
