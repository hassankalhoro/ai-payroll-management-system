@extends('admin.layout.app')

@section('title') Profile ({{ $user['username'] }}) - ApiDocs @endsection

@section('css')
<style type="text/css">

</style>
@endsection

@section('content')

<div class="page-header">
  <div class="row align-items-end">
     <div class="col-lg-8">
        <div class="page-header-title">
           <i class="ik user ik-user bg-blue"></i>
           <div class="d-inline">
              <h5>General Settings</h5>
              <span>Here you can view and edit your site general/payroll setting detailes.</span>
          </div>
      </div>
  </div>
  <div class="col-lg-4">
    <nav class="breadcrumb-container" aria-label="breadcrumb">
       <ol class="breadcrumb">
          <li class="breadcrumb-item">
             <a href="{{ $user['name'] }}"><i class="ik ik-home"></i></a>
         </li>
         <li class="breadcrumb-item">
             <a href="#">Settings</a>
         </li>
         <li class="breadcrumb-item active" aria-current="page">{{ $user['username'] }}</li>
     </ol>
 </nav>
</div>
</div>
</div>

<div class="row">
    <div class="col-lg-8 col-md-7">
        <div class="card">

            <div class="tab-content" id="pills-tabContent">

                <div class="tab-pane fade active show" id="previous-month" role="tabpanel" aria-labelledby="pills-setting-tab">
                    <div class="card-body">

                        @if($errors->any())
                        <div class="alert {{ session()->get('bgcolor') }} text-light alert-dismissible fade show" role="alert">
                            @foreach ($errors->all() as $error)
                            <span>{{ $error }}</span>
                            @endforeach
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="ik ik-x"></i>
                            </button>
                        </div>

                        @endif



                        <form class="form-horizontal" method="post" action="{{ $form_url }}">
                            @csrf

                            <div class="form-group">
                                <label for="account_type">Payroll cycle</label><br>
                                <select class="form-control" id="payroll_cycle" name="payroll_cycle">
                                    <option selected value disabled>choose</option>
                                    <option {{ (!empty($settingsObj->payroll_cycle) && $settingsObj->payroll_cycle=='1')?"selected":"" }} value="1">Weekly</option>
                                    <option {{ (!empty($settingsObj->payroll_cycle) && $settingsObj->payroll_cycle=='2')?"selected":"" }} value="2">Biweekly</option>
                                    <option {{ (!empty($settingsObj->payroll_cycle) && $settingsObj->payroll_cycle=='3')?"selected":"" }} value="3">Monthly</option>
                                    <option {{ (!empty($settingsObj->payroll_cycle) && $settingsObj->payroll_cycle=='4')?"selected":"" }} value="4">Other</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="email">Recieve notifications over Email</label>
                                <input type="email" placeholder="johnathan@admin.com" class="form-control" name="notification_email" id="notification_email" value="{{ !empty($settingsObj->notification_email)?$settingsObj->notification_email:$user['email'] }}" >
                            </div>
                            <div class="form-group">
                                <label for="number_of_hours">Number of Hours in Attendece</label>
                                <input type="number" placeholder="9" class="form-control" name="number_of_hours" id="number_of_hours" value="{{ !empty($settingsObj->number_of_hours)?$settingsObj->number_of_hours:$user['number_of_hours'] }}" >
                            </div>
                            <button class="btn btn-success" type="submit">Update Settings</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">

</script>
@endsection
