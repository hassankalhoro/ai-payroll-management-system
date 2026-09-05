<!--data here-->
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade active show" id="live" role="tabpanel" aria-labelledby="pills-timeline-tab">

    <!--Live Overtime Data-->
    <div class="card-header">
      <div class="col-md-6 d-block">
        <a href="{{ $add_new }}" class="btn btn-sm btn-dark float-left"><i class="ik plus-square ik-plus-square"></i> Create New Bank Transaction</a>
      </div>
     <div class="col-md-3 d-block">
        <a href="{{ $import_new }}" class="btn btn-sm btn-dark float-left"><i class="ik plus-square ik-plus-square"></i> Import New Bank Transaction</a>
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-primary mb-2 h-33 float-right move-to-delete-all" id="apply" disabled="true" data-href="{{ $moveToTrashAllLink }}">Action</button>
      </div>
    </div>

    <div class="card-body table-responsive">
        <table id="banktransaction_data_table" class="table table-striped">
          <thead>
            <tr>
              <th>Transaction ID</th>
              <th>Account Number</th>
              <th>Transaction Date</th>
              <th>Category</th>
              <th>Amount</th>
              <th>Remaining Balance</th>
              <th>Created At</th>
              <th>Actions</th>
              <th></th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
    </div>
    <!--End Live Overtime Data-->

  </div>
</div>
<!--End data here-->

<script type="text/javascript">

  $(document).ready(function() {

  // get data from serve ajax
  var merchantDataTable = $("table#banktransaction_data_table").DataTable({
    "processing": true,
    "serverSide": true,
    "pagingType":"full_numbers",
    "pageLength":25,
    "autoWidth": false,
    "lengthMenu": [ [10,10, 25, 50, 100,-1], [10,10, 25, 50,100, "All"] ],
    "ajax": {
      "url": "{{ $getDataTable }}",
      "type": "POST"
    },
    "columnDefs": [
    {
      'targets': 8,
      'searchable':false,
      'orderable':false,
      'render': function (data, type, full, meta){
       return "<div class='custom-control custom-checkbox pl-1 align-self-center'><label class='custom-control custom-checkbox mb-0'><input type='checkbox' class='custom-control-input sub_chk' data-id='"+$('<div/>').text(data).html()+"'><span class='custom-control-label'></span></label></div>";
     }
   },
   {
    'targets': [0,5,6],
    'searchable':false,
    'orderable':false,
    "className": "text-center"
  }
  ],
  "columns":[
  {"data":"id"},
  {"data":"account"},
  {"data":"transaction_date"},
  {"data":"category"},
  {"data":"amount"},
  {"data":"remaining_balance"},
  {"data":"created_at"},
  {"data":"action"},
  {"data":"id"},
  ],
});

});

</script>
