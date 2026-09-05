<!--Live Position Data-->
<div class="card-header">
  <div class="col-md-6 d-block">
    <a href="{{ $add_new }}" class="btn btn-sm btn-dark float-left"><i class="ik plus-square ik-plus-square"></i> Create Payment Summary</a>
  </div>
  <div class="col-md-6">
    <button type="submit" class="btn btn-primary mb-2 h-33 float-right move-to-delete-all" id="apply" disabled="true" data-href="{{ $moveToTrashAllLink }}">Action</button>
  </div>
</div>

<div class="card-body table-responsive p-0">
  <table id="state_data_table" class="table mb-0 table-hover">
    <thead>
      <tr>
        <th width="20">ID</th>
        <th width="20">As of Date</th>
        <th width="30">Total Receivables</th>
        <th width="40">Total Payables</th>
        <th width="40">Created At</th>
        <th width="7">Actions</th>
        <th width="3">
          <div class="custom-control custom-checkbox pl-1 align-self-center">
            <label class="custom-control custom-checkbox mb-0" title="Select All" data-toggle="tooltip" data-placement="right">
              <input type="checkbox" class="custom-control-input" id="master">
              <span class="custom-control-label"></span>
            </label>
          </div>
        </th>
      </tr>
    </thead>
    <tbody>
      @foreach($paymentSummary as $summary)
      <tr>
        <td><span><b>{{ $summary->id }}</b></span></td>
        <td><span><b>{{ $summary->as_of_date }}</b></span></td>
        <td>{{ $summary->total_receivables }}</td>
        <td>
          {{ $summary->total_payables }}
        </td>
          <td>
          {{ $summary->created_at }}
        </td>
        <td>
            <div class="btn-group btn-sm" role="group" aria-label="Basic example">
              <a href="{{ route('admin.paymentsummary.edit',['paymentsummary'=>$summary]) }}" type="button" class="btn btn-sm btn-outline-primary">
                <i class="ik edit-2 ik-edit-2"></i>
              </a>
            <a data-href="{{ route('admin.paymentsummary.show',['paymentsummary'=>$summary->id]) }}" type="button" class="btn btn-sm btn-outline-primary viewFile">
                <i class='ik ik-eye text-primary'></i>
            </a>
            <a onclick = "if (! confirm('Are you sure to duplicate this row?')) { return false; }" href="{{ route('admin.paymentsummary.duplicate',['id'=>$summary->id]) }}" type="button" class="btn btn-sm btn-outline-primary">
                <i class='ik ik-database text-primary'></i>
            </a>
              <a data-href="{{ route('admin.paymentsummary.destroy',['paymentsummary'=>$summary->id]) }}" type="button" class="btn btn-sm btn-outline-danger delete">
                <i class="ik trash-2 ik-trash-2"></i>
              </a>
            </div>
        </td>
        <td>
          <div class="custom-control custom-checkbox pl-1 align-self-center">
            <label class="custom-control custom-checkbox mb-0">
              <input type="checkbox" class="custom-control-input sub_chk" data-id="{{$summary->id}}">
              <span class="custom-control-label"></span>
            </label>
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
<!--EndLive Position Data-->
