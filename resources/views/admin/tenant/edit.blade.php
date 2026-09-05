@extends('admin.layout.app')

@section('title') {{ $tenant->title }} - Edit Customer @endsection

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
           <i class="ik ik-clock bg-blue"></i>
           <div class="d-inline">
              <h5>Customer</h5>
              <span>Edit Customer, Please fill all field correctly.</span>
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
             <a href="{{ route('admin.tenant.index') }}">Customer</a>
         </li>
         <li class="breadcrumb-item">
             <a href="#">Edit</a>
         </li>
         <li class="breadcrumb-item active" aria-current="page">{{ $tenant->title }}</li>
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
                    <span class="overlay-text">Customer {{ $tenant->title }} Updating...</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="Schedule">
                        <h5 class="text-secondary">Edit {{ $tenant->title }} Customer</h5>
                    </div>
                </div>

                <form action="{{ $form_update }}" method="POST" id="editTenant">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="title">Title</label><small class="text-danger">*</small>
                                <input type="text" name="title" class="form-control" id="title" placeholder="VF Corp" autocomplete="off" value="{{ $tenant->title }}">
                                <small class="text-danger err" id="title-err"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="email">Email</label><small class="text-danger">*</small>
                                <input type="email" name="email" class="form-control" id="email" placeholder="john@example.com" autocomplete="off" value="{{ $tenant->email }}">
                                <small class="text-danger err" id="email-err"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="tax">Tax (%)</label>
                                <input  type="number" step=".01" name="tax" class="form-control" id="tax" placeholder="0" autocomplete="off" value="{{ $tenant->tax }}">
                                <small class="text-danger err" id="tax-err"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea  name="address" class="form-control" id="address" autocomplete="off" value="{{ $tenant->address }}">{{ $tenant->address }}</textarea>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="email">Logo</label>
                                <input type="file" name="logo" class="form-control">
                                <small class="text-danger err" id="logo-err"></small>
                            </div>
                        </div>
                    </div>
                    @if(!empty($tenant->logo))
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-12 {{ (!$tenant->id) ? 'hidden' : '' }}" id="show-avatar-div">
                                <div class="form-group my-auto">
                                    <img src="{{ asset('admin_assets/tenant_logos/'.$tenant->logo) }}" class="circle-temp" id="avatar-logo">
                                </div>
                            </div>
                        </div>
                    @endif
                    <button type="submit" class="btn btn-primary"><i class="ik save ik-save"></i>Update</button>

                    <a href="{{ route('admin.tenant.index') }}" class="btn btn-light"><i class="ik arrow-left ik-arrow-left"></i> Go Back</a>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
$(document).ready(function(e){
  $("#editTenant").submit(function(event){
    event.preventDefault();
    editForm("#editTenant");
  });
});
</script>
@endsection
