<div class="nav-container">
    <nav id="main-menu-navigation" class="navigation-main">
        <div class="nav-item <?php echo e(request()->routeIs(['admin.dashboard']) ? 'active' : ''); ?>">
            <a href="<?php echo e(route('admin.dashboard')); ?>"><i class="ik ik-bar-chart-2"></i><span>Dashboard</span></a>
        </div>

        <div class="nav-lavel">Manage Employees</div>
        <div class="nav-item has-sub <?php echo e(request()->routeIs('admin.employee.*') ? 'active open' : ''); ?> <?php echo e(request()->routeIs('admin.overtime.*') ? 'active open' : ''); ?> <?php echo e(request()->routeIs('admin.cashadvance.*') ? 'active open' : ''); ?>">
            <a href="javascript:void(0)"><i class="ik users ik-users"></i><span>Employees</span>
                <?php if($counts['employees'] != 0): ?>
                <span title="Total Records" class="badge badge-light text-dark">
                    <?php echo e($counts['employees']); ?>

                </span>
                <?php endif; ?>
            </a>
            <div class="submenu-content">
                <a href="<?php echo e(route('admin.employee.create')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.employee.create') ? 'active' : ''); ?>"><i class="ik ik-user-plus"></i>Add New Employee</a>
                <a href="<?php echo e(route('admin.employee.index')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.employee.index') ? 'active' : ''); ?>"><i class="ik file-text ik-file-text"></i>List Of Employees</a>
                <a href="<?php echo e(route('admin.employee.employeeImport')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.employee.employeeImport') ? 'active' : ''); ?>"><i class="ik file-text ik-file-text"></i>Import Employees</a>
                <a href="<?php echo e(route('admin.overtime.index')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.overtime.*') ? 'active' : ''); ?>"><i class="ik watch ik-watch"></i>Overtime</a>
                <a href="<?php echo e(route('admin.cashadvance.index')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.cashadvance.index') ? 'active' : ''); ?>"><i class="ik at-sign ik-at-sign"></i>Cash Advance</a>
            </div>
        </div>

        <div class="nav-item has-sub <?php echo e(request()->routeIs('admin.attendance.*') ? 'active open' : ''); ?>">
            <a href="javascript:void(0)"><i class="ik ik-check-circle"></i><span>Attendance</span>
            </a>
            <div class="submenu-content">
                <a href="<?php echo e(route('admin.attendance.create')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.attendance.create') ? 'active' : ''); ?>"><i class="ik ik-plus-circle"></i>Add New Attendance</a>
                <a href="<?php echo e(route('admin.attendance.import')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.attendance.import') ? 'active' : ''); ?>"><i class="ik ik-plus-circle"></i>Bulk Insertion Attendance</a>
                <a href="<?php echo e(route('admin.attendance.index')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.attendance.index') ? 'active' : ''); ?>"><i class="ik file-text ik-file-text"></i>List Of Attendance</a>
            </div>
        </div>

        <div class="nav-item <?php echo e(request()->routeIs(['admin.payroll.*']) ? 'active' : ''); ?>">
            <a href="<?php echo e(route('admin.payroll.index')); ?>"><i class="ik ik-dollar-sign"></i><span>Payroll</span></a>
        </div>
        <div class="nav-item <?php echo e(request()->routeIs(['admin.employeepayroll.*']) ? 'active' : ''); ?>">
            <a href="<?php echo e(route('admin.employeepayroll.index')); ?>"><i class="ik ik-dollar-sign"></i><span>Employee Past Payrolls</span></a>
        </div>
        <div class="nav-item <?php echo e(request()->routeIs(['admin.employeefuturepayroll.*']) ? 'active' : ''); ?>">
            <a href="<?php echo e(route('admin.employeefuturepayroll.futurePayrolls')); ?>"><i class="ik ik-dollar-sign"></i><span>Employee Future Payrolls</span></a>
        </div>
        <div class="nav-item <?php echo e(request()->routeIs(['admin.invoice.*']) ? 'active' : ''); ?>">
            <a href="<?php echo e(route('admin.invoice.index')); ?>"><i class="ik ik-dollar-sign"></i><span>Invoice</span></a>
        </div>
        <div class="nav-item <?php echo e(request()->routeIs(['admin.paymentsummary.*']) ? 'active' : ''); ?>">
            <a href="<?php echo e(route('admin.paymentsummary.index')); ?>"><i class="ik ik-dollar-sign"></i><span>Payment Summary</span></a>
        </div>

        <div class="nav-lavel">Manage Site</div>

        <div class="nav-item has-sub <?php echo e(request()->routeIs('admin.position.*') ? 'active open' : ''); ?>">
            <a href="javascript:void(0)"><i class="ik ik-briefcase"></i><span>Positions</span>
                <?php if($counts['positions'] != 0): ?>
                <span title="Total Records" class="badge badge-light text-dark">
                    <?php echo e($counts['positions']); ?>

                </span>
                <?php endif; ?>
            </a>
            <div class="submenu-content">
                <a href="<?php echo e(route('admin.position.create')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.position.create') ? 'active' : ''); ?>"><i class="ik ik-plus-circle"></i>Add New Position</a>
                <a href="<?php echo e(route('admin.position.index')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.position.index') ? 'active' : ''); ?>"><i class="ik file-text ik-file-text"></i>List Of Position</a>
            </div>
        </div>

        <div class="nav-item has-sub <?php echo e(request()->routeIs('admin.states.*') ? 'active open' : ''); ?>">
            <a href="javascript:void(0)"><i class="ik ik-briefcase"></i><span>States</span>
                <?php if($counts['states'] != 0): ?>
                    <span title="Total Records" class="badge badge-light text-dark">
                    <?php echo e($counts['states']); ?>

                </span>
                <?php endif; ?>
            </a>
            <div class="submenu-content">
                <a href="<?php echo e(route('admin.states.create')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.states.create') ? 'active' : ''); ?>"><i class="ik ik-plus-circle"></i>Add New State</a>
                <a href="<?php echo e(route('admin.states.index')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.states.index') ? 'active' : ''); ?>"><i class="ik file-text ik-file-text"></i>List Of States</a>
            </div>
        </div>

        <div class="nav-item has-sub <?php echo e(request()->routeIs('admin.deduction.*') ? 'active open' : ''); ?>">
            <a href="javascript:void(0)"><i class="ik file-minus ik-file-minus"></i><span>Deductions</span>
                <?php if($counts['deductions'] != 0): ?>
                <span title="Total Records" class="badge badge-light text-dark">
                    <?php echo e($counts['deductions']); ?>

                </span>
                <?php endif; ?>
            </a>
            <div class="submenu-content">
                <a href="<?php echo e(route('admin.deduction.create')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.deduction.create') ? 'active' : ''); ?>"><i class="ik ik-plus-circle"></i>Add New Deduction</a>
                <a href="<?php echo e(route('admin.deduction.index')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.deduction.index') ? 'active' : ''); ?>"><i class="ik file-text ik-file-text"></i>List Of Deductions</a>
            </div>
        </div>

        <div class="nav-item has-sub <?php echo e(request()->routeIs('admin.schedule.*') ? 'active open' : ''); ?>">
            <a href="javascript:void(0)"><i class="ik clock ik-clock"></i><span>Schedules</span>
                <?php if($counts['schedules'] != 0): ?>
                <span title="Total Records" class="badge badge-light text-dark">
                    <?php echo e($counts['schedules']); ?>

                </span>
                <?php endif; ?>
            </a>
            <div class="submenu-content">
                <a href="<?php echo e(route('admin.schedule.create')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.schedule.create') ? 'active' : ''); ?>"><i class="ik ik-plus-circle"></i>Add New Schedule</a>
                <a href="<?php echo e(route('admin.schedule.index')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.schedule.index') ? 'active' : ''); ?>"><i class="ik file-text ik-file-text"></i>List Of Schedules</a>
            </div>
        </div>
        <div class="nav-item has-sub <?php echo e(request()->routeIs('admin.tenants.*') ? 'active open' : ''); ?>">
            <a href="javascript:void(0)"><i class="ik clock ik-clock"></i><span>Customers</span>
                <?php if($counts['tenants'] != 0): ?>
                    <span title="Total Records" class="badge badge-light text-dark">
                    <?php echo e($counts['tenants']); ?>

                </span>
                <?php endif; ?>
            </a>
            <div class="submenu-content">
                <a href="<?php echo e(route('admin.tenant.create')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.tenant.create') ? 'active' : ''); ?>"><i class="ik ik-plus-circle"></i>Add New Customer</a>
                <a href="<?php echo e(route('admin.tenant.index')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.tenant.index') ? 'active' : ''); ?>"><i class="ik file-text ik-file-text"></i>List Of Customers</a>
            </div>
        </div>
        <div class="nav-item has-sub <?php echo e(request()->routeIs('admin.services.*') ? 'active open' : ''); ?>">
            <a href="javascript:void(0)"><i class="ik clock ik-clock"></i><span>Services</span>
                <?php if($counts['services'] != 0): ?>
                    <span title="Total Records" class="badge badge-light text-dark">
                    <?php echo e($counts['services']); ?>

                </span>
                <?php endif; ?>
            </a>
            <div class="submenu-content">
                <a href="<?php echo e(route('admin.service.create')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.service.create') ? 'active' : ''); ?>"><i class="ik ik-plus-circle"></i>Add New Service</a>
                <a href="<?php echo e(route('admin.service.index')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.service.index') ? 'active' : ''); ?>"><i class="ik file-text ik-file-text"></i>List Of Service</a>
                <a href="<?php echo e(route('admin.categories.create')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.categories.create') ? 'active' : ''); ?>"><i class="ik ik-plus-circle"></i>Add New Category</a>
                <a href="<?php echo e(route('admin.categories.index')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.categories.index') ? 'active' : ''); ?>"><i class="ik file-text ik-file-text"></i>List Of Category</a>
                <a href="<?php echo e(route('admin.accounts.create')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.accounts.create') ? 'active' : ''); ?>"><i class="ik ik-plus-circle"></i>Add New  Accounts</a>
                <a href="<?php echo e(route('admin.accounts.index')); ?>" class="menu-item <?php echo e(request()->routeIs('admin.accounts.index') ? 'active' : ''); ?>"><i class="ik file-text ik-file-text"></i>List Of Accounts</a>
            </div>
        </div>

        <div class="nav-lavel">Site Settings</div>
        <div class="nav-item <?php echo e(request()->routeIs('admin.profile.*') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('admin.profile.index')); ?>"><i class="ik user ik-user"></i><span>My Profile</span></a>
        </div>
        <div class="nav-item <?php echo e(request()->routeIs('admin.settings.*') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('admin.settings.index')); ?>"><i class="ik user ik-settings"></i><span>Site Settings</span></a>
        </div>
        <div class="nav-item <?php echo e(request()->routeIs('admin.siteaccounts.*') ? 'active' : ''); ?>">
            <a href="<?php echo e(route('admin.siteaccounts.index')); ?>"><i class="ik user ik-settings"></i><span>Manage Accounts</span></a>
        </div>

        <div class="nav-item">
            <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <i class="ik log-out ik-log-out"></i><span>Logout</span>
            </a>
            <form id="logout-form" action="<?php echo e(route('admin.logout')); ?>" method="POST" style="display: none;">
                <?php echo csrf_field(); ?>
            </form>
        </div>

    </nav>
</div>
<?php /**PATH /home/u361785735/domains/caisol.com/public_html/payrollsystem/resources/views/admin/layout/menu.blade.php ENDPATH**/ ?>