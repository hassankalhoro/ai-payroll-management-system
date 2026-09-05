<!--data here-->
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade active show" id="live" role="tabpanel" aria-labelledby="pills-timeline-tab">

    <!--Live Overtime Data-->
    <div class="card-header">
      <div class="col-md-2 d-block">
        <button class="btn btn-sm btn-dark float-left" id="pdfBtnPrintpayroll"><i class="ik ik-printer"></i> FUTURE PAYROLL</button>
      </div>
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
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary mb-2 h-33 float-right" id="pdfBtnPrintpayslilp"><i class="ik ik-printer"></i> PAYSLIP</button>
      </div>

    </div>
      <div class="card-header">

          <div class="col-md-3">
              <div class="form-group">
                  <label for="currency_type">Currency Type</label>
                  <select class="form-control select2" name="currency_type" id="currency_type">
                      <option value="">Select Currency Type</option>
                      <option value="PKR">PKR</option>
                      <option value="USD" >USD</option>
                      <option value="INR">INR</option>
                  </select>
              </div>
          </div>
          <div class="col-md-3">
              <div class="form-group">
                  <label for="employee_type">Employee Type</label>
                  <select class="form-control select2" name="employee_type" id="employee_type">
                      <option value="">Select Employee Type</option>
                      <option value="1">W2</option>
                      <option value="2" >1099</option>
                      <option value="3">Off Shore</option>
                  </select>
              </div>
          </div>
          <div class="col-md-3">
              <div class="form-group">
                  <label for="pay_type">Pay Type</label>
                  <select class="form-control select2" name="pay_type" id="pay_type">
                      <option value="">Select Pay Type</option>
                      <option value="salary">Salaried</option>
                      <option value="hourly" >Hourly</option>
                  </select>
              </div>
          </div>

      </div>

    <div class="card-body table-responsive">
        <table id="payroll_data_table" class="table table-striped">
          <thead>
            <tr>
              <th>Employee Details</th>
              <th>Employee Type</th>
              <th>Employee Salary Type</th>
              <th>Total Hours</th>
              <th>Gross</th>
              <th>Deductions</th>
              <th>Cash Advance</th>
              <th>Overtime</th>
              <th>Net Pay</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>

          </tbody>
            <tfoot>
            <tr>
                <th>Employee Details</th>
                <th></th>
                <th></th>
                <th>Total Hours</th>
                <th>Gross</th>
                <th>Deductions</th>
                <th>Overtime</th>
                <th>Net Pay</th>
                <th></th>
            </tr>
            </tfoot>
            <tfoot id="Bfrtip">
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
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

<div class="divHide">
  <form data-action="{{ $payslip_url }}" method="post" id="payslipForm">
    @method("POST")
    @csrf
    <input type="text" name="date" id="payslip_date_input">
  </form>
  <form data-action="{{ $payroll_url }}" method="post" id="payrollForm">
    @method("POST")
    @csrf
    <input type="text" name="date" id="payroll_date_input">
  </form>

</div>
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
function datatablefn()
{
    var currency_type = $('#currency_type').val();
    var employee_type = $('#employee_type').val();
    var pay_type = $('#pay_type').val();
    var table = $("table#payroll_data_table").DataTable({
        "processing": true,
        "serverSide": true,
        "pagingType":"full_numbers",
        "pageLength":25,
        "autoWidth": false,
        "dom": 'Bfrtip',
        "bDestroy": true,
        drawCallback: function (settings) {
            $('<span id="active_rows"></span>').html(' Employees').appendTo($('.dataTables_info'));
        },
        "footerCallback": function (tfoot, data, start, end, display) {
            var api = this.api();
            var total_hours = api.column(3).data().reduce(function (a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0)
            $(api.column(0).footer()).html("Total: ");
            $(api.column(3).footer()).html(total_hours.toFixed(2));

            var total_gross = api.column(4).data().reduce(function (a, b) {
                if ( typeof a === 'string' ) {
                    a = a.replace(/[^\d.-]/g, '') * 1;
                }
                if ( typeof b === 'string' ) {
                    b = b.replace(/[^\d.-]/g, '') * 1;
                }
                return parseFloat(a) + parseFloat(b);
            }, 0)
            $(api.column(4).footer()).html(total_gross.toFixed(2));

            var total_deductions = api.column(5).data().reduce(function (a, b) {
                if ( typeof a === 'string' ) {
                    a = a.replace(/[^\d.-]/g, '') * 1;
                }
                if ( typeof b === 'string' ) {
                    b = b.replace(/[^\d.-]/g, '') * 1;
                }
                return parseFloat(a) + parseFloat(b);
            }, 0)
            $(api.column(5).footer()).html(total_deductions.toFixed(2));

            var total_advance = api.column(6).data().reduce(function (a, b) {
                if ( typeof a === 'string' ) {
                    a = a.replace(/[^\d.-]/g, '') * 1;
                }
                if ( typeof b === 'string' ) {
                    b = b.replace(/[^\d.-]/g, '') * 1;
                }
                return parseFloat(a) + parseFloat(b);
            }, 0)
            $(api.column(6).footer()).html(total_advance.toFixed(2));

            var total_overtime = api.column(7).data().reduce(function (a, b) {
                if ( typeof a === 'string' ) {
                    a = a.replace(/[^\d.-]/g, '') * 1;
                }
                if ( typeof b === 'string' ) {
                    b = b.replace(/[^\d.-]/g, '') * 1;
                }
                return parseFloat(a) + parseFloat(b);
            }, 0)
            $(api.column(7).footer()).html(total_overtime.toFixed(2));

// Remove the formatting to get integer data for summation
            var intVal = function(i) {
                return typeof i === 'string' ?
                    i.replace(/[\$a-zA-Z, ]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            var currency_label="";
            var total_netpay = api.column(8).data().reduce(function (a, b) {



                if ( typeof a === 'string') {
                    if(a.indexOf("PKR") != -1 && currency_label.indexOf("PKR")  < 0){
                        if(currency_label!="")
                        {
                            currency_label+= ",PKR ";
                        }
                        else
                        {
                            currency_label+= " PKR ";
                        }
                    }
                    if(a.indexOf("USD") != -1 && currency_label.indexOf("USD")  < 0){
                        if(currency_label!="")
                        {
                            currency_label+= ",USD ";
                        }
                        else
                        {
                            currency_label+= " USD ";
                        }
                    }
                    if(a.indexOf("INR") != -1 && currency_label.indexOf("INR")  < 0){
                        if(currency_label!="")
                        {
                            currency_label+= ",INR ";
                        }
                        else
                        {
                            currency_label+= " INR ";
                        }
                    }
                    a=a.replace("<b>", "")
                    a=a.replace("</b>", "")
                    a=a.replace("PKR.", "")
                    a=a.replace("INR.", "")
                    a=a.replace("USD.", "")
                    a=a.replace(",", "")
                }
                if ( typeof b === 'string' ) {
                    if(b.indexOf("PKR") != -1 && currency_label.indexOf("PKR")  < 0){
                        if(currency_label!="")
                        {
                            currency_label+= ",PKR ";
                        }
                        else
                        {
                            currency_label+= " PKR ";
                        }

                    }
                    if(b.indexOf("USD") != -1 && currency_label.indexOf("USD")  < 0){
                        if(currency_label!="")
                        {
                            currency_label+= ",USD ";
                        }
                        else
                        {
                            currency_label+= " USD ";
                        }
                    }
                    if(b.indexOf("INR") != -1 && currency_label.indexOf("INR")  < 0){

                        if(currency_label!="")
                        {
                            currency_label+= ",INR ";
                        }
                        else
                        {
                            currency_label+= " INR ";
                        }
                    }
                    b=b.replace("<b>", "")
                    b=b.replace("</b>", "")
                    b=b.replace("PKR.", "")
                    b=b.replace("INR.", "")
                    b=b.replace("USD.", "")
                    b=b.replace(",", "")
                }


                return intVal(a) + intVal(b);
            }, 0)
            $(api.column(8).footer()).html(currency_label+total_netpay.toFixed(2));


        },
        /* "initComplete": function () {
             count = 0;

             this.api().columns().every( function () {
                 var title = this.header();

                 //replace spaces with dashes
                 title = $(title).html().replace(/[\W]/g, '-');
                 var column = this;
                 var select = $('<select id="' + title + '" class="select2" ></select>')
                     .appendTo( $(column.header()) )
                     .on( 'change', function () {
                         //Get the "text" property from each selected data
                         //regex escape the value and store in array

                         var data = $.map( $(this).select2('data'), function( value, key ) {
                             return value.text ? '^' + $.fn.dataTable.util.escapeRegex(value.text) + '$' : null;
                         });
                         console.log(data);
                         //if no data selected use ""
                         if (data.length === 0) {
                             data = [""];
                         }

                         //join array into string with regex or (|)
                         var val = data.join('|');
                         //search for the option(s) selected
                         column
                             .search( val ? val : '', true, false )
                             .draw();



                     } );

                 if (column.index() === 2) {
                     offices = [];

                     officeSelectOrder = ['London', 'Edinburgh', 'Tokyo', 'San Francisco', 'New York'];

                     // Get unique data from the office column
                     column.data().unique().each( function ( d, j ) {
                         offices.push(d);
                     } );

                     // Loop the ofice order and if in data add to select list
                     $.each(officeSelectOrder, function( index, value ) {
                         if (offices.includes(value)) {
                             select.append( '<option value="'+value+'">'+value+'</option>' );
                         }
                     } );

                 } else {
                     column.data().unique().sort().each( function ( d, j ) {
                         select.append( '<option value="'+d+'">'+d+'</option>' );
                     } );
                 }

                 var currSearch = column.search();
                 if ( currSearch ) {
                     select.val( currSearch.substring(1, currSearch.length-1) );
                 }



                 //use column title as selector and placeholder
                 $('#' + title).select2({
                     multiple: true,
                     closeOnSelect: false,
                     placeholder: "Select a " + title
                 });

                 //initially clear select otherwise first option is selected
                 $('.select2').val(null).trigger('change');
             } );
         },*/
        "lengthMenu": [ [10, 25, 50, 100,-1], [10, 25, 50,100, "All"] ],
        "ajax": {
            "url": "{{ $getDataTable }}"+"?currency_type="+currency_type+"&employee_type="+employee_type+"&pay_type="+pay_type+"&futuredata=1",
            "type": "POST",
            "data":function( d ) {
                d.date = $("#date").val();
            }
        },
        "columnDefs": [
            {
                'targets': [7],
                'searchable':false,
                'orderable':false,
                "className": "text-left"
            }
        ],
        "columns":[
            {"data":"employee"},
            {"data":"employee_type"},
            {"data":"employee_salary_type"},
            {"data":"hours"},
            {"data":"gross"},
            {"data":"deduction"},
            {"data":"cash_advance"},
            {"data":"overtime"},
            {"data":"net_pay"},
            {"data":"action"},
        ],
    });

}
$(document).ready(function() {

  var crntDate = moment().format('MMMM DD, YYYY');
  var lastDate = moment().subtract(30, 'days').format('MMMM DD, YYYY');
  var datePickerPlug = $('#date').daterangepicker({
    "startDate": lastDate,
    "endDate": crntDate,
    locale: {format: 'MMMM DD, YYYY'},
  });

    $('#currency_type').change(function(){

        datatablefn();
    });
    $('#employee_type').change(function(){

        datatablefn();
    });
    $('#pay_type').change(function(){

        datatablefn();
    });
    datatablefn();
  var inputDate = $("#date").val();
  $("#payroll_date_input,#payslip_date_input").val(inputDate);

  datePickerPlug.on('apply.daterangepicker', function(ev, picker) {
      var date = picker.startDate.format("MMMM DD, YYYY")+" - "+picker.endDate.format("MMMM DD, YYYY");
      $("#payroll_date_input,#payslip_date_input").val(date);
      datatablefn()
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



/*
$(document).ready( function () {

    var table = $('#example').DataTable({
        scrollY: 200,
        initComplete: function () {
            count = 0;
            this.api().columns().every( function () {
                var title = this.header();
                //replace spaces with dashes
                title = $(title).html().replace(/[\W]/g, '-');
                var column = this;
                var select = $('<select id="' + title + '" class="select2" ></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        //Get the "text" property from each selected data
                        //regex escape the value and store in array
                        var data = $.map( $(this).select2('data'), function( value, key ) {
                            return value.text ? '^' + $.fn.dataTable.util.escapeRegex(value.text) + '$' : null;
                        });

                        //if no data selected use ""
                        if (data.length === 0) {
                            data = [""];
                        }

                        //join array into string with regex or (|)
                        var val = data.join('|');

                        //search for the option(s) selected
                        column
                            .search( val ? val : '', true, false )
                            .draw();



                    } );

                if (column.index() === 2) {
                    offices = [];

                    officeSelectOrder = ['London', 'Edinburgh', 'Tokyo', 'San Francisco', 'New York'];

                    // Get unique data from the office column
                    column.data().unique().each( function ( d, j ) {
                        offices.push(d);
                    } );

                    // Loop the ofice order and if in data add to select list
                    $.each(officeSelectOrder, function( index, value ) {
                        if (offices.includes(value)) {
                            select.append( '<option value="'+value+'">'+value+'</option>' );
                        }
                    } );

                } else {
                    column.data().unique().sort().each( function ( d, j ) {
                        select.append( '<option value="'+d+'">'+d+'</option>' );
                    } );
                }

                var currSearch = column.search();
                if ( currSearch ) {
                    select.val( currSearch.substring(1, currSearch.length-1) );
                }



                //use column title as selector and placeholder
                $('#' + title).select2({
                    multiple: true,
                    closeOnSelect: false,
                    placeholder: "Select a " + title
                });

                //initially clear select otherwise first option is selected
                $('.select2').val(null).trigger('change');
            } );
        }
    });
} );
*/


</script>
