<!--data here-->
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade active show" id="live" role="tabpanel" aria-labelledby="pills-timeline-tab">

    <!--Live Banner Data-->
    <div class="card-header">
      <div class="col-md-6 d-block">
        <a href="{{ $add_new }}" class="btn btn-sm btn-dark float-left"><i class="ik plus-square ik-plus-square"></i> Create New Employee</a>
      </div>
      <div class="col-md-6">
        <button type="submit" class="btn btn-primary mb-2 h-33 float-right move-to-delete-all" id="apply" disabled="true" data-href="{{ $moveToTrashAllLink }}">Action</button>
      </div>
    </div>

    <div class="card-body table-responsive">
        <table id="employee_data_table" class="table table-striped">
          <thead>
            <tr>
              <th>PKID</th>
              <th>Type</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Pay Type</th>
              <th>Currency Type</th>
              <th>Phone</th>
              <th>Email</th>
              <th>Position</th>
              <th>Details</th>
              <th>Terminatin Status</th>
              <th>Terminatin Data</th>
              <th>Actions</th>
              <th>

                {{-- <div class="custom-control custom-checkbox pl-1 align-self-center">
                  <label class="custom-control custom-checkbox mb-0" title="Select All" data-toggle="tooltip" data-placement="right">
                    <input type="checkbox" class="custom-control-input" id="dt-live-select-all">
                    <span class="custom-control-label"></span>
                  </label>
                </div> --}}

              </th>
            </tr>
          </thead>
          <tbody>
            @foreach($employees as $k => $employee)

            <tr>
             <td>{{ $employee->id }}</td>
             <td>{{ ($employee->employee_type_form==1)?'W2':(($employee->employee_type_form==2)?'1099':'offshore') }}</td>
             <td>{{ $employee->first_name }}</td>
             <td>{{ $employee->last_name }}</td>
             <td>{{ $employee->pay_type }}</td>
             <td>{{ $employee->currency_type }}</td>
             <td>{{ $employee->phone }}</td>
             <td>{{ $employee->email }}</td>
             <td>{{ $employee->position->title }}</td>
             <td>
               <div class=''>
                <b>Gender :</b> <span>{{$employee->gender}}</span></br>
                <b>Employee Id :</b> <span>{{$employee->employee_id}}</span></br>
                <b>Schedule :</b> <span>{{$employee->schedule->time_in.'-'.$employee->schedule->time_out}}</span></br>
                <b>Address :</b> <span>{{$employee->address}}</span></br>
              </div>
             </td>
             <td>
              @if($employee->is_active == '1')
                <span class='success-dot' title='Published' title='Active Employee'></span>
              @else
                <i class='failure-dot' title='Terminated Employee'></i>
              @endif
             </td>
             <td>
              @if(!empty($employee->terminatin_date))
                <span >{{ !empty($employee->terminatin_date)?(\Carbon\Carbon::parse($employee->terminatin_date)->format('m/d/Y h:i A')):"" }}</span>
              @else
                     <span>N/A</span>
              @endif
             </td>
             <td>
               <div class='table-actions'>
                   @if(!empty($employee->employee_id))
                <a data-href="{{route('admin.employee.show',['employee'=>$employee->employee_id])}}" class='show-employee cursure-pointer'>
                  <i class='ik ik-eye text-primary'></i>
                </a>
                <a href="{{route("admin.employee.edit",['employee'=>$employee->employee_id])}}">
                  <i class='ik ik-edit-2 text-dark'></i>
                </a>
                <a data-href="{{route("admin.employee.destroy",['employee'=>$employee->id])}}" class='delete cursure-pointer'><i class='ik ik-trash-2 text-danger'></i></a>
                   @endif
              </div>
             </td>
             <td>
               <div class="custom-control custom-checkbox pl-1 align-self-center">
                  <label class="custom-control custom-checkbox mb-0">
                    <input type="checkbox" class="custom-control-input sub_chk" data-id="{{$employee->id}}">
                    <span class="custom-control-label"></span>
                  </label>
                </div>
             </td>
            </tr>
            @endforeach
          </tbody>
        </table>
    </div>
    <!--End Live Banner Data-->

  </div>
</div>
<!--End data here-->

<script type="text/javascript">
  $(document).ready(function(){
    $("#employee_data_table").DataTable();
  });
</script>
