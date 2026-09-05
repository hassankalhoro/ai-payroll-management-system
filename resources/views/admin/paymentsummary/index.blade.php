@extends('admin.layout.app')

@section('title') Payment Summary @endsection

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
</style>
@endsection

@section('content')

<div class="page-header">
  <div class="row align-items-end">
    <div class="col-lg-8">
      <div class="page-header-title">
        <i class="ik ik-briefcase bg-blue"></i>
        <div class="d-inline">
          <h5>Payment Summary</h5>
          <span>You can show and manage Payment Summary from here.</span>
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
            <a href="{{ route('admin.paymentsummary.index') }}">Payment Summary</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">List of Payment Summary</li>
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
            <h5>List of Payment Summary</h5>
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

<div class="showBannerModel">

</div>
<!-- Modal -->
<div class="modal showBannerModelfade" id="viewFileModel" tabindex="-1" role="dialog" aria-labelledby="myResumeLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content modal-fullscreen">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-fullscreen modal-body">
                <iframe title="Candidate Resume"

                        allowfullscreen width="100%" height="500vh" class="responsive-iframe" id="resumeIfram" ></iframe>
            </div>
            <div class="modal-footer">
                <a type="button" id="downloadXls" class="btn btn-primary mr-auto">Download</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- Modal -->
@endsection

@section('js')
<script type="text/javascript">

$(document).ready(function() {
  // get data from serve ajax
  const getDataUrl = "{{ $get_data }}"
  getData(getDataUrl);

  //single record move to trash
  $(document).on('click','a.move-to-trash',function(){
    var trashRecordUrl = $(this).data('href');
    moveToTrashOrDelete(trashRecordUrl);
  });

  $(document).on('click','a.viewFile',function(){
    var viewRecordUrl = $(this).data('href');
      downloadViewFile(viewRecordUrl,'div.showModel','#viewFileModel','#resumeIfram','#downloadXls');
  });

  //select all checkboxes
  checkbox("#master",".sub_chk",'#apply');

  //selected record move to trash
  $(document).on('click','.move-to-trash-all', function(e) {
    e.preventDefault();
    var trashAllRecordUrl = $(this).data('href');
    moveToTrashAllOrDelete(trashAllRecordUrl);
  });
});
</script>
@endsection
