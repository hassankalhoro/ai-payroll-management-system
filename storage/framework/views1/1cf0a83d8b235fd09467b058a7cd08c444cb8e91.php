<!--Live Position Data-->
<div class="card-header">
  <div class="col-md-6 d-block">
    <a href="<?php echo e($add_new); ?>" class="btn btn-sm btn-dark float-left"><i class="ik plus-square ik-plus-square"></i> Create Category</a>
  </div>
  <div class="col-md-6">
    <button type="submit" class="btn btn-primary mb-2 h-33 float-right move-to-delete-all" id="apply" disabled="true" data-href="<?php echo e($moveToTrashAllLink); ?>">Action</button>
  </div>
</div>

<div class="card-body table-responsive p-0">
  <table id="category_data_table" class="table mb-0 table-hover">
    <thead>
      <tr>
        <th width="20">Title</th>
        <th width="30">Parent Category</th>
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
      <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>
        <td><span><b><?php echo e($cat->title); ?></b></span></td>
        <td><code class="pc"><?php echo e($cat->parent); ?></code></td>

        <td>
            <div class="btn-group btn-sm" role="group" aria-label="Basic example">
              <a href="<?php echo e(route('admin.categories.edit',['category'=>$cat])); ?>" type="button" class="btn btn-sm btn-outline-primary">
                <i class="ik edit-2 ik-edit-2"></i>
              </a>
              <a data-href="<?php echo e(route('admin.categories.destroy',['category'=>$cat])); ?>" type="button" class="btn btn-sm btn-outline-danger delete">
                <i class="ik trash-2 ik-trash-2"></i>
              </a>
            </div>
        </td>
        <td>
          <div class="custom-control custom-checkbox pl-1 align-self-center">
            <label class="custom-control custom-checkbox mb-0">
              <input type="checkbox" class="custom-control-input sub_chk" data-id="<?php echo e($cat->id); ?>">
              <span class="custom-control-label"></span>
            </label>
          </div>
        </td>
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
  </table>
</div>
<!--EndLive Position Data-->
<?php /**PATH /home/u361785735/domains/caisol.com/public_html/payrollsystem/resources/views/admin/categories/content.blade.php ENDPATH**/ ?>