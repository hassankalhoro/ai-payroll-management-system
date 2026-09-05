<!--Live Schedule Data-->
<div class="card-header">
  <div class="col-md-6 d-block">
    <a href="{{ $add_new }}" class="btn btn-sm btn-dark float-left"><i class="ik plus-square ik-plus-square"></i> Create Customer</a>
  </div>
  <div class="col-md-6">
    <button type="submit" class="btn btn-primary mb-2 h-33 float-right move-to-delete-all" id="apply" disabled="true" data-href="{{ $moveToTrashAllLink }}">Action</button>
  </div>
</div>

<div class="card-body table-responsive p-0">
  <table id="state_data_table" class="table mb-0 table-hover">
    <thead>
      <tr>
        <th width="2" class="text-center">
          <div class="custom-control custom-checkbox pl-1 align-self-center">
            <label class="custom-control custom-checkbox mb-0" title="Select All" data-toggle="tooltip" data-placement="right">
              <input type="checkbox" class="custom-control-input" id="master">
              <span class="custom-control-label"></span>
            </label>
          </div>
        </th>
        <th width="3" class="text-center">No.</th>
        <th width="45" class="text-center">Title</th>
        <th width="45" class="text-center">Email</th>
        <th width="5" class="text-center">Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($tenants as $tenant)
      <tr>
        <td class="text-center">
          <div class="custom-control custom-checkbox pl-1 align-self-center">
            <label class="custom-control custom-checkbox mb-0">
              <input type="checkbox" class="custom-control-input sub_chk" data-id="{{$tenant->id}}">
              <span class="custom-control-label"></span>
            </label>
          </div>
        </td>
        <td class="text-center">
          {{ ($loop->index + 1) }}
        </td>
        <td>
          <div class="text-center">
            <span><b>{{ $tenant->title }}</b></span>
          </div>
        </td>
        <td class="text-center">
          <span><b>{{ $tenant->email }}</b></span>
        </td>
        <td class="text-center">
            <div class="btn-group btn-sm" role="group" aria-label="Basic example">
              <a href="{{ route('admin.tenant.edit',['tenant'=>$tenant]) }}" type="button" class="btn btn-sm btn-outline-primary">
                <i class="ik edit-2 ik-edit-2"></i>
              </a>
              <a data-href="{{ route('admin.tenant.destroy',['tenant'=>$tenant]) }}" type="button" class="btn btn-sm btn-outline-danger delete">
                <i class="ik trash-2 ik-trash-2"></i>
              </a>
              <a href="{{ url('invoice/listing/'.$tenant->id) }}" type="button" class="btn btn-sm btn-outline-primary">
                <i class="ik ik ik-eye text-primary"></i>
              </a>
            </div>
        </td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan="6" class="text-right">
          <h6>Total Tenants : <span class="text-danger">{{ $count }}</span> </h6>
        </td>
      </tr>
    </tfoot>
  </table>
</div>
<!--EndLive Schedule Data-->
