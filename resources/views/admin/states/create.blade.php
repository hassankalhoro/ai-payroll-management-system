@extends('admin.layout.app')

@section('title') Create Position @endsection

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
              <h5>Create State</h5>
              <span>Create State, Please fill all field correctly.</span>
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
             <a href="{{ route('admin.states.index') }}">State</a>
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
                    <span class="overlay-text">New State Creating...</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="position">
                        <h5 class="text-secondary">Create State</h5>
                    </div>
                </div>

                <form action="{{ $form_store }}" method="POST" id="createState">
                    @csrf
                    <div class="form-group">
                        <label for="title">Title</label><small class="text-danger">*</small>
                        <input type="text" name="title" class="form-control" id="title" placeholder="ex: Alaska" autocomplete="on">
                        <small class="text-danger err" id="title-err"></small>
                    </div>
                    <div class="form-group">
                        <label for="title">Short Code</label><small class="text-danger">*</small>
                        <input type="text" name="shortcode" class="form-control" id="shortcode" placeholder="ex: AL" autocomplete="off">
                        <small class="text-danger err" id="shortcode-err"></small>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="ik save ik-save"></i>Submit</button>

                    <a href="{{ route('admin.states.index') }}" class="btn btn-light"><i class="ik arrow-left ik-arrow-left"></i> Go Back</a>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
$(document).ready(function($) {
  $("#createState").submit(function(event){
    event.preventDefault();
    createForm("#createState");
  });
});
</script>
@endsection
