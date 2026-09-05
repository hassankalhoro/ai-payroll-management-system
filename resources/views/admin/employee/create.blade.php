@extends('admin.layout.app')

@section('title') Create Employee @endsection

@section('css')

<style type="text/css">
    .overflow-visible{
        overflow: visible !important;
    }
    .modal-sm{
      width: auto;
      max-width: 356px !important;
    }
    .select2-container--default {
      display: block;
      width: auto !important;
    }



    /*Background color*/
    #grad1 {
        background-color: : #9C27B0;
        background-image: linear-gradient(120deg, #FF4081, #81D4FA);
    }

    /*form styles*/
    .msform {
        text-align: center;
        position: relative;
        margin-top: 20px;
    }

    .msform fieldset .form-card {
        background: white;
        border: 0 none;
        border-radius: 0px;
        box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
        padding: 20px 40px 30px 40px;
        box-sizing: border-box;
        width: 94%;
        margin: 0 3% 20px 3%;

        /*stacking fieldsets above each other*/
        position: relative;
    }

    .msform fieldset {
        background: white;
        border: 0 none;
        border-radius: 0.5rem;
        box-sizing: border-box;
        width: 100%;
        margin: 0;
        padding-bottom: 20px;

        /*stacking fieldsets above each other*/
        position: relative;
    }

    /*Hide all except first fieldset*/
    .msform fieldset:not(:first-of-type) {
        display: none;
    }

    .msform fieldset .form-card {
        text-align: left;
    }



    .msform input:focus, .msform textarea:focus {
        -moz-box-shadow: none !important;
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
        border: none;
        font-weight: bold;
        border-bottom: 2px solid skyblue;
        outline-width: 0;
    }

    /*Blue Buttons*/
    .msform .action-button {
        width: 100px;
        background: skyblue;
        font-weight: bold;
        color: white;
        border: 0 none;
        border-radius: 0px;
        cursor: pointer;
        padding: 10px 5px;
        margin: 10px 5px;
    }

    .msform .action-button:hover, .msform .action-button:focus {
        box-shadow: 0 0 0 2px white, 0 0 0 3px skyblue;
    }

    /*Previous Buttons*/
    .msform .action-button-previous {
        width: 100px;
        background: #616161;
        font-weight: bold;
        color: white;
        border: 0 none;
        border-radius: 0px;
        cursor: pointer;
        padding: 10px 5px;
        margin: 10px 5px;
    }

    .msform .action-button-previous:hover, .msform .action-button-previous:focus {
        box-shadow: 0 0 0 2px white, 0 0 0 3px #616161;
    }

    /*Dropdown List Exp Date*/
    select.list-dt {
        border: none;
        outline: 0;
        border-bottom: 1px solid #ccc;
        padding: 2px 5px 3px 5px;
        margin: 2px;
    }

    select.list-dt:focus {
        border-bottom: 2px solid skyblue;
    }

    /*The background card*/
    .card {
        z-index: 0;
        border: none;
        border-radius: 0.5rem;
        position: relative;
    }

    /*FieldSet headings*/
    .fs-title {
        font-size: 25px;
        color: #2C3E50;
        margin-bottom: 10px;
        font-weight: bold;
        text-align: left;
    }

    /*progressbar*/
    #progressbar {
        margin-bottom: 30px;
        overflow: hidden;
        color: lightgrey;
    }

    #progressbar .active {
        color: #000000;
    }

    #progressbar li {
        list-style-type: none;
        font-size: 12px;
        width: 25%;
        float: left;
        position: relative;
    }

    /*Icons in the ProgressBar*/
    #progressbar #employee_info:before {
        font-family: FontAwesome;
        content: "\f023";
    }

    #progressbar #contact_info:before {
        font-family: FontAwesome;
        content: "\f007";
    }

    #progressbar #tax_info:before {
        font-family: FontAwesome;
        content: "\f09d";
    }
    #progressbar #contractor_info:before {
        font-family: FontAwesome;
        content: "\f09d";
    }
    #progressbar #direct_deposit:before {
        font-family: FontAwesome;
        content: "\f09d";
    }
    #progressbar #employment_info:before {
        font-family: FontAwesome;
        content: "\f09d";
    }
    #progressbar #payroll_info:before {
        font-family: FontAwesome;
        content: "\f09d";
    }
    #progressbar #earnings_and_deductions:before {
        font-family: FontAwesome;
        content: "\f09d";
    }
    #progressbar #paid_timeoff:before {
        font-family: FontAwesome;
        content: "\f09d";
    }
    #progressbar #wrap_it_up:before {
        font-family: FontAwesome;
        content: "\f09d";
    }

    #progressbar #confirm:before {
        font-family: FontAwesome;
        content: "\f00c";
    }

    /*ProgressBar before any progress*/
    #progressbar li:before {
        width: 50px;
        height: 50px;
        line-height: 45px;
        display: block;
        font-size: 18px;
        color: #ffffff;
        background: lightgray;
        border-radius: 50%;
        margin: 0 auto 10px auto;
        padding: 2px;
    }

    /*ProgressBar connectors*/
    #progressbar li:after {
        content: '';
        width: 100%;
        height: 2px;
        background: lightgray;
        position: absolute;
        left: 0;
        top: 25px;
        z-index: -1;
    }

    /*Color number of the step and the connector before it*/
    #progressbar li.active:before, #progressbar li.active:after {
        background: skyblue;
    }

    /*Imaged Radio Buttons*/
    .radio-group {
        position: relative;
        margin-bottom: 25px;
    }

    .radio {
        display:inline-block;
        width: 204;
        height: 104;
        border-radius: 0;
        background: lightblue;
        box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
        box-sizing: border-box;
        cursor:pointer;
        margin: 8px 2px;
    }

    .radio:hover {
        box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.3);
    }

    .radio.selected {
        box-shadow: 1px 1px 2px 2px rgba(0, 0, 0, 0.1);
        background: #6666d9;
    }

    /*Fit image in bootstrap div*/
    .fit-image{
        width: 100%;
        object-fit: cover;
    }
</style>
@endsection

@section('content')

<div class="page-header">
  <div class="row align-items-end">
     <div class="col-lg-8">
        <div class="page-header-title">
           <i class="ik ik-users bg-blue"></i>
           <div class="d-inline">
              <h5>Employees</h5>
              <span>Create Employee, Please fill all field correctly.</span>
          </div>
      </div>
  </div>
  <div class="col-lg-4">
    <nav class="breadcrumb-container" aria-label="breadcrumb">
       <ol class="breadcrumb">
          <li class="breadcrumb-item">
             <a href="{{ route('admin.dashboard') }}"><i class="ik ik-home"></i></a>
         </li>
         <li class="breadcrumb-item">
             <a href="{{ route('admin.employee.index') }}">Employees</a>
         </li>
         <li class="breadcrumb-item active" aria-current="page">Create</li>
     </ol>
 </nav>
</div>
</div>
</div>
<!-- id="grad1" MultiStep Form -->

<div  class="row">

    <div class="col-md-8 col-sm-12 col-xl-8 offset-md-2 offset-xl-2">

        <div id="peopleDiv" class="card px-0 pt-4 pb-0 mt-3 mb-3">
            <div style="text-align:center;">
                <h2><strong>People</strong></h2>
            </div>
            <div class="row">
                <div class="col-md-12 mx-0">
                    <div class="form-card">
                        <div class="radio-group" style="text-align: center;" >
                            <div id="add_an_employeew2" style="width: 40%;height: 80px;text-align: center;" class='radio card px-0 pt-4  mb-3' data-value="w2"><h5><i class="ik ik-user-plus"></i>Add a new employee (W-2)</h5></div>
                            <div id="add_an_employeew1099" style="width: 40%;height: 80px;text-align: center;" class='radio  card px-0 pt-4  mb-3' data-value="1099"><h5><i class="ik users ik-users"></i>Add a new contractor (1099)</h5></div>
                            <div id="add_an_employeewoffshore" style="width: 40%;height: 80px;text-align: center;" class='radio  card px-0 pt-4  mb-3' data-value="offshore"><h5><i class="ik users ik-users"></i>Add a new Employee Offshore</h5></div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="display: none;" id="ownPersonalDiv" class="card px-0 pt-4 pb-0 mt-3 mb-3">
            <div style="text-align:center;">
                <h2><strong>Do you want the employee to enter their own personal information?
                        </strong></h2>
                <p>Letting the employee enter their own info saves you time and helps prevent errors.</p>
            </div>
            <div class="row">
                <div class="col-md-12 mx-0">
                    <div class="form-card">
                        <div class="radio-group" style="text-align: center;" >
                            <div id="self_onboard" style="width: 40%;height: 80px;text-align: center;" class='radio card px-0 pt-4  mb-3' data-value="w2"><h5><i class="ik ik-plus-circle"></i>Yes, let then self onboard</h5>
                                <span>Send then an invitation to MySystem.</span></div>
                            <div id="system_onboard" style="width: 40%;height: 80px;text-align: center;" class='radio  card px-0 pt-4  mb-3' data-value="1099"><h5><i class="ik edit-2 ik-edit-2"></i>No, I'll do the onboarding</h5>
                                <span>I can fill out all the information</span></div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="display: none;" id="employeeInfoDiv" class="card px-0 pt-4 pb-0 mt-3 mb-3">
            <div style="text-align:center;">
            <h2><strong>Create Employee</strong></h2>
            <p>Fill all form field to go to next step</p>

            </div>
            <div class="row">
                <div class="col-md-12 mx-0">
                    <form class="msform" action="{{ $form_store }}" method="POST" enctype="multipart/form-data" id="createEmployee" >
                        <!-- progressbar -->
                        <input type="hidden" name="employee_type_form" id="employee_type_form" value="1"/>
                        <ul id="progressbar">
                            <li class="active" id="employee_info"><strong>Employee Info</strong></li>
                            <li id="contact_info"><strong>Contact info</strong></li>
                            <li id="tax_info"><strong>Tax info</strong></li>
                            <li id="contractor_info"><strong>Contractor info</strong></li>
                            <li id="direct_deposit"><strong>Direct deposit (Optional)</strong></li>
                            <li id="employment_info"><strong>Employment info</strong></li>
                            <li id="payroll_info"><strong>Payroll info</strong></li>
                            <li id="earnings_and_deductions"><strong>Earnings and deductions</strong></li>
                            <li id="paid_timeoff"><strong>Paid time off</strong></li>
                            <li id="wrap_it_up"><strong>Lets wrap it up</strong></li>
                            <li id="confirm"><strong>Finish</strong></li>
                        </ul>
                        <!-- fieldsets -->
                        <fieldset id="femployee_info">
                            <div class="form-card">
                                <h2 class="fs-title">Employee Info</h2>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="tenant_id">Customer </label><small class="text-danger">*</small>
                                            <select class="form-control" id="tenant_id" name="tenant_id">
                                                <option selected value disabled>choose</option>
                                                @foreach($tenants as $tenant)
                                                    <option value="{{ $tenant->id }}">{{ $tenant->title.'-'.$tenant->email }}</option>
                                                @endforeach
                                            </select>
                                            <small class="text-danger err" id="tenant_id-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="first_name">First Name</label><small class="text-danger">*</small>
                                            <input type="text" name="first_name" class="form-control" id="first_name" placeholder="John" autocomplete="off">
                                            <small class="text-danger err" id="first_name-err"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Middle initial</label>
                                            <input type="text" name="middle_name" class="form-control" id="middle_name" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="middle_name-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Last Name</label><small class="text-danger">*</small>
                                            <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Duo" autocomplete="off">
                                            <small class="text-danger err" id="last_name-err"></small>
                                        </div>
                                    </div>
                                </div>

                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="gender">Gender for insurance/compliance </label><small class="text-danger">*</small>
                                            <select class="form-control" id="gender" name="gender">
                                                <option selected value disabled>choose</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                            <small class="text-danger err" id="gender-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div id="marital_statusDiv" style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="marital_status">Marital Status </label><small class="text-danger">*</small>
                                            <select class="form-control" id="marital_status" name="marital_status">
                                                <option value="single">Single</option>
                                                <option value="married">Married</option>
                                                <option value="other">Other</option>
                                            </select>
                                            <small class="text-danger err" id="gender-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div id="ssn_typeDiv" style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="ssn_type">ID type </label><small class="text-danger">*</small>
                                            <select class="form-control" id="ssn_type" name="ssn_type">
                                                <option value="nic">NIC</option>
                                                <option value="adhaar_card">Adhaar Card</option>

                                            </select>
                                            <small class="text-danger err" id="ssn_type-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label id="ssnlabel" for="last_name">Social security number</label><small class="text-danger">*</small>
                                            <input type="text" name="ssn" class="form-control" id="ssn" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="ssn-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="birthdate">Date of birth</label><small class="text-danger">*</small>
                                            <input type="text" name="birthdate" class="form-control datetimepicker-input" id="birthdate" data-toggle="datetimepicker" data-target="#birthdate" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="birthdate-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div id="preferred_info_div" >
                                <div  style="text-align: center;" class='  px-0 pt-4  mb-3' data-value="1099"><h5><i class="ik tni-edit"></i>Preferred or chosen identity</h5>
                                    <span>Tell us how this employee prefers to identify</span>
                                    <br>
                                    <span>If no information is provided, the employee's legal identity will be used</span>
                                </div>

                                <!-- Preffered-->
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="first_name">First Name</label><small class="text-danger">*</small>
                                            <input type="text" name="pfirst_name" class="form-control" id="pfirst_name" placeholder="John" autocomplete="off">
                                            <small class="text-danger err" id="pfirst_name-err"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Middle initial</label>
                                            <input type="text" name="pmiddle_name" class="form-control" id="pmiddle_name" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="pmiddle_name-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Last Name</label><small class="text-danger">*</small>
                                            <input type="text" name="plast_name" class="form-control" id="plast_name" placeholder="Duo" autocomplete="off">
                                            <small class="text-danger err" id="plast_name-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="gender">Gender for insurance/compliance </label><small class="text-danger">*</small>
                                            <select class="form-control" id="pgender" name="pgender">
                                                <option selected value disabled>choose</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                            <small class="text-danger err" id="pgender-err"></small>
                                        </div>
                                    </div>
                                </div>
                                </div>


                                <div  style="text-align: center;" class='  px-0 pt-4  mb-3' data-value="1099"><h5><i class="ik tni-edit"></i>Home address</h5>
                                    <span>
This is the address that is included on paystubs, W-2s, and 1099s. Make sure the address is correct, in case you need to mail one of these documents to employee or contractor.</span>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Search home address</label><small class="text-danger">*</small>
                                            <input type="text" name="homeaddress" class="form-control" id="homeaddress" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="homeaddress-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Apt, suite, floor, room or PO Box</label><small class="text-danger">*</small>
                                            <input type="text" name="fulladdress" class="form-control" id="fulladdress" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="fulladdress-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">City</label><small class="text-danger">*</small>
                                            <input type="text" name="city" class="form-control" id="city" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="city-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="first_name">State</label><small class="text-danger">*</small>
                                            <input type="text" name="state" class="form-control" id="state" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="state-err"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Zipcode</label>
                                            <input type="text" name="zipcode" class="form-control" id="zipcode" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="zipcode-err"></small>
                                        </div>
                                    </div>
                                </div>



                            </div>
                            <input type="button" name="save_for_later" class="next action-button save_for_later" value="Save for later"/>
                            <input type="button" name="next" class="next action-button" id="employee_info_next" value="Next Step"/>
                        </fieldset>
                        <fieldset id="fcontact_info" >
                            <div class="form-card">
                                <h2 class="fs-title">Contact Info</h2>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Phone number</label><small class="text-danger">*</small>
                                            <input type="text" name="phone" class="form-control" id="phone" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="phone-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Additional phone number</label>
                                            <input type="text" name="aphone_number" class="form-control" id="aphone_number" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="aphone_number-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Email</label><small class="text-danger">*</small>
                                            <input type="email" name="email" class="form-control" id="email" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="email-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Additional info</label><br>
                                            <small>Internal notes</small>
                                            <textarea class="form-control" id="internal_notes" name="internal_notes" rows="3"></textarea>
                                            <small class="text-danger err" id="internal_notes-err"></small>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
                            <input type="button" name="next" class="next action-button" id="contact_info_next" value="Next Step"/>
                        </fieldset>
                        <fieldset id="ftax_info" >
                            <div class="form-card">
                                <h2 class="fs-title">Tax Info</h2>
                                <div class='  px-0 pt-4  mb-3' data-value="1099"><h5>Fedral income tax</h5>
                                    <span>All employee must complete a Federal Form W4. This helps us calculate how much money to withhold from their paycheck for federal income taxes.</span>
                                </div>



                                <div class="radio-group" style="text-align: center;" >
                                    <div   class='radio card  pt-4 col-md-3 col-lg-3 col-sm-12 ' id="currentw4_id" data-value="currentw4"><h5>Current W4</h5></div>
                                    <div   class='radio  card  pt-4 col-md-3 col-lg-3 col-sm-12' id="pcurrentw4_id" data-value="pcurrentw4"><h5>Previous W4</h5></div>
                                    <br>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="gender">Which W-4 does employee have? </label><small class="text-danger">*</small>
                                            <select class="form-control" id="whichw4employee" name="whichw4employee">
                                                <option selected value="" disabled>choose</option>
                                                <option value="currentw4">Current W-4</option>
                                                <option value="previousw4">Previous W-4</option>
                                                <option value="other">Other</option>
                                            </select>
                                            <small class="text-danger err" id="whichw4employee-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="gender">Withholding status (step 1c) </label><small class="text-danger">*</small>
                                            <select class="form-control" id="withholdingstatus" name="withholdingstatus">
                                                <option selected value disabled>choose</option>
                                                <option value="singleormerried">Single or Merried but filing separately</option>
                                                <option value="married_filing_jointly">Married Filing Jointly or Qualifying Surviving Spouse</option>
                                                <option value="head_of_household">Head of household</option>
                                                <option value="other">Other</option>
                                            </select>
                                            <small class="text-danger err" id="whichw4employee-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="gender">Multiple jobs or spouce workes (step 2c) </label><small class="text-danger">*</small>
                                            <select class="form-control" id="multijobsorspouceworks" name="multijobsorspouceworks">
                                                <option selected value disabled>choose</option>
                                                <option value="no">No</option>
                                                <option value="yes">Yes</option>
                                                <option value="other">Other</option>
                                            </select>
                                            <small class="text-danger err" id="whichw4employee-err"></small>
                                        </div>
                                    </div>
                                </div>


                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">
                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Claim dependents (step 3)</label><small class="text-danger">*</small>
                                            <input type="text" name="claindependents" class="form-control" id="claindependents" placeholder="$0" autocomplete="off">
                                            <small class="text-danger err" id="claindependents-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Other income (step 4a)</label><small class="text-danger">*</small>
                                            <input type="text" name="otherincome" class="form-control" id="otherincome" placeholder="$0" autocomplete="off">
                                            <small class="text-danger err" id="otherincome-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Deductions (step 4b)</label><small class="text-danger">*</small>
                                            <input type="text" name="deductions_step4b" class="form-control" id="deductions_step4b" placeholder="$0" autocomplete="off">
                                            <small class="text-danger err" id="deductions_step4b-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Extra withholding (step 4c)</label><br>
                                            <input type="text" name="extrawithholding" class="form-control" id="extrawithholding" placeholder="$0" autocomplete="off">
                                            <small class="text-danger err" id="extrawithholding-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="gender">Exempt from withholding </label><small class="text-danger">*</small>
                                            <select class="form-control" id="exemptwithholding" name="exemptwithholding">
                                                <option selected value disabled>choose</option>
                                                <option value="not_exempt">Not Exempt</option>
                                                <option value="no_withholding">No withholding</option>
                                            </select>
                                            <small class="text-danger err" id="exemptwithholding-err"></small>
                                        </div>
                                </div>

                                <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="gender">Is this employee exempt from fedral tax? </label><small class="text-danger">*</small>
                                            <select class="form-control" id="isthisemployeeexempt" name="isthisemployeeexempt">
                                                <option selected value disabled>choose</option>
                                                <option value="no">No</option>
                                                <option value="yes">Yes</option>
                                                <option value="other">Other</option>
                                            </select>
                                            <small class="text-danger err" id="isthisemployeeexempt-err"></small>
                                        </div>
                                    </div>
                                </div>

                                <div class='px-0 pt-4  mb-3' data-value="">
                                    <span>Because of the tax implications, your company must be set up to allow employee tax exemptions. Please contact your Caisol Services team if you need to select Yes for this field.</span>
                                    <span>If the eployee has to pay income tax but does not want if taken from their paycheck, please select Exempt from income tax withholding above.</span>
                                </div>

                                <div class="col-md-12 col-lg-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="gender">State Income Tax </label><small class="text-danger">*</small>
                                        <small>State where the employee lives</small>
                                        <select class="form-control" id="statewheretheremployeelives" name="statewheretheremployeelives">
                                            <option selected value="" disabled>choose</option>
                                            @foreach($states as $state)
                                                <option value="{{ $state->shortcode }}">{{ $state->title.'-'.$state->shortcode }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger err" id="statewheretheremployeelives-err"></small>
                                    </div>
                                </div>

                                <div id="state_taxes_Div"  style="margin-right:0 !important;margin-left:0 !important;" class="row">


                                </div>

                                <div style="text-align: center;"  class='  px-0 pt-4  mb-3' data-value="">
                                    <span>You don't have to worry about this! Texas has no state income tax.</span>
                                </div>
                                <div class="col-md-12 col-lg-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="gender">Does the employee work in the state where they live?</label><small class="text-danger">*</small>
                                        <select class="form-control" id="employeeworkstatewherelive" name="employeeworkstatewherelive">
                                            <option selected value disabled>choose</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <small class="text-danger err" id="employeeworkstatewherelive-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="gender">Is this employee exempt from any state taxes?</label><small class="text-danger">*</small>
                                        <select class="form-control" id="isemployeeexemptfromstatetaxes" name="isemployeeexemptfromstatetaxes">
                                            <option selected value disabled>choose</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                        <small class="text-danger err" id="isemployeeexemptfromstatetaxes-err"></small>
                                    </div>
                                </div>
                                <div style="text-align: center;"  class='  px-0 pt-4  mb-3' data-value="">
                                    <span>Because of the tax implications, your company must be set up to allow employee tax exemption. Please contact your Caisol Services team if you need to select Yes for this field.</span>
                                    <span>If the employee has to pay income taz but does not want it taken from their paycheck, please select Exempt from income taz withholding above.</span>
                                </div>
                            </div>
                            <input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
                            <input type="button" name="next" class="next action-button" id="tax_info_next" value="Next Step"/>
                        </fieldset>
                        <fieldset id="fcontractor_info" >
                            <div class="form-card">
                                <h2 class="fs-title">Contractor Info</h2>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="cemployment_type">Basic info </label><br>
                                            <label for="ctenant_id">Customer </label><small class="text-danger">*</small>
                                            <select class="form-control" id="ctenant_id" name="ctenant_id">
                                                <option selected value disabled>choose</option>
                                                @foreach($tenants as $tenant)
                                                    <option value="{{ $tenant->id }}">{{ $tenant->title.'-'.$tenant->email }}</option>
                                                @endforeach
                                            </select>
                                            <small class="text-danger err" id="ctenant_id-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="cemployment_type">Employment type </label><small class="text-danger">*</small>
                                            <select class="form-control" id="cemployment_type" name="cemployment_type">
                                                <option selected value="" disabled>choose</option>
                                                <option value="individual_contractor_with_ssn">Individual Contractor with SSN</option>
                                                <option value="Company_contractor_with_tin">Company Contractor with TIN</option>
                                            </select>
                                            <small class="text-danger err" id="cemployment_type-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="contractor_first_name">First Name</label><small class="text-danger">*</small>
                                            <input type="text" name="contractor_first_name" class="form-control" id="contractor_first_name" placeholder="John" autocomplete="off">
                                            <small class="text-danger err" id="contractor_first_name-err"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="contractor_middle_name">Middle initial</label>
                                            <input type="text" name="contractor_middle_name" class="form-control" id="contractor_middle_name" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="contractor_middle_name-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="contractor_last_name">Last Name</label><small class="text-danger">*</small>
                                            <input type="text" name="contractor_last_name" class="form-control" id="contractor_last_name" placeholder="Duo" autocomplete="off">
                                            <small class="text-danger err" id="contractor_last_name-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="contractor_ssn">Social security	number</label><small class="text-danger">*</small>
                                            <input type="text" name="contractor_ssn" class="form-control" id="contractor_ssn" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="contractor_ssn-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div  style="text-align: center;" class='  px-0 pt-4  mb-3' data-value="1099"><h5><i class="ik tni-edit"></i>Home addres</h5>
                                    <span>
This is the address that is included on paystubs, W-2s, and 1099s. Make sure the address is correct, in case you need to mail one of these documents to employee or contractor.</span>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="contractor_homeaddress">Search home address</label><small class="text-danger">*</small>
                                            <input type="text" name="contractor_homeaddress" class="form-control" id="contractor_homeaddress" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="contractor_homeaddress-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="contractor_fulladdress">Apt, suite, floor, room or PO Box</label><small class="text-danger">*</small>
                                            <input type="text" name="contractor_fulladdress" class="form-control" id="contractor_fulladdress" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="contractor_fulladdress-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="contractor_city">City</label><small class="text-danger">*</small>
                                            <input type="text" name="contractor_city" class="form-control" id="contractor_city" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="contractor_city-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="contractor_state">State</label><small class="text-danger">*</small>
                                            <input type="text" name="contractor_state" class="form-control" id="contractor_state" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="contractor_state-err"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="contractor_zipcode">Zipcode</label>
                                            <input type="text" name="contractor_zipcode" class="form-control" id="contractor_zipcode" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="contractor_zipcode-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <h2 class="fs-title">Contact Info</h2>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="contractor_phone_number">Phone number</label><small class="text-danger">*</small>
                                            <input type="text" name="contractor_phone_number" class="form-control" id="contractor_phone_number" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="contractor_phone_number-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="contractor_aphone_number">Additional phone number</label><small class="text-danger">*</small>
                                            <input type="text" name="contractor_aphone_number" class="form-control" id="contractor_aphone_number" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="contractor_aphone_number-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="contractor_email">Email</label><small class="text-danger">*</small>
                                            <input type="email" name="contractor_email" class="form-control" id="contractor_email" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="contractor_email-err"></small>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
                            <input type="button" name="next" class="next action-button" id="contractor_info_next" value="Next Step"/>
                        </fieldset>
                        <fieldset>
                            <div class="form-card">
                                <h2 class="fs-title">Direct deposit (Optional)</h2>
                                <div class='  px-0 pt-4  mb-3' data-value="1099">
                                    <span>An employee can have their pay deposited in up to 4 deposit accounts, including checking, saving, paycard, and pre-loaded debit card.</span>
                                    <h5>Bank info</h5>
                                    <span>we have partnered with Vantage to hep make sure direct deposit are right the first time!.</span>
                                </div>
                                <button  type="button" class="action-button addAccountsBtn" id="addAccounts">Add Account</button>
                                <div  id="itemsAccounts" >
                                </div>


                            </div>
                            <input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
                            <input type="button" name="make_payment" class="next action-button" value="Next Step"/>
                        </fieldset>
                        <fieldset>
                            <div class="form-card">
                                <h2 class="fs-title">Employment info</h2>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Contact info</label><br>
                                            <small>Work phone</small>
                                            <input type="text" name="work_phone" class="form-control" id="work_phone" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="work_phone-err"></small>
                                        </div>
                                    </div>
                                    <div style="margin-top: 18px;" class="col-md-6 col-lg-3 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Ext</label>
                                            <input type="text" name="work_phone_ext" class="form-control" id="work_phone_ext" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="work_phone_ext-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Work Email</label><small class="text-danger">*</small>
                                            <input type="email" name="work_email" class="form-control" id="work_email" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="work_email-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <label for="position_id">Position</label><small class="text-danger">*</small>
                                        <select class="form-control" name="position_id" id="position_id">
                                            @foreach($positions as $position)
                                                <option value="{{ $position->id }}">{{ $position->title }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger err" id="position_id-err"></small>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <label for="schedule_id">Schedule</label><small class="text-danger">*</small>
                                        <select class="form-control" name="schedule_id" id="schedule_id">
                                            @foreach($schedules as $schedule)
                                                <option value="{{ $schedule->id }}">{{ $schedule->time_in.'-'.$schedule->time_out }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger err" id="schedule_id-err"></small>
                                    </div>
                                </div>

                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Hiring Info</label><br>
                                            <small>Hire Date</small>
                                            <input type="text" name="hire_date" class="form-control datetimepicker-input" id="hire_date" data-toggle="datetimepicker" data-target="#hire_date"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="hire_date-err"></small>
                                        </div>
                                    </div>
                                    <div style="margin-top: 18px;" class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Status</label>
                                            <select class="form-control" id="hire_date_status" name="hire_date_status">
                                                <option selected value disabled>choose</option>
                                                <option value="active">Active</option>
                                                <option value="terminated">Terminated</option>
                                                <option value="leave_of_bsence">Leave of absence</option>
                                            </select>
                                            <small class="text-danger err" id="hire_date_status-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div id="terminationDiv" style="display: none;" >
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="terminatin_date">Terminatin date</label>
                                            <input type="text" name="terminatin_date" class="form-control datetimepicker-input" id="terminatin_date" data-toggle="datetimepicker" data-target="#terminatin_date" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="terminatin_date-err"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_day_worked">Last day worked</label>
                                            <input type="text" name="last_day_worked" class="form-control datetimepicker-input" id="last_day_worked" data-toggle="datetimepicker" data-target="#last_day_worked" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="last_day_worked-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div id="termination_extra_paramsDiv" style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="termination_typ">Termination type</label>
                                            <select class="form-control" id="termination_type" name="termination_type">
                                                <option selected value disabled>choose</option>
                                                <option value="involuntary">Involuntary</option>
                                                <option value="voluntary">Voluntary</option>
                                                <option value="miscellaneous">Miscellaneous</option>
                                            </select>
                                            <small class="text-danger err" id="termination_type-err"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="termination_description">Termination description</label>
                                            <select class="form-control" id="termination_description" name="termination_description">
                                                <option selected value disabled>choose</option>
                                                <option value="discharge_no_misconduct">Discharge - No Misconduct</option>
                                                <option value="discharge_misconduct_genral">Discharge - Misconduct(genral)</option>
                                                <option value="discharge_safety_policy">Discharge - Safety Policy</option>
                                                <option value="discharge_drug_alcohol_policy">Discharge - Drung / Alcohol Policy</option>
                                                <option value="other">Other</option>
                                            </select>
                                            <small class="text-danger err" id="termination_description-err"></small>
                                        </div>
                                    </div>
                                </div>
                                </div>


                                <div id="company_extra_paramsDiv" style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Company paid pension</label>
                                            <select class="form-control" id="comapny_paid_pension" name="comapny_paid_pension">
                                                <option selected value disabled>choose</option>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                            <small class="text-danger err" id="hire_date_status-err"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Statutory employee</label>
                                            <select class="form-control" id="statutory_employee" name="statutory_employee">
                                                <option selected value disabled>choose</option>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                            <small class="text-danger err" id="statutory_employee-err"></small>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
                            <input type="button" name="make_payment" class="next action-button" id="employment_info_next" value="Next Step"/>
                        </fieldset>
                        <fieldset>
                            <div class="form-card">
                                <h2 class="fs-title">Payroll info</h2>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Pay info</label><br>
                                            <small>Pay type</small>
                                            <select class="form-control" id="pay_type" name="pay_type">
                                                <option selected value disabled>choose</option>
                                                <option value="hourly">Hourly</option>
                                                <option value="salary">Salary</option>
                                            </select>
                                            <small class="text-danger err" id="pay_type-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Basis of pay</label><br>
                                            <select class="form-control" id="basis_of_pay" name="basis_of_pay">
                                                <option selected value disabled>choose</option>
                                                <option value="same_as_pay_type">Same as Pay Type</option>
                                                <option value="daily">Daily</option>
                                                <option value="piece">Piece</option>
                                                <option value="shift">Shift</option>
                                                <option value="commission">Commission</option>
                                            </select>
                                            <small class="text-danger err" id="basis_of_pay-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Pay schedule</label><br>
                                            <select class="form-control" id="pay_schedule" name="pay_schedule">
                                                <option selected value disabled>choose</option>
                                                <option value="semi_monthly">Semi-Monthly</option>
                                                <option value="weekly">Weekly</option>
                                                <option value="bi_weekly">Bi-Weekly</option>
                                                <option value="monthly">Monthly</option>
                                            </select>
                                            <small class="text-danger err" id="pay_schedule-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Standard hours per day period</label>
                                            <input type="number" name="standard_hours_per_day_period" class="form-control" id="standard_hours_per_day_period" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="standard_hours_per_day_period-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Employment type</label><br>
                                            <select class="form-control" id="employment_type" name="employment_type">
                                                <option selected value disabled>choose</option>
                                                <option value="full_time">Full time</option>
                                                <option value="part_time">Part time</option>
                                                <option value="temporary">Temporary</option>
                                            </select>
                                            <small class="text-danger err" id="employment_type-err"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="currency_type">Currency type</label><br>
                                            <select class="form-control" id="currency_type" name="currency_type">
                                                <option value="USD">$ Dollar</option>
                                                <option value="PKR">PKR</option>
                                                <option value="INR">₹ INR</option>
                                            </select>
                                            <small class="text-danger err" id="currency_type-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div id="seasonal_employeeDiv" style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Seasonal employee</label><br>
                                            <select class="form-control" id="seasonal_employee" name="seasonal_employee">
                                                <option selected value disabled>choose</option>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="pay_stubDiv"  style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label class="custom-control custom-checkbox mb-0" title="Add a new message on the employee's pay stub" data-toggle="tooltip" data-placement="right">
                                                <input type="checkbox" class="custom-control-input" id="checkbox_add_new_message_on_paystub" name="checkbox_add_new_message_on_paystub">
                                                <span class="custom-control-label">Add a new message on the employee's pay stub</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <textarea class="form-control" id="add_new_message_on_paystub" name="add_new_message_on_paystub" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div id="hourly_par_rateDiv" style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Hourly pay rate</label><br>
                                            <input type="number" name="hourly_pay_rate" class="form-control" id="hourly_pay_rate"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="hourly_pay_rate-err"></small>
                                        </div>
                                    </div>

                                </div>
                                <div id="salary_type_checkboxDiv" style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Pay rate</label><br>
                                            <label class="custom-control custom-checkbox mb-0"  data-toggle="tooltip" data-placement="right">
                                                <input type="radio" class="custom-control-input" value="semi_monthly" id="semi_monthly_salary_type" name="salary_type">
                                                <span class="custom-control-label">Semi Monthly</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div style="margin: 27px 0px 0px 0px" class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="custom-control custom-checkbox mb-0"  data-toggle="tooltip" data-placement="right">
                                                <input type="radio" class="custom-control-input" value="annual_salary"  id="annual_salary" name="salary_type">
                                                <span class="custom-control-label">Annual Salary</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Salary</label><br>
                                            <input type="number" name="semi_monthly_annual_salary" class="form-control" id="semi_monthly_annual_salary"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="semi_monthly_annual_salary-err"></small>
                                        </div>
                                    </div>

                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Hourly rate</label><br>
                                            <input type="number" name="semi_monthly_annual_salary_hourly_rate" class="form-control" id="semi_monthly_annual_salary_hourly_rate"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="semi_monthly_annual_salary_hourly_rate-err"></small>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
                            <input type="button" name="make_payment" class="next action-button" id="payroll_info_next" value="Next Step"/>
                        </fieldset>
                        <fieldset id="fearnings_and_deductions">
                            <div class="form-card">
                                <h2 class="fs-title">Earnings and deductions</h2>
                                <div class='  px-0 pt-4  mb-3' data-value="1099">
                                    <span>Don't see what you are looking for? You can add new earnings and deductions for your company.</span>
                                </div>
                                <span>Earnings.</span>
                                <button  type="button" class="action-button" id="addEarnings" >Add earning</button>
                                    <div  id="itemsEarnings" >
                                    </div>
                            </div>
                            <div class="form-card">
                                <div class='  px-0 pt-4  mb-3' data-value="1099">
                                </div>
                                <span>Deductions.</span>
                                <button  type="button" class="action-button" id="addDeductions">Add deduction</button>
                                <div  id="itemsDeductions" >
                                </div>
                            </div>
                            <input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
                            <input type="button" name="make_payment" class="next action-button beforeWrapitUpType2"  value="Next Step"/>
                        </fieldset>
                        <fieldset id="fpaid_timeoff" >
                            <div class="form-card">
                                <div class='  px-0 pt-4  mb-3' data-value="1099">
                                </div>
                                <h2 class="fs-title">Paid time off</h2>
                                <div class='  px-0 pt-4  mb-3' data-value="1099">
                                    <span>Paid time off(PTO) lets you track vacations,personal, and sick time of your employees.</span>
                                </div>
                                <div class="col-md-6 col-lg-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="last_name">PTO plan</label>
                                        <select class="form-control" id="pto_plan" name="pto_plan">
                                            <option selected value disabled>choose</option>
                                            <option value="vacation">Vacation</option>
                                            <option value="sick">Sick</option>
                                            <option value="personal">Personal</option>
                                            <option value="others">Others</option>
                                        </select>
                                        <small class="text-danger err" id="pto_plan-err"></small>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="hours_to_off">Hours to off</label><br>
                                            <input type="number" name="hours_to_off" class="form-control" id="hours_to_off"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="hours_to_off-err"></small>
                                        </div>
                                    </div>

                                </div>
                            </div>

                                <input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
                                <input type="button" name="make_payment" class="next action-button beforeWrapitUp"  value="Next Step"/>
                        </fieldset>
                        <fieldset id="letsWrapupDiv" >
                            <div class="form-card">
                                <h2 class="fs-title">Lets wrap it up!</h2>
                                <div class='  px-0 pt-4  mb-3' data-value="1099">
                                    <span>Here's everything we have for Employee. Take a look and make sure everything is accurate.</span>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="form-card row">

                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Personal Info</label><br>
                                            <label >Name : </label>
                                            <label id="pViewName"></label><br>
                                            <label >DOB : </label>
                                            <label id="pViewDob"></label><br>
                                            <label >SSN : </label>
                                            <label id="pViewSsn"></label>
                                        </div>
                                    </div>



                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="form-card row">

                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Tax Info</label><br>
                                            <label >Fedral Tax : </label>
                                            <label id="pViewFedraltax" ></label>
                                            <br>
                                            <label >State Tax : </label>
                                            <label id="pViewStatetax"></label>
                                        </div>
                                    </div>



                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="form-card row">

                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Employment Info</label><br>
                                            <label >Hire Date : </label>
                                            <label id="pViewHiredate" ></label>
                                            <br>
                                            <label >Employment Status : </label>
                                            <label id="pViewEmploymentstatus"></label>
                                            <br>
                                            <label id="pViewCompanypaidpensionlbl" >Company Paid Pension : </label>
                                            <label id="pViewCompanypaidpension"></label>
                                            <br>
                                            <label id="pViewStatutoryemployeelbl" >Statutory employee : </label>
                                            <label id="pViewStatutoryemployee"></label>
                                        </div>
                                    </div>



                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="form-card row">

                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Payroll Info</label><br>
                                            <label >Pay type : </label>
                                            <label id="pViewPaytype" ></label>
                                            <br>
                                            <label >Annual salary : </label>
                                            <label id="pViewAnnualsalary"></label>
                                            <br>
                                            <label >Basis of pay : </label>
                                            <label id="pViewBasisofpay"></label>
                                            <br>
                                            <label >Pay schedule : </label>
                                            <label id="pViewPayschedule"></label>
                                            <br>
                                            <label >Employment type : </label>
                                            <label id="pViewEmploymenttype"></label>
                                            <br>
                                            <label id="pViewSeasonalemployeelbl" >Seasonal employee : </label>
                                            <label id="pViewSeasonalemployee"></label>
                                            <br>
                                            <label id="pViewCheckstubmessagelbl" >Check stub message : </label>
                                            <label id="pViewCheckstubmessage"></label>
                                        </div>
                                    </div>



                                </div>
                                <div id="earningsandDeductionssDiv" style="margin-right:0 !important;margin-left:0 !important;" class="form-card row">

                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Earnings and Deductionss</label><br>
                                            <label >Earning name(s) : </label>
                                            <label id="pViewEarningname" ></label>
                                            <br>
                                            <label >Deduction name(s) : </label>
                                            <label id="pViewDeductionname"></label>

                                        </div>
                                    </div>



                                </div>

                            </div>
                            <input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
                            <input type="submit" name="make_payment" class="next action-button" id="finalformsubmission" value="Confirm"/>
                        </fieldset>
                        <fieldset id="fsuccessDiv" >
                            <div class="form-card">
                                <div id="successmessage" style="display: none;">
                                    <h2 class="fs-title text-center">Success !</h2>
                                    <br><br>
                                    <div class="row justify-content-center">
                                        <div class="col-3">
                                            <img src="https://img.icons8.com/color/96/000000/ok--v2.png" class="fit-image">
                                        </div>
                                    </div>
                                    <br><br>
                                    <div class="row justify-content-center">
                                        <div class="col-7 text-center">
                                            <h5>You Have Successfully Added Employee Record</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="errorMessageDiv col-7 text-center">
                                        <h2 id="processingH2" style="display: block;" class="fs-title text-center">Processing !</h2>
                                        <div class="form-group" id="errorBlock">
                                            <ul id="showErrors" class="text-danger"></ul>

                                        </div>
                                        <input id="backtoPreviousScreen" style="display: none" type="button" name="previous" class="previous action-button-previous" value="Previous"/>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
        <!--<div class="widget overflow-visible">
            <div class="progress progress-sm progress-hi-3 hidden">
                <div class="progress-bar bg-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
            </div>
            <div class="widget-body">
                <div class="overlay hidden">
                    <i class="ik ik-refresh-ccw loading"></i>
                    <span class="overlay-text">New Employee Creating...</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="state">
                        <h5 class="text-secondary">Create Employee</h5>
                    </div>
                </div>

                <form action="{{ $form_store }}" method="POST" enctype="multipart/form-data" id="createEmployee">
                    @csrf
                    <div class="row">
                      <div class="col-md-6 col-lg-6 col-sm-12">
                       <div class="form-group">
                        <label for="first_name">First Name</label><small class="text-danger">*</small>
                        <input type="text" name="first_name" class="form-control" id="first_name" placeholder="John" autocomplete="off">
                        <small class="text-danger err" id="first_name-err"></small>
                      </div>
                      </div>
                      <div class="col-md-6 col-lg-6 col-sm-12">
                       <div class="form-group">
                        <label for="last_name">Last Name</label><small class="text-danger">*</small>
                        <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Duo" autocomplete="off">
                        <small class="text-danger err" id="last_name-err"></small>
                      </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 col-lg-12 col-sm-12">
                        <div class="form-group">
                          <label for="email">Email</label><small class="text-danger">*</small>
                          <input type="email" name="email" class="form-control" id="email" placeholder="john@example.com" autocomplete="off">
                          <input type="hidden" name="username">
                          <small class="text-danger err" id="email-err"></small>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4 col-lg-4 col-sm-12">
                        <div class="form-group">
                          <label for="gender">Gender </label><small class="text-danger">*</small>
                          <select class="form-control" id="gender" name="gender">
                            <option selected value disabled>choose</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                          </select>
                          <small class="text-danger err" id="gender-err"></small>
                        </div>
                      </div>
                      <div class="col-md-4 col-lg-4 col-sm-12">
                        <div class="form-group">
                          <label for="phone">Phone</label><small class="text-danger">*</small>
                          <input type="text" name="phone" class="form-control" id="phone" placeholder="XXXX-XXX-XXX" autocomplete="off" data-mask="0000-000-000">
                          <small class="text-danger err" id="phone-err"></small>
                        </div>
                      </div>
                      <div class="col-md-4 col-lg-4 col-sm-12">
                        <div class="form-group">
                          <label for="birthdate">Birthdate</label><small class="text-danger">*</small>
                          <input type="text" class="form-control datetimepicker-input" name="birthdate" id="birthdate" data-toggle="datetimepicker" data-target="#birthdate" autocomplete="off">
                          <small class="text-danger err" id="birthdate-err"></small>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-lg-6 col-sm-12">
                       <div class="form-group">
                        <label for="position_id">Position</label><small class="text-danger">*</small>
                        <select class="form-control" name="position_id" id="position_id">
                          @foreach($positions as $position)
                            <option value="{{ $position->id }}">{{ $position->title }}</option>
                          @endforeach
                        </select>
                        <small class="text-danger err" id="position_id-err"></small>
                      </div>
                      </div>
                      <div class="col-md-6 col-lg-6 col-sm-12">
                       <div class="form-group">
                        <label for="schedule_id">Schedule</label><small class="text-danger">*</small>
                        <select class="form-control" name="schedule_id" id="schedule_id">
                          @foreach($schedules as $schedule)
                            <option value="{{ $schedule->id }}">{{ $schedule->time_in.'-'.$schedule->time_out }}</option>
                          @endforeach
                        </select>
                        <small class="text-danger err" id="schedule_id-err"></small>
                      </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-lg-6 col-sm-12">
                       <div class="form-group">
                        <label for="rate_per_hour">Rate Per Hour</label><small class="text-danger">*</small>
                        <input type="text" name="rate_per_hour" class="form-control" id="rate_per_hour" placeholder="200.00" autocomplete="off">
                        <small class="text-danger err" id="rate_per_hour-err">It's important for Payscal calculation.</small>
                      </div>
                      </div>
                      <div class="col-md-6 col-lg-6 col-sm-12">
                       <div class="form-group">
                        <label for="salary">Salary</label><small class="text-danger">*</small>
                        <input type="text" name="salary" class="form-control" id="salary" placeholder="45000.00" autocomplete="off">
                        <small class="text-danger err" id="salary-err">It's just informaton purpose. it will not reflect on payslip.</small>
                      </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                          <label for="address">Address</label>  <small class="text-secondary">(Optional)</small>
                          <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                        </div>
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                          <label for="remark">Remark</label>  <small class="text-secondary">(Optional)</small>
                          <textarea class="form-control" id="remark" name="remark" rows="3"></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-lg-6 col-sm-12">
                        <div class="form-group">
                          <label for="is_active">Publish </label>
                          <select class="form-control" id="is_active" name="is_active">
                            <option value="1">Publish Now</option>
                            <option value="0">Do it Later</option>
                          </select>
                        </div>
                            <button type="submit" class="btn btn-primary"><i class="ik save ik-save"></i>Submit</button>
                            <a href="{{ route('admin.employee.index') }}" class="btn btn-light"><i class="ik arrow-left ik-arrow-left"></i> Go Back</a>
                      </div>
                      <div class="col-md-6 col-lg-6 col-sm-12" id="add-avatar-div">
                        <div class="form-group">
                          <label for="avatar">Upload Profile Picture</label><small class="text-secondary">(Optional)</small>
                          <label for="avatar" class="btn btn-outline-danger d-block btn-block mb-0"><i class="ik ik-image"></i> Attach Document</label>
                          <input type="file" name="avatar" class="image hidden" id="avatar">
                          <small class="text-danger err" id="media-err">*Please add pixel perfect avatar of Employee.</small>
                        </div>
                      </div>
                      <div class="col-md-6 col-lg-6 col-sm-12 hidden" id="show-avatar-div">
                        <div class="form-group my-auto">
                          <a href="#" class="text-danger float-right" data-remove="" id="remove-avatar-profile"><i class="ik ik-x-circle"></i></a>
                          <img src="{{ asset('admin_assets/avatars/merchant/thumb/male.png') }}" class="circle-temp" id="avatar-profile">
                        </div>
                      </div>
                    </div>
                </form>
            </div>

        </div>-->
    </div>
</div>

<!--Avatar model-->
<div class="modal" id="AvatarModel">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <!-- Modal body -->
      <div class="modal-body">
        <div class="img-container">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12" id="avatar-preview">

            </div>
          </div>
        </div>
        <div class="mt-2">
          <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12">
              <button type="button" class="btn btn-block btn-outline-secondary" data-dismiss="modal"><i class="ik x-circle ik-x-circle"></i> Close</button>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12">
              <button type="button" class="btn btn-block btn-dark" id="crop-nd-save"><i class="ik ik-crop"></i> Crop & Save</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

@endsection

@section('js')

<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyAT6g4HAsIG_8P8t4u9xxWijkkLTv-LjNw&libraries=places" ></script>

<script type="text/javascript">

    var employeeType=0;

    //AIzaSyAT6g4HAsIG_8P8t4u9xxWijkkLTv-LjNw
    let templateEarnings = `<div class="form-card" ><button type="button" class="remove" style="margin: 0px 0px 0px 500px;">X</button>
                                        <div class="col-md-6 col-lg-12 col-sm-12">
                                            <div class="form-group">
                                                <label for="last_name">Earnings</label>
                                                <select class="form-control" id="earnings" name="earnings[]">
                                                    <option selected value disabled>choose</option>
                                                    <option value="daily_pay">Daily pay</option>
                                                    <option value="overtime">Overtime</option>
                                                    <option value="cash_tips">Cash tips</option>
                                                    <option value="others">Others</option>
                                                </select>
                                                <small class="text-danger err" id="hire_date_status-err"></small>
                                            </div>
                                        </div>
                                        <div style="margin-right:0 !important;margin-left:0 !important;" class=" row">

                                        <div  class="col-md-6 col-lg-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="last_name">Type</label>
                                                <select class="form-control" id="earning1_type" name="earning_type[]">
                                                    <option selected value disabled>choose</option>
                                                    <option value="amount">Amount</option>
                                                    <option value="hours">Hours</option>
                                                </select>
                                                <small class="text-danger err" id="earning_type-err"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="last_name">Value</label><br>
                                                <input type="text" name="earning_amount[]" class="form-control " id="earning1_amount"   placeholder="" autocomplete="off">
                                                <small class="text-danger err" id="earning1_amount-err"></small>
                                            </div>
                                        </div>
                                        </div></div>`;
    let templateDeductions = ` <div class="form-card" >
                                        <button type="button" class="remove removeDeductions" style="margin: 0px 0px 0px 500px;">X</button>
                                        <div class="col-md-6 col-lg-12 col-sm-12">
                                            <div class="form-group">
                                                <label for="last_name">Deductions</label>
                                                <select class="form-control" id="deductions" name="deductions[]">
                                                    <option selected value disabled>choose</option>
                                                    <option value="medical">Medical</option>
                                                    <option value="advance">Advance</option>
                                                    <option value="loan">Loan</option>
                                                    <option value="hsa_amount">HSA Amount</option>
                                                    <option value="miscellaneous">Miscellaneous</option>
                                                    <option value="post_tax_insurance_dental">Post Tax Insurance Dental</option>
                                                    <option value="post_tax_insurance_medical">Post Tax Insurance Medical</option>
                                                    <option value="post_tax_insurance_vision">Post Tax Insurance Vision</option>
                                                    <option value="others">Others</option>
                                                </select>
                                                <small class="text-danger err" id="deductions-err"></small>
                                            </div>
                                        </div>
                                        <div style="margin-right:0 !important;margin-left:0 !important;" class=" row">

                                            <div  class="col-md-6 col-lg-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="last_name">Type</label>
                                                    <select class="form-control" id="deduction_type" name="deduction_type[]">
                                                        <option selected value disabled>choose</option>
                                                        <option value="amount">Dollars</option>
                                                        <option value="hours">Hours</option>
                                                    </select>
                                                    <small class="text-danger err" id="deduction_type-err"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="deduction_amount_hours">Value</label><br>
                                                    <input type="text" name="deduction_amount_hours[]" class="form-control " id="deduction_amount_hours"   placeholder="" autocomplete="off">
                                                    <small class="text-danger err" id="deduction_amount_hours-err"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Does this deduction have a goal amount?</label>
                                            <select class="form-control" id="deduction_have_goal_amount" name="deduction_have_goal_amount[]">
                                                <option selected value disabled>choose</option>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                            <small class="text-danger err" id="deduction_have_goal_amount-err"></small>
                                        </div>
                                    </div>
                                        <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="last_name">Payment method for this deduction</label>
                                            <select class="form-control" id="deduction_payment_method" name="deduction_payment_method[]">
                                                <option selected value disabled>choose</option>
                                                <option value="none">None</option>
                                                <option value="check">Check</option>
                                                <option value="direct_deposit">Direct deposit</option>
                                            </select>
                                            <small class="text-danger err" id="deduction_payment_method-err"></small>
                                        </div>
                                    </div>
                                </div>
                                     `;
    let templateSickPlan = `<tr id="sickplanw2row" >
    <th scope="row">Sick (w2 pto)</th>
    <td>Annual Allownce</td>
    <td>48 Hours</td>
    <td>6 Days</td>
    </tr>`;
    let templateSickPlanUnlimited = `<tr id="sickplanallrow" >
    <th scope="row">Unlimited sick</th>
    <td>Annual Allownce</td>
    <td>48 Hours</td>
    <td>6 Days</td>
    </tr>`;

    let templateAccounts = `<div class="form-card accountsDivCount" ><button type="button" class="remove removeAccounts" style="margin: 0px 0px 0px 500px;">X</button>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="account_type">Account type</label><br>
                                            <select class="form-control" id="account_type" name="account_type[]">
                                                <option selected value disabled>choose</option>
                                                <option value="checking_account">Checking Account</option>
                                                <option value="saving_account">Saving Account</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="routing_number">Routing number</label><br>
                                            <input type="text" name="routing_number[]" class="form-control" id="routing_number"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="routing_number-err"></small>
                                        </div>
                                    </div>

                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="confirm_routing_number">Confirm routing number</label><br>
                                            <input type="text" name="confirm_routing_number[]" class="form-control" id="confirm_routing_number"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="confirm_routing_number-err"></small>
                                        </div>
                                    </div>

                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="bank_name">Bank name</label><br>
                                            <input type="text" name="bank_name[]" class="form-control" id="bank_name"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="bank_name-err"></small>
                                        </div>
                                    </div>

                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="account_number">Account number</label><br>
                                            <input type="text" name="account_number[]" class="form-control" id="account_number"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="account_number-err"></small>
                                        </div>
                                    </div>

                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="account_number">Confirm account number</label><br>
                                            <input type="text" name="confirm_account_number[]" class="form-control" id="confirm_account_number"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="confirm_account_number-err"></small>
                                        </div>
                                    </div>

                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div  class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="deposit_distribution">Deposit distribution</label>
                                            <select class="form-control" id="deposit_distribution" name="deposit_distribution[]">
                                                <option selected value disabled>choose</option>
                                                <option value="full_net">Full Net</option>
                                                <option value="partial_amount">Partial $</option>
                                                <option value="partial_percentage">Partial %</option>
                                                <option value="remainder">Remainder</option>
                                            </select>
                                            <small class="text-danger err" id="deposit_distribution-err"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="deposite_amount">Amount</label><br>
                                            <input type="text" name="deposite_amount[]" class="form-control " id="deposite_amount"   placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="deposite_amount-err"></small>
                                        </div>
                                    </div>

                                </div>

                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="amount_nickname">Amount nickname</label><br>
                                            <input type="text" name="amount_nickname[]" class="form-control " id="amount_nickname"   placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="amount_nickname-err"></small>
                                        </div>
                                    </div>

                                </div></div>`;
    let templateAccountsOffshore = `<div class="form-card" ><button type="button" class="remove removeAccounts" style="margin: 0px 0px 0px 500px;">X</button>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="account_type">Account type</label><br>
                                            <select class="form-control" id="account_type" name="account_type[]">
                                                <option selected value disabled>choose</option>
                                                <option value="current_account">Current Account</option>
                                                <option value="saving_account">Saving Account</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="custom-control custom-checkbox mb-0" title="Select account for payment transfer (by default first account will be considered for payment transfer)" data-toggle="tooltip" data-placement="right">
                                                <input type="radio" class="custom-control-input" id="account_for_payment_transfer" onchange="updateValue(this)" checked value="1" name="account_for_payment_transfer[]">
                                                <input type="hidden" name="uncheckedValue[]" id="uncheckedValue" value="">
                                                <span class="custom-control-label">Account for payment transfer </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="account_first_name">First name</label><br>
                                            <input type="text" name="account_first_name[]" class="form-control" id="account_first_name"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="account_first_name-err"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="account_last_name">Last name</label><br>
                                            <input type="text" name="account_last_name[]" class="form-control" id="account_last_name"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="account_last_name-err"></small>
                                        </div>
                                    </div>

                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="home_address_on_bank">Home Address on Bank</label><br>
                                            <textarea class="form-control" id="home_address_on_bank" name="home_address_on_bank[]" rows="3"></textarea>
                                            <small class="text-danger err" id="home_address_on_bank-err"></small>
                                        </div>
                                    </div>

                                </div>
                                <div  style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="account_ssn_type">ID type </label><small class="text-danger">*</small>
                                            <select class="form-control" id="account_ssn_type" name="account_ssn_type[]">
                                                <option value="nic">NIC</option>
                                                <option value="adhaar_card">Adhaar Card</option>

                                            </select>
                                            <small class="text-danger err" id="account_ssn_type-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label id="bank_ssn_number_label" for="last_name">Number</label><small class="text-danger">*</small>
                                            <input type="text" name="bank_ssn_number[]" class="form-control" id="bank_ssn_number" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="bank_ssn_number-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label  for="iban_ifsc">IBAN/IFSC#</label><small class="text-danger">*</small>
                                            <input type="text" name="iban_ifsc[]" class="form-control" id="iban_ifsc" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="iban_ifsc-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="bank_name">Bank name</label><br>
                                            <input type="text" name="bank_name[]" class="form-control" id="bank_name"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="bank_name-err"></small>
                                        </div>
                                    </div>

                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="account_number">Account number</label><br>
                                            <input type="text" name="account_number[]" class="form-control" id="account_number"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="account_number-err"></small>
                                        </div>
                                    </div>

                                </div>

                                </div>`;


    $("#addEarnings").on("click", ()=>{
        $("#itemsEarnings").append(templateEarnings);
    })
    $("body").on("click", ".remove", (e)=>{
        $(e.target).parent("div").remove();
    })
    $("#addDeductions").on("click", ()=>{
        $("#itemsDeductions").append(templateDeductions);
    })
    $("body").on("click", ".removeDeductions", (e)=>{
        $(e.target).parent("div").remove();
    })
    var numItems=0;
    $("#addAccounts").on("click", ()=>{

        if(numItems>3)
        {
            alert("You can add only 4 accounts");
            return false;
        }
        if($('#addAccounts').attr('value')=='addAccounts')
        {
            $("#itemsAccounts").append(templateAccounts);
        }
        else if($('#addAccounts').attr('value')=='addAccountsOffshore')
        {
            $("#itemsAccounts").append(templateAccountsOffshore);
        }
        numItems = numItems+1;

    })
    $("body").on("click", ".removeAccounts", (e)=>{
        numItems = numItems-1;
        $(e.target).parent("div").remove();
    })

    $("#sickpto").on("click", ()=>{
        $("#sickplanw2row").remove();
        $("#sickplanallrow").remove();
        $("#assignPlanTablebody").append(templateSickPlan);
    })
    $("#unlimitedpto").on("click", ()=>{
        $("#sickplanw2row").remove();
        $("#sickplanallrow").remove();
        $("#assignPlanTablebody").append(templateSickPlanUnlimited);
    })
    $(function () {      $('#add_new_message_on_paystub').hide(); }); $( "#checkbox_add_new_message_on_paystub" ).on('click', function() {    $( "#add_new_message_on_paystub" ).toggle(); });
$uploadCrop = $('#avatar-preview').croppie({
    enableExif: true,
    viewport: {
        width: 312,
        height: 312,
        type: 'circle'
    },
    boundary: {
        width: 320,
        height: 320
    },
});

$model = $("#AvatarModel");


$(document).ready(function($) {
  $("#schedule_id,#position_id").select2();

  $('#birthdate').datetimepicker({
    format: 'LL'
  });

  $("#createEmployee").submit(function(event){
      $(".errorMessageDiv").css('display','block');
      $(".errorMessageDiv").css('opacity','1');
      $('#processingH2').css('display','block');
      $('#backtoPreviousScreen').css('display','none');
      $('#showErrors').css('display','none');
      setTimeout(function() {
          createForm("#createEmployee");
      }, 2000)
    event.preventDefault();

  });

  $('#avatar').on('change', function () {
    var reader = new FileReader();
    reader.onload = function (e) {
      $uploadCrop.croppie('bind', {
        url: e.target.result
      })
    }
    reader.readAsDataURL(this.files[0]);
    $model.modal('show');
  });

  //crop and save image
  $('#crop-nd-save').on('click', function (ev) {
    $uploadCrop.croppie('result', {
      type: 'canvas',
      size: 'viewport',
      circle:false
    }).then(function (resp) {
      $.ajax({
        url: "{{ route('admin.storeMediaBase64') }}",
        type: "POST",
        data: {"file":resp},
        headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        beforeSend:function(){
          $("button").prop('disabled',true);
        },
        success: function (response) {
          $('form#createEmployee').append('<input type="hidden" name="media" value="' + response.name + '">');
          $("#avatar-profile").prop('src', response.profileUrl); // avatar profile show
          $("#remove-avatar-profile").prop('href', response.removeProfileUrl);//remove button
          $("#add-avatar-div").addClass('hidden');
          $("#show-avatar-div").removeClass('hidden');
          $model.modal('hide'); // model close
        },
        complete:function(){
          $("button").prop('disabled',false);
        }
      });
    });
  });

  //remove current saved image
  $("#remove-avatar-profile").on('click',function(e){
    e.preventDefault();
    var fireUrl = $(this).prop('href');
    $.ajax({
        url: fireUrl,
        type: "POST",
        headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        beforeSend:function(){
          $("button").prop('disabled',true);
        },
        success: function (response) {
          $('<input type="hidden" name="media">').remove();
          $("#show-avatar-div").addClass('hidden');
          $("#add-avatar-div").removeClass('hidden');
        },
        complete:function(){
          $("button").prop('disabled',false);
        }
      });
  });

});

$(document).ready(function(){

    $('#birthdate').datetimepicker({
        format: 'LL'
    });
    $('#hire_date').datetimepicker({
        format: 'LL'
    });
    $('#terminatin_date').datetimepicker({
        format: 'LL'
    });
    $('#last_day_worked').datetimepicker({
        format: 'LL'
    });

    $('#hire_date_status').on('change', function() {
        if(this.value=='terminated')
        {
            $('#terminationDiv').show();
        }
        else
        {
            $('#terminationDiv').hide();
        }
    });


    var current_fs, next_fs, previous_fs; //fieldsets
    var opacity;
    function validateFormFields(currentDiv)
    {
        if(currentDiv=='employee_info_next')
        {
            var tenant_id = $("#tenant_id").val();
            var first_name = $("#first_name").val();
            var last_name = $("#last_name").val();
            var middle_name = $("#middle_name").val();
            var gender = $("#gender").val();
            var ssn = $("#ssn").val();
            var birthdate = $("#birthdate").val();
            var pfirst_name = $("#pfirst_name").val();
            var pmiddle_name = $("#pmiddle_name").val();
            var plast_name = $("#plast_name").val();
            var pgender = $("#pgender").val();

            if($.trim(pfirst_name).length<=0)
            {
                $("#pfirst_name").val(first_name);
            }
            if($.trim(pmiddle_name).length<=0)
            {
                $("#pmiddle_name").val(middle_name);
            }
            if($.trim(plast_name).length<=0)
            {
                $("#plast_name").val(last_name);
            }
            if($.trim(pgender).length<=0)
            {
                $("#pgender").val(gender);
            }
            var homeaddress = $("#homeaddress").val();
            var fulladdress = $("#fulladdress").val();
            var city = $("#city").val();
            var state = $("#state").val();
            $("#tenant_id-err").html('');
            $("#first_name-err").html('');
            $("#last_name-err").html('');
            $("#gender-err").html('');
            $("#ssn-err").html('');
            $("#birthdate-err").html('');
            $("#homeaddress-err").html('');
            $("#fulladdress-err").html('');
            $("#city-err").html('');
            $("#state-err").html('');
            if($.trim(tenant_id).length<=0){
                $("#tenant_id-err").focus();
                $("#tenant_id-err").html('Customer is required');
                return;
            }
            if($.trim(first_name).length<=0){
                $("#first_name-err").focus();
                $("#first_name-err").html('First name is required');
                return;
            }
            if($.trim(last_name).length<=0){
                $("#last_name-err").focus();
                $("#last_name-err").html('Last name is required');
                return;
            }
            if($.trim(gender).length<=0){
                $("#gender-err").focus();
                $("#gender-err").html('Gender is required');
                return;
            }
            if($.trim(ssn).length<=0){
                $("#ssn-err").focus();
                $("#ssn-err").html('This field is required');
                return;
            }
            if($.trim(birthdate).length<=0){
                $("#birthdate-err").focus();
                $("#birthdate-err").html('DOB is required');
                return;
            }
            if($.trim(homeaddress).length<=0){
                $("#homeaddress-err").focus();
                $("#homeaddress-err").html('Address is required');
                return;
            }
            /*if($.trim(fulladdress).length<=0){
                $("#fulladdress-err").focus();
                $("#fulladdress-err").html('Full Address is required');
                return;
            }*/
            if($.trim(city).length<=0){
                $("#city-err").focus();
                $("#city-err").html('City is required');
                return;
            }
            if($.trim(state).length<=0){
                $("#state-err").focus();
                $("#state-err").html('State is required');
                return;
            }
        }
        else if(currentDiv=='contact_info_next')
        {
            var phone = $("#phone").val();
            var email = $("#email").val();
            if($.trim(phone).length<=0){
                $("#phone-err").focus();
                $("#phone-err").html('Phone number is required');
                return;
            }
            if($.trim(email).length<=0){
                $("#email-err").focus();
                $("#email-err").html('Email is required');
                return;
            }
        }
        else if(currentDiv=='tax_info_next')
        {
            var whichw4employee = $("#whichw4employee").val();
            var exemptwithholding = $("#exemptwithholding").val();
            var isthisemployeeexempt = $("#isthisemployeeexempt").val();
            var statewheretheremployeelives = $("#statewheretheremployeelives").val();
            var isemployeeexemptfromstatetaxes = $("#isemployeeexemptfromstatetaxes").val();
            $("#whichw4employee-err").html('');
            $("#exemptwithholding-err").html('');
            $("#isthisemployeeexempt-err").html('');
            $("#statewheretheremployeelives-err").html('');
            $("#isemployeeexemptfromstatetaxes-err").html('');
            if($.trim(whichw4employee).length<=0){
                $("#whichw4employee-err").focus();
                $("#whichw4employee-err").html('W-4 type is required');
            }
            if($.trim(exemptwithholding).length<=0){
                $("#exemptwithholding-err").focus();
                $("#exemptwithholding-err").html('Exempt is required');
                return;
            }
            if($.trim(isthisemployeeexempt).length<=0){
                $("#isthisemployeeexempt-err").focus();
                $("#isthisemployeeexempt-err").html('Fedral Exempt is required');
                return;
            }
            if($.trim(statewheretheremployeelives).length<=0){
                $("#statewheretheremployeelives-err").focus();
                $("#statewheretheremployeelives-err").html('State where employee lives is required');
                return;
            }
            if($.trim(isemployeeexemptfromstatetaxes).length<=0){
                $("#isemployeeexemptfromstatetaxes-err").focus();
                $("#isemployeeexemptfromstatetaxes-err").html('Exempt from Texas state  is required');
                return;
            }
        }
        else if(currentDiv=='employment_info_next') {
            var work_email = $("#work_email").val();
            var hire_date = $("#hire_date").val();
            var hire_date_status = $("#hire_date_status").val();
            var statutory_employee = $("#statutory_employee").val();
            var terminatin_date = $("#terminatin_date").val();
            var schedule_id = $("#schedule_id").val();
            var position_id = $("#position_id").val();
            $("#work_email-err").html('');
            $("#hire_date-err").html('');
            $("#hire_date_status-err").html('');
            $("#terminatin_date-err").html('');
            $("#statutory_employee-err").html('');
            if($.trim(work_email).length<=0){
                $("#work_email-err").focus();
                $("#work_email-err").html('Work email is required');
                return;
            }
            else if(IsEmail(work_email)==false){
                $("#work_email-err").focus();
                $("#work_email-err").html('This email is not valid');
                return;
            }
            if($.trim(hire_date).length<=0){
                $("#hire_date-err").focus();
                $("#hire_date-err").html('Hire date is required');
                return;
            }
            if($.trim(hire_date_status).length<=0){
                $("#hire_date_status-err").focus();
                $("#hire_date_status-err").html('Status is required');
                return;
            }
            else
            {
                if(hire_date_status=='terminated')
                {
                    if($.trim(terminatin_date).length<=0){
                        $("#terminatin_date-err").focus();
                        $("#terminatin_date-err").html('Termination date is required');
                        return;
                    }
                }
            }
            /*if($.trim(statutory_employee).length<=0){
                $("#statutory_employee-err").focus();
                $("#statutory_employee-err").html('Statutory employee is required');
                return;
            }*/
            if($.trim(schedule_id).length<=0){
                $("#schedule_id-err").focus();
                $("#schedule_id-err").html('Schedule is required');
                return;
            }
            if($.trim(position_id).length<=0){
                $("#position_id-err").focus();
                $("#position_id-err").html('Position is required');
                return;
            }
        }
        else if(currentDiv=='payroll_info_next') {
            var pay_type = $("#pay_type").val();
            var basis_of_pay = $("#basis_of_pay").val();
            var pay_schedule = $("#pay_schedule").val();
            var standard_hours_per_day_period = $("#standard_hours_per_day_period").val();
            var employment_type = $("#employment_type").val();
            var hourly_pay_rate = $("#hourly_pay_rate").val();
            var semi_monthly_annual_salary = $("#semi_monthly_annual_salary").val();
            var semi_monthly_annual_salary_hourly_rate = $("#semi_monthly_annual_salary_hourly_rate").val();
            $("#pay_type-err").html('');
            $("#basis_of_pay-err").html('');
            $("#pay_schedule-err").html('');
            $("#standard_hours_per_day_period-err").html('');
            $("#employment_type-err").html('');
            $("#hourly_pay_rate-err").html('');
            $("#semi_monthly_annual_salary-err").html('');
            $("#semi_monthly_annual_salary_hourly_rate-err").html('');
            if($.trim(pay_type).length<=0){
                $("#pay_type-err").focus();
                $("#pay_type-err").html('Pay type is required');
                return;
            }
            /*if($.trim(basis_of_pay).length<=0){
                $("#basis_of_pay-err-err").focus();
                $("#basis_of_pay-err").html('Basis of type is required');
                return;
            }
            if($.trim(pay_schedule).length<=0){
                $("#pay_schedule-err").focus();
                $("#pay_schedule-err").html('Pay schedule is required');
                return;
            }
            if($.trim(standard_hours_per_day_period).length<=0){
                $("#standard_hours_per_day_period-err").focus();
                $("#standard_hours_per_day_period-err").html('Standard hours per day is required');
                return;
            }*/
            if($.trim(employment_type).length<=0){
                $("#employment_type-err").focus();
                $("#employment_type-err").html('Employment type is required');
                return;
            }
            /*if($.trim(hourly_pay_rate).length<=0){
                $("#hourly_pay_rate-err").focus();
                $("#hourly_pay_rate-err").html('Hourly pay rate is required');
                return;
            }*/
            if(pay_type=='salary' && $.trim(semi_monthly_annual_salary).length<=0){
                $("#semi_monthly_annual_salary-err").focus();
                $("#semi_monthly_annual_salary-err").html('Salary is required');
                return;
            }
            if(pay_type=='hourly' && $.trim(semi_monthly_annual_salary_hourly_rate).length<=0){
                $("#semi_monthly_annual_salary_hourly_rate-err").focus();
                $("#semi_monthly_annual_salary_hourly_rate-err").html('Hourly rate is required');
                return;
            }

        }
        else if(currentDiv=='contractor_info_next'){
            var ctenant_id = $("#ctenant_id").val();
            var cemployment_type = $("#cemployment_type").val();
            var contractor_first_name = $("#contractor_first_name").val();
            var contractor_last_name = $("#contractor_last_name").val();
            var contractor_ssn = $("#contractor_ssn").val();
            var contractor_homeaddress = $("#contractor_homeaddress").val();
            var contractor_fulladdress = $("#contractor_fulladdress").val();
            var contractor_city = $("#contractor_city").val();
            var contractor_state = $("#contractor_state").val();
            var contractor_phone_number = $("#contractor_phone_number").val();
            var contractor_email = $("#contractor_email").val();
            $("#ctenant_id-err").html('');
            $("#cemployment_type-err").html('');
            $("#contractor_first_name-err").html('');
            $("#contractor_ssn-err").html('');
            $("#contractor_email-err").html('');
            $("#contractor_fulladdress-err").html('');
            $("#contractor_state-err").html('');
            $("#contractor_city-err").html('');
            $("#contractor_homeaddress-err").html('');
            if($.trim(ctenant_id).length<=0){
                $("#ctenant_id-err").focus();
                $("#ctenant_id-err").html('Customer is required');
                return;
            }
            if($.trim(cemployment_type).length<=0){
                $("#cemployment_type-err").focus();
                $("#cemployment_type-err").html('Employment type is required');
                return;
            }
            if($.trim(contractor_first_name).length<=0){
                $("#contractor_first_name-err").focus();
                $("#contractor_first_name-err").html('First name is required');
                return;
            }
            if($.trim(contractor_last_name).length<=0){
                $("#contractor_last_name-err").focus();
                $("#contractor_last_name-err").html('Last name is required');
                return;
            }
            if($.trim(contractor_ssn).length<=0){
                $("#contractor_ssn-err").focus();
                $("#contractor_ssn-err").html('SSN is required');
                return;
            }
            if($.trim(contractor_homeaddress).length<=0){
                $("#contractor_homeaddress-err").focus();
                $("#contractor_homeaddress-err").html('Home Address is required');
                return;
            }
            /*if($.trim(contractor_fulladdress).length<=0){
                $("#contractor_fulladdress-err").focus();
                $("#contractor_fulladdress-err").html('Full Address is required');
                return;
            }*/
            if($.trim(contractor_city).length<=0){
                $("#contractor_city-err").focus();
                $("#contractor_city-err").html('City is required');
                return;
            }
            if($.trim(contractor_state).length<=0){
                $("#contractor_state-err").focus();
                $("#contractor_state-err").html('State is required');
                return;
            }
            if($.trim(contractor_phone_number).length<=0){
                $("#contractor_phone_number-err").focus();
                $("#contractor_phone_number-err").html('Phone is required');
                return;
            }
            if($.trim(contractor_email).length<=0){
                $("#contractor_email-err").focus();
                $("#contractor_email-err").html('Email is required');
                return;
            }
        }
        return true
    }

    $(".next").click(function(){

        current_fs = $(this).parent();
        next_fs = $(this).parent().next();
        var currentDiv = $(this).attr('id');
        if(!validateFormFields(currentDiv))
        {
            return;
        }


        //Add Class Active
        $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

        //show the next fieldset
        next_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now) {
                // for making fielset appear animation
                opacity = 1 - now;

                current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                });
                next_fs.css({'opacity': opacity});
            },
            duration: 600
        });
    });

    $(".previous").click(function(){


        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();

        //Remove class active
        $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

        //show the previous fieldset
        previous_fs.show();

        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now) {
                // for making fielset appear animation
                opacity = 1 - now;

                current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                });
                previous_fs.css({'opacity': opacity});
            },
            duration: 600
        });
    });
    $('#backtoPreviousScreen').click(function(){
        $('#letsWrapupDiv').css('display','block')
        $('#letsWrapupDiv').css('opacity','1')
        $('#letsWrapupDiv').css('position','relative')
        $('#fsuccessDiv').css('display','none')
        $('#processingH2').css('display','block')

    });

    $('.radio-group .radio').click(function(){
        $(this).parent().find('.radio').removeClass('selected');
        $(this).addClass('selected');
    });

    $(".submit").click(function(){
        return false;
    })

});


$("#add_an_employeew2").on('click',function(e){
    $('.beforeWrapitUp').attr('onClick', 'javascript: wrapitUP();');
    $('.beforeWrapitUpType2').attr('onClick', 'javascript: void(0);');
    employeeType=1;
    $('#earningsandDeductionssDiv').css("display","block");
    $('#employee_type_form').val('1');
    $('#contractor_info').remove();
    $('#fcontractor_info').remove();
    $('#employee_info').addClass('active');
    $('#femployee_info').addClass('active');
    $('#contractor_info').removeClass('active');
    $('#fcontractor_info').removeClass('active');
    $('#ssn_typeDiv').hide();
    $('#marital_statusDiv').hide();
    $('#preferred_info_div').show();
    $('#termination_extra_paramsDiv').show();
    $('#company_extra_paramsDiv').show();
    $('#seasonal_employeeDiv').show();
    $('#hourly_par_rateDiv').show();
    $('#salary_type_checkboxDiv').show();
    $('#pay_stubDiv').show();
    $('.addAccountsBtn').attr("value","addAccounts");
    selectEmployeeType();
});
$("#add_an_employeew1099").on('click',function(e){
    $('.beforeWrapitUpType2').attr('onClick', 'javascript: wrapitUP();');
    $('.beforeWrapitUp').attr('onClick', 'javascript: void(0);');
    employeeType=2;
    $('#earningsandDeductionssDiv').css("display","block");
    $('#employee_info').remove();
    $('#femployee_info').remove();
    $('#contact_info').remove();
    $('#fcontact_info').remove();
    $('#tax_info').remove();
    $('#ftax_info').remove();
    $('#employee_type_form').val('2');
    $('#paid_timeoff').remove();
    $('#fpaid_timeoff').remove();

    $('#employee_info').removeClass('active');
    $('#femployee_info').removeClass('active');
    $('#contractor_info').addClass('active');
    $('#fcontractor_info').addClass('active');
    $('.addAccountsBtn').attr("value","addAccounts");
    $('#termination_extra_paramsDiv').show();
    $('#company_extra_paramsDiv').show();
    $('#seasonal_employeeDiv').show();
    $('#hourly_par_rateDiv').show();
    $('#salary_type_checkboxDiv').show();
    $('#pay_stubDiv').show();
    selectEmployeeType();
});
$("#add_an_employeewoffshore").on('click',function(e){
    $('.beforeWrapitUp').attr('onClick', 'javascript: wrapitUP();');
    $('.beforeWrapitUpType2').attr('onClick', 'javascript: void(0);');
    employeeType=3;
    $('#earningsandDeductionssDiv').css("display","none");
    $('#contractor_info').remove();
    $('#fcontractor_info').remove();
    $('#employee_type_form').val('3');

    $('#employee_info').addClass('active');
    $('#femployee_info').addClass('active');
    $('#contractor_info').removeClass('active');
    $('#fcontractor_info').removeClass('active');
    $('#ftax_info').remove();
    $('#tax_info').remove();
    $('#tax_info').removeClass('active');
    $('#ftax_info').removeClass('active');
    $('#fearnings_and_deductions').remove();
    $('#earnings_and_deductions').remove();
    $('#earnings_and_deductions').removeClass('active');
    $('#fearnings_and_deductions').removeClass('active');

    $('#ssn_typeDiv').show();
    $('#marital_statusDiv').show();
    $('#preferred_info_div').hide();
    $('.addAccountsBtn').attr("value","addAccountsOffshore");
    $('#termination_extra_paramsDiv').hide();
    $('#company_extra_paramsDiv').hide();
    $('#seasonal_employeeDiv').hide();
    $('#hourly_par_rateDiv').hide();
    $('#salary_type_checkboxDiv').hide();
    $('#pay_stubDiv').hide();
    changeSSNLabel();


    setTimeout(function() {
        $("#peopleDiv").hide();
        $("#employeeInfoDiv").show();
    }, 1000); // <-- time in milliseconds
});

var onboardType=0;
$("#self_onboard").on('click',function(e){
    onboardType=1;
    selectOnboardType();
});
$("#system_onboard").on('click',function(e){
    onboardType=2;

    selectOnboardType();
});
function selectEmployeeType(){
    if(employeeType==1 || employeeType==2 || employeeType==3 ){
        setTimeout(function() {
            $("#peopleDiv").hide();
            $("#ownPersonalDiv").show();
        }, 1000); // <-- time in milliseconds

    }
}
function selectOnboardType(){
    if(onboardType==1 || onboardType==2 || onboardType==3){
        setTimeout(function() {
            $("#ownPersonalDiv").hide();
            $("#employeeInfoDiv").show();
        }, 1000); // <-- time in milliseconds

    }
}

    function wrapitUP(){
        if(employeeType==1 || employeeType==3)
        {
            var full_name  = $("#first_name").val()+' '+$("#middle_name").val()+' '+$("#last_name").val();
            var birthdate  = $("#birthdate").val();
            var ssn  = $("#ssn").val();
        }
        else
        {
            var full_name  = $("#contractor_first_name").val()+' '+$("#contractor_middle_name").val()+' '+$("#contractor_last_name").val();
            var birthdate  = 'N/A';
            var ssn  = $("#contractor_ssn").val();
        }

        $("#pViewName").html(full_name);
        $("#pViewDob").html(birthdate);
        $("#pViewSsn").html(ssn);

        var fedraltax  = $("#whichw4employee").val();
        if(fedraltax)
        {
            $("#pViewFedraltax").html('Complete');
        }
        else
        {
            $("#pViewFedraltax").html('In-Complete');
        }
        var statetax  = $("#statewheretheremployeelives").val();
        if(statetax)
        {
            $("#pViewStatetax").html('Complete');
        }
        else
        {
            $("#pViewStatetax").html('In-Complete');
        }


        var hire_date  = $("#hire_date").val();
        var comapny_paid_pension  = $("#comapny_paid_pension").val();
        var hire_date_status  = $("#hire_date_status").val();
        var statutory_employee  = $("#statutory_employee").val();
        $('#pViewHiredate').html(hire_date);
        $('#pViewEmploymentstatus').html(hire_date_status);
        if($.trim(comapny_paid_pension).length<=0)
        {
            $('#pViewCompanypaidpensionlbl').css("display","none");
        }
        else {
            $('#pViewCompanypaidpensionlbl').css("display","block");
            $('#pViewCompanypaidpension').html(comapny_paid_pension);
        }
        if($.trim(statutory_employee).length<=0)
        {
            $('#pViewStatutoryemployeelbl').css("display","none");
        }
        else {
            $('#pViewStatutoryemployeelbl').css("display","block");
            $('#pViewStatutoryemployee').html(statutory_employee);
        }


        var pay_type = $("#pay_type").val();
        var basis_of_pay = $("#basis_of_pay").val();
        var pay_schedule = $("#pay_schedule").val();
        var employment_type = $("#employment_type").val();
        var seasonal_employee = $("#seasonal_employee").val();
        var add_new_message_on_paystub = $("#add_new_message_on_paystub").val();
        $('#pViewPaytype').html(pay_type);
        $('#pViewAnnualsalary').html('');
        $('#pViewBasisofpay').html(basis_of_pay);
        $('#pViewPayschedule').html(pay_schedule);
        $('#pViewEmploymenttype').html(employment_type);
        if($.trim(seasonal_employee).length<=0)
        {
            $('#pViewSeasonalemployeelbl').css("display","none");
        }
        else {
            $('#pViewSeasonalemployeelbl').css("display","block");
            $('#pViewSeasonalemployee').html(seasonal_employee);
        }
        if($.trim(add_new_message_on_paystub).length<=0)
        {
            $('#pViewCheckstubmessagelbl').css("display","none");
        }
        else {
            $('#pViewCheckstubmessagelbl').css("display","block");
            $('#pViewCheckstubmessage').html(add_new_message_on_paystub);
        }


        var earnings = [];
        var fields = document.getElementsByName("earnings[]");
        for(var i = 0; i < fields.length; i++) {
            earnings.push(fields[i].value);
        }
        var earningsValues = earnings.join(", ")
        $('#pViewEarningname').html(earningsValues);


        var deductions = [];
        var fieldsDeductions = document.getElementsByName("deductions[]");
        for(var i = 0; i < fieldsDeductions.length; i++) {
            deductions.push(fieldsDeductions[i].value);
        }
        var deductionsValues = deductions.join(", ")
        $('#pViewDeductionname').html(deductionsValues);
    }



    google.maps.event.addDomListener(window, 'load', initialize);

    function initialize() {
        var input = document.getElementById('homeaddress');
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            console.log(place);
            //$('#latitude').val(place.geometry['location'].lat());
            //$('#longitude').val(place.geometry['location'].lng());

            //$("#latitudeArea").removeClass("d-none");
            //$("#longtitudeArea").removeClass("d-none");
        });
    }

    $("#currentw4_id").click(function(){
        $('#whichw4employee').val('currentw4')
    });
    $("#pcurrentw4_id").click(function(){
        $('#whichw4employee').val('previousw4')
    });


    if (!$("#semi_monthly_salary_type").is(":checked") || !$("#annual_salary").is(":checked")) {
        $('#semi_monthly_salary_type').prop('checked', true);
        // do something if the checkbox is NOT checked
    }
    $('#ssn_type').on('change', function () {
        changeSSNLabel();
    });
    function changeSSNLabel(){
        var ssn_type = $('#ssn_type').val();
        if(ssn_type == 'nic'){
            $('#ssnlabel').html('NIC Card Number');
        }else if(ssn_type == 'adhaar_card'){
            $('#ssnlabel').html('Adhaar Card Number');
        }
    }
    function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if(!regex.test(email)) {
            return false;
        }else{
            return true;
        }
    }
    function updateValue(radioButton) {
        var radios = document.getElementsByName("account_for_payment_transfer[]");
        var uncheckedValue = document.getElementsByName("uncheckedValue[]");
        for (var i = 0; i < radios.length; i++) {
            if (radios[i] === radioButton) {
                radios[i].value = "1";
                uncheckedValue[i].value = "1";
            } else {
                radios[i].value = "0";
                uncheckedValue[i].value = "0";
            }
        }
    }
    $('#statewheretheremployeelives').on('change', function () {
        showStateTexes(this);
    });
    function showStateTexes(element){
        var currentState = $(element).val();
        $('#state_taxes_Div').html('');
        $.ajax({
            url: "{{ url('get-state-deduction') }}",
            type: "POST",
            data: {"currentState":currentState},
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            beforeSend:function(){
                //$("button").prop('disabled',true);
            },
            success: function (response) {
                if(response.deductions)
                {
                    var deductions = response.deductions;
                    var htmlDiv='';
                    $.each(deductions, function(key,valueObj){
                        console.log(key + "/" + valueObj.name );
                        var valueLabel  = valueObj.amount+' Amount ';
                        if(valueObj.value_type=='1')
                        {
                            valueLabel  = valueObj.amount+' Percentage (%)';
                        }

                        htmlDiv += '<div class="col-md-12 col-lg-12 col-sm-12">'+
                            '<div class="form-group">'+
                                '<label class="custom-control custom-checkbox mb-0" >'+
                                '<input type="checkbox" checked class="custom-control-input" value="'+valueObj.id+'" id="deductions_amount" name="emp_deductions_amount[]">'+
                                '<span class="custom-control-label">'+valueObj.name+' for State '+valueObj.state+' Deduction value '+valueLabel+'</span>'+
                                '</label>'+
                    '</div>'+
                                '</div>';
                    });
                    $('#state_taxes_Div').html(htmlDiv);
                }

            },
            complete:function(){
                //$("button").prop('disabled',false);
            }
        });
/**/
    }
</script>

@endsection
