<!--Live Position Data-->
<div class="card-header">
  <div class="col-md-6 d-block">
    <a href="{{ $add_new }}" class="btn btn-sm btn-dark float-left"><i class="ik plus-square ik-plus-square"></i> Create Account</a>
  </div>
  <div class="col-md-6">
    <button type="submit" class="btn btn-primary mb-2 h-33 float-right move-to-delete-all" id="apply" disabled="true" data-href="{{ $moveToTrashAllLink }}">Action</button>
  </div>
</div>

<div class="card-body table-responsive p-0">
  <table id="category_data_table" class="table mb-0 table-hover">
    <thead>
      <tr>
        <th width="20">Account Number</th>
        <th width="20">Account Title</th>
        <th width="20">Bank Name</th>
        <th width="20">Is Main Account</th>

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
      @foreach($accounts as $acc)
      <tr>
        <td><span><b>{{ $acc->account_number }}</b></span></td>
        <td><span><b>{{ $acc->account_title }}</b></span></td>
        <td><span><b>{{ $acc->bank_name }}</b></span></td>
        <td><span><b>{{ (!empty($acc->is_main_account) && $acc->is_main_account==1)?'Yes':'No' }}</b></span></td>
        <td>
            <div class="btn-group btn-sm" role="group" aria-label="Basic example">
              <a href="{{ route('admin.siteaccounts.edit',['siteaccount'=>$acc]) }}" type="button" class="btn btn-sm btn-outline-primary">
                <i class="ik edit-2 ik-edit-2"></i>
              </a>

                <a href="{{ url('banktransaction/listing/'.$acc->id) }}" type="button" class="btn btn-sm btn-outline-primary viewFile">
                    <i class='ik ik-eye text-primary'></i>
                </a>
              <a data-href="{{ route('admin.siteaccounts.destroy',['siteaccount'=>$acc]) }}" type="button" class="btn btn-sm btn-outline-danger delete">
                <i class="ik trash-2 ik-trash-2"></i>
              </a>
            </div>
        </td>
        <td>
          <div class="custom-control custom-checkbox pl-1 align-self-center">
            <label class="custom-control custom-checkbox mb-0">
              <input type="checkbox" class="custom-control-input sub_chk" data-id="{{$acc->id}}">
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
