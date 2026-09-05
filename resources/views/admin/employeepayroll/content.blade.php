<!--data here-->
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade active show" id="live" role="tabpanel" aria-labelledby="pills-timeline-tab">

    <!--Live Overtime Data-->
      <!--Live Banner Data-->
      <div class="card-header">

          <div class="col-md-6">
              <button type="submit" class="btn btn-primary mb-2 h-33 float-right move-to-delete-all" id="apply" disabled="true" data-href="{{ $moveToTrashAllLink }}">Action</button>
          </div>
      </div>

    <div class="card-body table-responsive">
        <table id="pastpayroll_data_table" class="table table-striped">
          <thead>
            <tr>
              <th>Employee Details</th>
              <th>Unique ID</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Net Pay</th>
              <th>Run time Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
            <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>Total Sum of Past Payrolls</td>
                <td>{{$total_past_parolls}}</td>
                <td></td>
                <td></td>
            </tr>
            </tfoot>
        </table>
    </div>
    <!--End Live Overtime Data-->

  </div>
</div>
<!--End data here-->


<script type="text/javascript">
// get data from serve ajax

function printForm(formId,btn){

  $.ajax({
    url: $(formId).data('action'),
    type: 'POST',
    data : new FormData($(formId)[0]),
    processData: false,
    contentType: false,
    xhrFields: {
        'responseType': 'blob'
    },
    beforeSend:function() {
      btn.prop('disabled',true);
    },
    complete : function(data) {
        if(formId=='#payrollRun')
        {
            alert("Payroll run successfull")
        }
        btn.prop('disabled',false);

    },
    success: function (blob, status, xhr) {
        let filename = '';

        const disposition = xhr.getResponseHeader('Content-Disposition');

        if (disposition && disposition.indexOf('attachment') !== -1) {
            const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
            const matches = filenameRegex.exec(disposition);

            if (matches != null && matches[1]) {
                filename = matches[1].replace(/['"]/g, '');
            }
        }

        let a = document.createElement('a');
        a.href = window.URL.createObjectURL(blob, status, xhr);
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(a.href);
    }
  });
}

$(document).ready(function() {


  var table = $("table#pastpayroll_data_table").DataTable({
    "processing": true,
    "serverSide": true,
    "pagingType":"full_numbers",
    "pageLength":25,
    "autoWidth": false,
    "lengthMenu": [ [10, 10, 25, 50, 100, 10,-1], [10, 10, 25, 50,100,10, "All"] ],
    "ajax": {
      "url": "{{ $getDataTable }}",
      "type": "POST",
      "data":function( d ) {
        d.date = $("#date").val();
      }
    },
    "columnDefs": [
    {
      'targets': [6],
      'searchable':false,
      'orderable':false,
      "className": "text-left"
    }
    ],
    "columns":[
    {"data":"employee"},
    {"data":"unique_id"},
    {"data":"start_date"},
    {"data":"end_date"},
    {"data":"net_pay"},
    {"data":"run_time_date"},
    {"data":"action"},
    ],
  });

  var inputDate = $("#date").val();
  $("#payroll_date_input,#payslip_date_input").val(inputDate);

  datePickerPlug.on('apply.daterangepicker', function(ev, picker) {
      var date = picker.startDate.format("MMMM DD, YYYY")+" - "+picker.endDate.format("MMMM DD, YYYY");
      $("#payroll_date_input,#payslip_date_input").val(date);
      table.ajax.reload();
  });

  $("#pdfBtnPrintpayslilp,#pdfBtnPrintpayroll").on("click",function(e){
    var formId = ($(this).attr("id") == "pdfBtnPrintpayslilp") ? "#payslipForm" : "#payrollForm";
    printForm(formId,$(this));
  });
  $("#pdfBtnPrintpayrun").on("click",function(e){
    var formId = "#payrollRun"
    printForm(formId,$(this));
  });
});

</script>
