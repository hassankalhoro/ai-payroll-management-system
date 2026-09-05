<!--data here-->
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade active show" id="live" role="tabpanel" aria-labelledby="pills-timeline-tab">

    <!--Live Overtime Data-->
    <div class="card-header">
      <div class="col-md-4 d-block">
        <a href="{{ $add_new }}" class="btn btn-sm btn-dark float-left"><i class="ik plus-square ik-plus-square"></i> Create New Rate Card</a>
      </div>
    <div class="col-md-4 d-block">
        <div class="col-md-4 col-lg-4 col-sm-12">
            <label for="of_year">of Year</label><small class="text-danger">*</small>
            <select required class="form-control" name="of_year" id="of_year">
                @if(!empty($pastYears))
                    @foreach($pastYears as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                @endif

            </select>
        </div>
        <a id="exportRecordYearLink" href="#" class="btn btn-sm btn-dark float-left"><i class="ik plus-square ik-plus-square"></i> Export All Records</a>
    </div>
      <div class="col-md-4">
        <button type="submit" class="btn btn-primary mb-2 h-33 float-right move-to-delete-all" id="apply" disabled="true" data-href="{{ $moveToTrashAllLink }}">Action</button>
      </div>
    </div>

    <div class="card-body table-responsive">
        <table id="ratecard_data_table" class="table table-striped">
          <thead>
            <tr>
              <th>Starting Date</th>
              <th>Employee Details</th>
              <th>Year</th>
              <th>Month</th>
              <th>Rate</th>
              <th>Hour</th>
              <th>Total</th>
              <th>Actions</th>
              <th></th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
    </div>
    <!--End Live Overtime Data-->

      <!--Live Overtime Data-->
      <div class="widget-body">
          <div class="row">
              <div class="col-md-6 col-lg-3 col-sm-12">
                  <div class="form-group">
                      <label for="extra_charges">Extra Charges Description</label><small class="text-danger">*</small>
                      <input type="text"  name="extra_charges" class="form-control" id="extra_charges" placeholder="additonal amount applied to records" autocomplete="off" >
                      <input type="hidden"  name="extra_charges_id" class="form-control" id="extra_charges_id" value="0" autocomplete="off" >
                      <small class="text-danger err" id="extra_charges-err"></small>
                  </div>
              </div>
              <div class="col-md-4 col-lg-1 col-sm-12">
                  <div class="form-group">
                      <label for="year">Year</label><small class="text-danger">*</small>
                      <input required type="number" name="year" class="form-control" id="year" value="{{ !empty($ratecard->year)?$ratecard->year:date('Y') }}" placeholder=" {{date('Y')}} " autocomplete="off">
                      <small class="text-danger err" id="year-err">Numeric Year value {{date('Y')}} .</small>
                  </div>
              </div>
              <div class="col-md-4 col-lg-2 col-sm-12">
                  <label for="month">Month</label><small class="text-danger">*</small>
                  <select required class="form-control" name="month" id="month">
                      <option  value="jan">January</option>
                      <option  value="feb">February</option>
                      <option  value="mar">March</option>
                      <option  value="apr">April</option>
                      <option  value="may">May</option>
                      <option  value="jun">June</option>
                      <option  value="jul">July</option>
                      <option  value="aug">August</option>
                      <option  value="sep">September</option>
                      <option  value="oct">October</option>
                      <option  value="nov">November</option>
                      <option  value="dec">December</option>

                  </select>
                  <small class="text-danger err" id="hour-err"></small>
              </div>
              <div class="col-md-6 col-lg-2 col-sm-12">
                  <div class="form-group">
                      <label for="extra_charges_amount">Extra Charges Amount</label><small class="text-danger">*</small>
                      <input type="number"  step=".01" name="extra_charges_amount" class="form-control" id="extra_charges_amount" placeholder="200.00" autocomplete="off" >
                      <small class="text-danger err" id="extra_charges_amount-err"></small>
                  </div>
              </div>
              <div class="col-md-6 col-lg-2 col-sm-12">
                  <div class="form-group">
                  <label for="save_all">Save/Update charges for all records</label><small class="text-danger">*</small>
                  <button type="button" id="add_extra_charges" class="btn btn-primary"><i class="ik  ik-save"></i>Add</button>
                  </div>
              </div>
              <div class="col-md-6 col-lg-2 col-sm-12">
                  <small class="text-success err" id="extra_charges_success-err"></small>
              </div>
          </div>

      </div>

      <div class="card-body table-responsive">
          <table id="ratecard_extra_amount_data_table" class="table table-striped">
              <thead>
              <tr>
                  <th>Date</th>
                  <th>Details</th>
                  <th>Year</th>
                  <th>Month</th>
                  <th>Amount</th>
                  <th>Actions</th>
                  <th>ID</th>
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

      var of_year = $('#of_year').find(':selected').val()
      $('#exportRecordYearLink').attr('href','{{ url('export-all-records/employeeratecard') }}/'+of_year)
      $('#of_year').change(function(){
          var of_year =  $(this).children('option:selected').val();
          $('#exportRecordYearLink').attr('href','{{ url('export-all-records/employeeratecard') }}/'+of_year)
      });

  // get data from serve ajax
  var merchantDataTable = $("table#ratecard_data_table").DataTable({
    "processing": true,
    "serverSide": true,
    "pagingType":"full_numbers",
    "pageLength":25,
    "autoWidth": false,
    "lengthMenu": [ [10, 25, 25, 25, 50, 100,-1], [10, 25, 25, 25, 50,100, "All"] ],
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
    'targets': [0,5,6,6,6],
    'searchable':false,
    'orderable':false,
    "className": "text-center"
  }
  ],
  "columns":[
  {"data":"starting_date"},
  {"data":"employee"},
  {"data":"year"},
  {"data":"month"},
  {"data":"rate"},
  {"data":"hours"},
  {"data":"total"},
  {"data":"action"},
  {"data":"id"},
  ],
});


      $('#add_extra_charges').click(function (){

          var extra_charges_id = $('#extra_charges_id').val();
          var extra_charges = $('#extra_charges').val();
          var extra_charges_amount = $('#extra_charges_amount').val();
          var year = $('#year').val();
          var month = $('#month').val();
          $('#extra_charges-err').html('');
          $('#extra_charges_success-err').html("");
          $('#year-err').html("");
          $('#month-err').html("");
          if($.trim(extra_charges).length<=0)
          {
              $('#extra_charges-err').html('Required!');
          }
          else if($.trim(year).length<=0)
          {
              $('#year-err').html('Required!');
          }
          else if($.trim(month).length<=0)
          {
              $('#month-err').html('Required!');
          }
          else
          {

              $.ajax({
                  url: "{{ url('save-extra-charges-ratecard') }}",
                  type: "POST",
                  data: {"extra_charges_id":extra_charges_id,"extra_charges":extra_charges,'extra_charges_amount':extra_charges_amount,'year':year,'month':month},
                  headers: {
                      'X-CSRF-TOKEN': "{{ csrf_token() }}"
                  },
                  beforeSend:function(){
                      //$("button").prop('disabled',true);
                  },
                  success: function (response) {
                      if(response.message)
                      {
                          $('#add_extra_charges').html('<i class="ik  ik-save"></i>Add');

                          $('#extra_charges_success-err').html(response.message);
                          $('#extra_charges').val("");
                          $('#extra_charges_id').val(0);
                          $('#extra_charges_amount').val(0);
                          loadExtraChargesData();
                      }

                  },
                  complete:function(){
                      //$("button").prop('disabled',false);
                  }
              });
          }


      });

      loadExtraChargesData();

  });
  function loadExtraChargesData()
  {
      $('#ratecard_extra_amount_data_table').DataTable().clear().destroy();
      var chargesDataTable = $("table#ratecard_extra_amount_data_table").DataTable({
          "processing": true,
          "serverSide": true,
          "pagingType":"full_numbers",
          "pageLength":25,
          "autoWidth": false,
          "lengthMenu": [ [10, 25, 25, 25, 100,-1], [10, 25,25,25,100, "All"] ],
          "ajax": {
              "url": "{{ $getExtraDataTable }}",
              "type": "POST"
          },
          "columnDefs": [
              {
                  'targets': 6,
                  'searchable':false,
                  'orderable':false,
                  'render': function (data, type, full, meta){
                      return "";
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
              {"data":"date"},
              {"data":"description"},
              {"data":"year"},
              {"data":"month"},
              {"data":"amount"},
              {"data":"action"},
              {"data":"id"},
              {"data":"id"},
          ],
      });

  }
function updateRecord(id,desc,amount,year,month)
{
    if(id>0)
    {

        $('#add_extra_charges').html('<i class="ik  ik-save"></i>Update');
        $('#extra_charges_id').val(id);
        $('#extra_charges').val(desc);
        $('#year').val(year);
        $('#month').val(month);
        $('#extra_charges_amount').val(amount);
    }
}
function removeRecord(id)
{
    if(id>0)
    {
        $.ajax({
            url: "{{ url('remove-extra-charges-ratecard') }}",
            type: "POST",
            data: {"id":id},
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            beforeSend:function(){
                //$("button").prop('disabled',true);
            },
            success: function (response) {
                if(response.message)
                {
                    $('#extra_charges_success-err').html(response.message);
                    loadExtraChargesData();
                }

            },
            complete:function(){
                //$("button").prop('disabled',false);
            }
        });
    }
}
</script>
