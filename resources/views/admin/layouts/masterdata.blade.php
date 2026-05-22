
<!--- Country -->
<div class="modal fade" id="newcountrymodel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Country in List</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <form id="create_new_country" data-parsley-validate="true" class="custom-form">
      <div class="modal-body">
          @csrf
          <input type="hidden" name="json_action" value="1">
          <div class="">
              <div class="card-body">
                  <div class="row">
                      <div class="col-lg-6 mb-4">
                          <div class="form-group">
                              <label>Name<span style="color:red;">*<span></span></span></label>
                              <input type="text" class="form-control" data-jqv-maxlength="100" id="name" name="name" value="{{old('name')}}" required
                              data-parsley-required-message="Enter Name"
                              pattern="^[a-zA-Z0-9\s]+$"
                              >
                              <smal class="float-end me-2 text-danger" id="country_already_error"></smal>
                          </div>
                      </div>
                      <div class="col-lg-6 mb-4 d-none">
                          <div class="form-group">
                              <label>Name (ar) <span style="color:red;">*<span></span></span></label>
                              <input type="text" class="form-control" name="name_ar" value="{{old('name_ar')}}" dir="rtl"
                              pattern="^[a-zA-Z0-9\s]+$"
                              >
                          </div>
                      </div>
                      <div class="col-lg-6 mb-4">
                          <div class="form-group">
                              <label>Prefix<span style="color:red;">*<span></span></span></label>
                              <input type="text" class="form-control" name="prefix" value="{{old('prefix')}}" pattern="^[a-zA-Z0-9\s]+$">
                          </div>
                      </div>
                      <div class="col-lg-6 mb-4">
                          <div class="form-group">
                              <label>Dial Code<span style="color:red;">*<span></span></span></label>
                              <input type="text" class="form-control" name="dial_code" value="{{old('dial_code')}}" pattern="^[a-zA-Z0-9\s]+$">
                          </div>
                      </div>

                      <div class="col-lg-6 mb-4">
                          <div class="form-group">
                              <label>Status <span style="color:red;"><span></span></span></label>
                              <select name="active" class="form-control status-selection" required>
                                <option value="1">Active</option>
                              </select>
                          </div>
                      </div>
                  </div>


              </div>

          </div>


          <div class="modal-footer">
            <button type="button" class="reset-form btn btn-info waves-effect waves-light me-2">Clear</button>
              <button type="submit" id="save_country_btn" class="btn btn-primary waves-effect waves-light me-2">Save Now</button>
          </div>
  </form>
</div>
    </div>
  </div>
</div>

<!--- Country Origin-->
<div class="modal fade" id="newcountryOriginmodel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Country in List</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <form id="create_new_country_origin" data-parsley-validate="true" class="custom-form">
          @csrf
          <input type="hidden" name="json_action" value="1">
          <div class="">
              <div class="card-body">
                  <div class="row">
                      <div class="col-lg-6 mb-4">
                          <div class="form-group">
                              <label>Name<span style="color:red;">*<span></span></span></label>
                              <input type="text" class="form-control" data-jqv-maxlength="100" id="name_origin" name="name" value="{{old('name')}}" required
                              data-parsley-required-message="Enter Name"
                              pattern="^[a-zA-Z0-9\s]+$"
                              >
                              <smal class="float-end me-2 text-danger" id="country_already_error_origin"></smal>
                          </div>
                      </div>
                      <div class="col-lg-6 mb-4 d-none">
                          <div class="form-group">
                              <label>Name (ar) <span style="color:red;">*<span></span></span></label>
                              <input type="text" class="form-control" data-jqv-maxlength="100" name="name_ar" value="{{old('name_ar')}}"
                              data-parsley-required-message="Enter Name" dir="rtl"
                              pattern="^[a-zA-Z0-9\s]+$"
                              >
                          </div>
                      </div>
                      <div class="col-lg-6 mb-4">
                          <div class="form-group">
                              <label>Prefix<span style="color:red;">*<span></span></span></label>
                              <input type="text" class="form-control" data-jqv-maxlength="100" name="prefix" value="{{old('prefix')}}" required
                              data-parsley-required-message="Enter Prefix" pattern="^[a-zA-Z0-9\s]+$">
                          </div>
                      </div>
                      <div class="col-lg-6 mb-4">
                          <div class="form-group">
                              <label>Dial Code<span style="color:red;">*<span></span></span></label>
                              <input type="text" class="form-control" data-jqv-maxlength="100" name="dial_code" value="{{old('dial_code')}}" required
                              data-parsley-required-message="Enter Dial Code" pattern="^[a-zA-Z0-9\s]+$">
                          </div>
                      </div>

                      <div class="col-lg-6 mb-4">
                          <div class="form-group">
                              <label>Status <span style="color:red;"><span></span></span></label>
                              <select name="active" class="form-control status-selection" required>
                                <option value="1">Active</option>
                              </select>
                          </div>
                      </div>
                  </div>


              </div>

          </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="reset-form btn btn-info waves-effect waves-light" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="save_country_btn_origin" class="btn btn-primary waves-effect waves-light me-2">Save Now</button>
      </div>
  </form>
    </div>
  </div>
</div>

<!--- EMirates -->
<div class="modal fade" id="newemiratemodel" tabindex="-2" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add New City / Province</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form method="post" id="newemirate_form"   data-parsley-validate="true" class="custom-form">
        @csrf
        <input type="hidden" name="json_action" value="1">
        <div class="">
            <div class="card-body">

                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="form-group">
                            <label>Name (en)<span style="color:red;">*<span></span></span></label>
                            <input type="text" class="form-control" data-jqv-maxlength="100" name="name_en" value="{{old('name_en')}}" required
                            data-parsley-required-message="Enter Name"
                            pattern="^[a-zA-Z0-9\s]+$"
                            >
                            <smal class="float-end me-2 text-danger" id="emirate_already_error"></smal>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4 d-none">
                        <div class="form-group">
                            <label>Name (ar) <span style="color:red;">*<span></span></span></label>
                            <input type="text" class="form-control" dname="name_ar" value="{{old('name_ar')}}"
                            dir="rtl"
                            pattern="^[a-zA-Z0-9\s]+$"
                            >
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="form-group">
                            <label>Country <span style="color:red;">*<span></span></span></label>
                            <select name="country_id" id="country_id" required class="select2-single jqv-input" data-jqv-required="true" role="select2" data-parsley-message="Select Country" data-placeholder="Select Country">
                              <option value="">Select</option>
                              @if(isset($country_list))
                              @foreach($country_list as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="form-group">
                            <label>Status <span style="color:red;">*<span></span></span></label>
                            <select name="active" class="form-control status-selection">
                              <option value="1" selected="selected">Active</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 d-flex">
                      <button type="button" class="reset-form btn btn-info waves-effect waves-light me-2">Clear</button>
                        <button id="btn_save_emirate" class="btn btn-primary waves-effect waves-light me-2" type="submit"> Save Now</button>
                    </div>
                </div>
            </div>

        </div>
    </form>
      </div>
    </div>
  </div>
</div>

<!--- Area --->

<div class="modal fade" id="neweareamodel" tabindex="-3" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Area</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form method="post" id="newarea_form"   data-parsley-validate="true" class="custom-form">
        @csrf
        <input type="hidden" name="json_action" value="1">
        <div class="">
            <div class="card-body">

                  <div class="row">
                    <div class="col-sm-6 col-xs-12 mb-4">
                        <div class="form-group">
                            <label>Name (en)<span style="color:red;">*<span></span></span></label>
                            <input type="text" class="form-control" data-jqv-maxlength="100" name="name_en" value="{{old('name_en')}}" required
                            data-parsley-required-message="Enter Name" pattern="^[a-zA-Z0-9\s]+$">
                        </div>
                        <smal class="float-end me-2 text-danger" id="area_already_error"></smal>
                    </div>
                    <div class="col-sm-6 col-xs-12 mb-4 d-none">
                        <div class="form-group">
                            <label>Name (ar) <span style="color:red;">*<span></span></span></label>
                            <input type="text" class="form-control" name="name_ar" value="{{old('name_ar')}}"
                            data-parsley-message="Enter Name" dir="ltr" pattern="^[a-zA-Z0-9\s]+$">
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12 mb-4">
                        <div class="form-group">
                            <label>Country</label>
                            <select id="countryforarea" class="form-control" name="country_id" required>
                                <option value="">Select Country <span style="color:red;">*<span></span></span></option>
                                @if(isset($country_list))
                                @foreach($country_list as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12 mb-4">
                        <div class="form-group">
                            <label>City <span style="color:red;">*<span></span></span></label>
                            <select id="emirate_area" class="form-control" name="emirate_id" required>
                                <option value="">Select Emirate</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12 mb-4">
                        <div class="form-group">
                            <label>Status <span style="color:red;">*<span></span></span></label>
                            <select name="active" class="form-control status-selection" required>
                              <option  value="1" selected="selected">Active</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 d-flex">
                      <button type="button" class="reset-form btn btn-info waves-effect waves-light me-2">Clear</button>
                        <button id="btn_save_area" class="btn btn-primary waves-effect waves-light me-2" type="submit"> Save Now</button>
                    </div>
                </div>
            </div>

        </div>
    </form>
      </div>
    </div>
  </div>
</div>

<!-- language -->
<div class="modal fade" id="new_lang" tabindex="-4" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Language</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form method="post" id="newlang_form"   data-parsley-validate="true" class="custom-form">
        @csrf
        <input type="hidden" name="json_action" value="1">
        <div class="">
            <div class="card-body">

                  <div class="row">


                    <div class="col-lg-6 mb-4">
                      <label class="form-label" for="bs-validation-name">Title (en)<span class="text-danger">*</span> </label>
                      <input
                          type="text"
                          class="form-control jqv-input" data-jqv-required="true"
                          id="title_en"
                          name="title_en"
                          required
                          pattern="^[a-zA-Z0-9\s]+$"
                      />
                      <smal class="float-end me-2 text-danger" id="language_already_error"></smal>
                      </div>
                      <div class="col-lg-6 mb-4">
                      <label class="form-label" for="bs-validation-name">Title (ar) </label>
                      <input
                          type="text"
                          class="form-control jqv-input"
                          id="title_ar"
                          name="title_ar"
                          dir="rtl"
                          required
                          pattern="^[a-zA-Z0-9\s]+$"
                      />
                      </div>

                  <div class="col-lg-6 mb-4">
                      <label class="form-label">Status<span class="text-danger">*</span></label>
                      <select class="form-control jqv-input" data-jqv-required="true" name="status">
                        <option value="1" selected="selected">Active</option>
                      </select>
                  </div>


                </div>

                <div class="row">
                    <div class="col-12 d-flex">
                      <button type="button" class="reset-form btn btn-info waves-effect waves-light me-2">Clear</button>
                        <button id="btn_save_lang" class="btn btn-primary waves-effect waves-light me-2" type="submit"> Save Now</button>
                    </div>
                </div>
            </div>

        </div>
    </form>
      </div>
    </div>
  </div>
</div>
<!-- Departments -->

<div class="modal fade" id="new_dept" tabindex="-4" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Department</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form method="post" id="newdept_form"   data-parsley-validate="true" class="custom-form">
        @csrf
        <input type="hidden" name="json_action" value="1">

        <div class="">
            <div class="card-body">

                  <div class="row">


                    <div class="col-lg-6 mb-4">
                      <label class="form-label" for="bs-validation-name">Title (en)<span class="text-danger">*</span> </label>
                      <input
                          type="text"
                          class="form-control jqv-input" data-jqv-required="true"
                          id="title_en"
                          name="title_en"
                          required
                          pattern="^[a-zA-Z0-9\s]+$"
                      />
                      <smal class="float-end me-2 text-danger" id="department_already_error"></smal>
                      </div>
                      <div class="col-lg-6 mb-4">
                      <label class="form-label" for="bs-validation-name">Title (ar) </label>
                      <input
                          type="text"
                          class="form-control jqv-input"
                          id="title_ar"
                          name="title_ar"
                          dir="rtl"
                          pattern="^[a-zA-Z0-9\s]+$"

                      />
                      </div>

                  <div class="col-lg-6 mb-4">
                      <label class="form-label">Status<span class="text-danger">*</span></label>
                      <select class="form-control jqv-input" data-jqv-required="true" name="status" required>
                        <option value="1" selected="selected">Active</option>
                      </select>
                  </div>

                  <input type="hidden" name="new_hospital_id" id="new_hospital_id" required>
                  <small id="hospital_error" class="text-danger float-end me-2"></small>
                </div>

                <div class="row">
                    <div class="col-12 d-flex">
                      <button type="button" class="reset-form btn btn-info waves-effect waves-light me-2">Clear</button>
                        <button id="btn_save_dept" class="btn btn-primary waves-effect waves-light me-2" type="submit"> Save Now</button>
                    </div>
                </div>
            </div>

        </div>
    </form>
      </div>
    </div>
  </div>
</div>
<!-- Speciality -->
<div class="modal fade" id="new_special" tabindex="-4" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Speciality</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form method="post" id="newdspecial_form"  data-parsley-validate="true" class="custom-form">
        @csrf
        <input type="hidden" name="json_action" value="1">
        <div class="">
            <div class="card-body">

                  <div class="row">


                    <div class="col-lg-6 mb-4">
                      <label class="form-label" for="bs-validation-name">Title (en)<span class="text-danger">*</span> </label>
                      <input
                          type="text"
                          class="form-control jqv-input" data-jqv-required="true"
                          id="name_en"
                          name="name_en"
                          required
                      />
                      <smal class="float-end me-2 text-danger" id="special_already_error"></smal>
                      </div>
                      <div class="col-lg-6 mb-4">
                      <label class="form-label" for="bs-validation-name">Title (ar) </label>
                      <input
                          type="text"
                          class="form-control jqv-input"
                          id="name_ar"
                          name="name_ar"
                          dir="rtl"
                      />
                      </div>

                  <div class="col-lg-6 mb-4">
                      <label class="form-label">Status<span class="text-danger">*</span></label>
                      <select class="form-control jqv-input" data-jqv-required="true" name="active">
                        <option value="1" selected="selected">Active</option>
                      </select>
                  </div>


                </div>

                <div class="row">
                    <div class="col-12 d-flex">
                      <button type="button" class="reset-form btn btn-info waves-effect waves-light me-2">Clear</button>
                        <button id="btn_save_special" class="btn btn-primary waves-effect waves-light me-2" type="submit"> Save Now</button>
                    </div>
                </div>
            </div>

        </div>
    </form>
      </div>
    </div>
  </div>
</div>
<!-- Qualification -->
<div class="modal fade" id="new_education" tabindex="-4" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Qualification</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form method="post" id="newdeducation_form"  data-parsley-validate="true" class="custom-form">
        @csrf
        <input type="hidden" name="json_action" value="1">
        <div class="">
            <div class="card-body">

                  <div class="row">


                    <div class="col-lg-6 mb-4">
                      <label class="form-label" for="bs-validation-name">Title (en)<span class="text-danger">*</span> </label>
                      <input
                          type="text"
                          class="form-control jqv-input" data-jqv-required="true"
                          id="title_en"
                          name="title_en"
                           data-parsley-required-message="Enter Name"
                           required
                      />
                      <smal class="float-end me-2 text-danger" id="education_already_error"></smal>
                      </div>
                      <div class="col-lg-6 mb-4">
                      <label class="form-label" for="bs-validation-name">Title (ar) </label>
                      <input
                          type="text"
                          class="form-control jqv-input"
                          id="title_ar"
                          name="title_ar"
                          dir="rtl"
                          pattern="^[a-zA-Z0-9\s]+$"
                      />
                      </div>

                  <div class="col-lg-6 mb-4">
                      <label class="form-label">Status<span class="text-danger">*</span></label>
                      <select class="form-control jqv-input" data-jqv-required="true" name="status">
                        <option value="1" selected="selected">Active</option>
                      </select>
                  </div>


                </div>

                <div class="row">
                    <div class="col-12 d-flex">
                      <button type="button" class="reset-form btn btn-info waves-effect waves-light me-2">Clear</button>
                        <button id="btn_save_education" class="btn btn-primary waves-effect waves-light me-2" type="submit"> Save Now</button>
                    </div>
                </div>
            </div>

        </div>
    </form>
      </div>
    </div>
  </div>
</div>
<!-- Special Interest -->
<div class="modal fade" id="new_intereset" tabindex="-4" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Special Interest</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form method="post" id="newdeinterest_form"  data-parsley-validate="true" class="custom-form">
        @csrf
        <input type="hidden" name="json_action" value="1">
        <div class="">
            <div class="card-body">

                  <div class="row">


                    <div class="col-lg-6 mb-4">
                      <label class="form-label" for="bs-validation-name">Title (en)<span class="text-danger">*</span> </label>
                      <input
                          type="text"
                          class="form-control jqv-input" data-jqv-required="true"
                          id="title_en"
                          name="title_en"
                          required
                      />
                      <smal class="float-end me-2 text-danger" id="specialinterest_already_error"></smal>
                      </div>
                      <div class="col-lg-6 mb-4">
                      <label class="form-label" for="bs-validation-name">Title (ar) </label>
                      <input
                          type="text"
                          class="form-control jqv-input"
                          id="title_ar"
                          name="title_ar"
                          dir="rtl"
                      />
                      </div>

                  <div class="col-lg-6 mb-4">
                      <label class="form-label">Status<span class="text-danger">*</span></label>
                      <select class="form-control jqv-input" data-jqv-required="true" name="status">
                        <option value="1" selected="selected">Active</option>
                      </select>
                  </div>


                </div>

                <div class="row">
                    <div class="col-12 d-flex">
                      <button type="button" class="reset-form btn btn-info waves-effect waves-light me-2">Clear</button>
                        <button id="btn_save_interest" class="btn btn-primary waves-effect waves-light me-2" type="submit"> Save Now</button>
                    </div>
                </div>
            </div>

        </div>
    </form>
      </div>
    </div>
  </div>
</div>

<!-- Insurance -->
<div class="modal fade" id="new_insurance" tabindex="-4" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Insurance</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form method="post" id="newinsurance_form"   data-parsley-validate="true" class="custom-form">
        @csrf
        <input type="hidden" name="json_action" value="1">
        <div class="">
            <div class="card-body">

                  <div class="row">


                    <div class="col-lg-6 mb-4">
                      <label class="form-label" for="bs-validation-name">Title (en)<span class="text-danger">*</span> </label>
                      <input
                          type="text"
                          class="form-control jqv-input" data-jqv-required="true"
                          id="title_en"
                          name="title_en"
                          required
                          pattern="^[a-zA-Z0-9\s]+$"
                      />
                      <smal class="float-end me-2 text-danger" id="insurance_already_error"></smal>
                      </div>
                      <div class="col-lg-6 mb-4">
                      <label class="form-label" for="bs-validation-name">Title (ar) <span class="text-danger">*</span></label>
                      <input
                          type="text"
                          class="form-control jqv-input"
                          id="title_ar"
                          name="title_ar"
                          dir="rtl"
                          pattern="^[a-zA-Z0-9\s]+$"
                      />
                      </div>

                  <div class="col-lg-6 mb-4">
                      <label class="form-label">Status<span class="text-danger">*</span></label>
                      <select class="form-control jqv-input" data-jqv-required="true" name="status" required>
                        <option value="1" selected="selected">Active</option>
                      </select>
                  </div>


                </div>

                <div class="row">
                    <div class="col-12 d-flex">
                      <button type="button" class="reset-form btn btn-info waves-effect waves-light me-2">Clear</button>
                        <button id="btn_save_insurance" class="btn btn-primary waves-effect waves-light me-2" type="submit"> Save Now</button>
                    </div>
                </div>
            </div>

        </div>
    </form>
      </div>
    </div>
  </div>
</div>

<!-- Sub Insurance -->
<div class="modal fade" id="new_subinsurance" tabindex="-4" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Sub Insurance</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form method="post" id="newsubinsurance_form"  data-parsley-validate="true" class="custom-form">
        @csrf
        <input type="hidden" name="json_action" value="1">
        <div class="">
            <div class="card-body">

                  <div class="row">


                    <div class="col-lg-6 mb-4">
                      <label class="form-label" for="bs-validation-insurence">Insurance<span class="text-danger">*</span> </label>
                      <select name="insurence_id" required id="insurence_list" class="form-control jqv-inuput" role="select2" data-jqv-required="true">
                          <option value="">Select</option>
                          @if(isset($insurence_list))
                          @foreach($insurence_list as $item)
                          <option value="{{$item->id}}">{{$item->title}}</option>
                          @endforeach
                          @endif
                      </select>
                  </div>
                  <div class="col-lg-6 mb-4">
                  <label class="form-label" for="bs-validation-name">Title (en)<span class="text-danger">*</span> </label>
                  <input
                      type="text"
                      class="form-control jqv-input" data-jqv-required="true"
                      id="title_en"
                      name="title_en"
                      required
                      pattern="^[a-zA-Z0-9\s]+$"
                  />
                  <smal class="float-end me-2 text-danger" id="subinsurance_already_error"></smal>
                  </div>
                  <div class="col-lg-6 mb-4">
                  <label class="form-label" for="bs-validation-name">Title (ar) </label>
                  <input
                      type="text"
                      class="form-control jqv-input"
                      id="title_ar"
                      name="title_ar"
                      dir="rtl"
                      pattern="^[a-zA-Z0-9\s]+$"
                  />
                  </div>

                  <div class="col-lg-6 mb-4">
                      <label class="form-label">Status<span class="text-danger">*</span></label>
                      <select class="form-control jqv-input" data-jqv-required="true" name="status" required>
                        <option value="1" selected="selected">Active</option>
                      </select>
                  </div>

                </div>

                <div class="row">
                    <div class="col-12 d-flex">
                      <button type="button" class="reset-form btn btn-info waves-effect waves-light me-2">Clear</button>
                        <button id="btn_save_subinsurance" class="btn btn-primary waves-effect waves-light me-2" type="submit"> Save Now</button>
                    </div>
                </div>
            </div>

        </div>
    </form>
      </div>
    </div>
  </div>
</div>



<link href="{{ asset('admin-assets/plugins/notification/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('admin-assets/plugins/notification/toastr/toastr.min.js') }}"></script>
        <script>

            // Toaster options
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "rtl": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": 300,
                "hideDuration": 1000,
                "timeOut": 2000,
                "extendedTimeOut": 1000,
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }

            $(document).ready(function() {
            // Initialize Select2 on the modal shown event
            $('#newemiratemodel').on('shown.bs.modal', function () {
                $('#country_id').select2({
                    dropdownParent: $('#newemiratemodel') // Ensures the dropdown is appended to the modal
                });
            });
            $('#neweareamodel').on('shown.bs.modal', function () {
                $('#countryforarea').select2({
                    dropdownParent: $('#neweareamodel') // Ensures the dropdown is appended to the modal
                });
            });
            $('#new_subinsurance').on('shown.bs.modal', function () {
                $('#insurence_list').select2({
                    dropdownParent: $('#new_subinsurance') // Ensures the dropdown is appended to the modal
                });
            });
        });
    $("#create_new_country").submit(function(e) {
        e.preventDefault();
        var form = $('#create_new_country');
        if (form.parsley().isValid()) {
        var formData = $(this).serialize();
        $('#save_country_btn').prop("disabled", true).text("Processing..");
        $.ajax({
            url: "{{route('admin.countries.store')}}",
            type: "POST",
            data: formData,
            success: function(response){
                console.log(response);
                if(response.success){
                  if(response.id > 0)
                {
                  toastr["success"](response.message);
                  $('#save_country_btn').prop("disabled", false).text("Save Now");
                  $('#country').append('<option value="' + response.id + '" selected >' + response.name + '</option>');
                  $('#country_id').append('<option value="' + response.id + '" selected >' + response.name + '</option>');
                  $('#countryforarea').append('<option value="' + response.id + '" selected >' + response.name + '</option>');
                  $('#newcountrymodel').modal('hide');
                  $('#create_new_country').find('input[type="text"]').val('');
                  $('#country_already_error').text('');
                }

              else
              {
                toastr["error"](response.message);
                $('#country_already_error').text(response.message);
                $('#save_country_btn').prop("disabled", false).text("Save Now");
              }
            }

            },
            error: function(xhr, status, error){
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : error;
                toastr["error"](errorMessage);
              $('#save_country_btn').prop("disabled", false).text("Save Now");
                $('#newcountrymodel').modal('hide');
            }
        });
      }


    });

    $("#newemirate_form").submit(function(e) {
        e.preventDefault();
        var form = $('#newemirate_form');
        if (form.parsley().isValid()) {
        var formData = $(this).serialize();

        $('#btn_save_emirate').prop("disabled", true).text("Processing..");
        $.ajax({
            url: "{{route('admin.emirates.store')}}",
            type: "POST",
            data: formData,
            success: function(response){
                console.log(response);
                if(response.success){
                  if(response.id > 0){
                  toastr["success"](response.message);
                  $('#btn_save_emirate').prop("disabled", false).text("Save Now");
                  $('#emirate_id').append('<option value="' + response.id + '" selected >' + response.name + '</option>');
                  $('#emirate_area').append('<option value="' + response.id + '" selected >' + response.name + '</option>');
                  $('#newemiratemodel').modal('hide');
                  $('#newemirate_form').find('input[type="text"]').val('');
                  $('#country_id').val('0').change();
                  $('#emirate_already_error').text('');
                  }
                  else
                  {
                    toastr["error"](response.message);
                    $('#emirate_already_error').text(response.message);
                    $('#btn_save_emirate').prop("disabled", false).text("Save Now");
                  }

                }

            },
            error: function(xhr, status, error){
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : error;
              toastr["error"](errorMessage);
              $('#btn_save_emirate').prop("disabled", false).text("Save Now");

            }
        });
      }

    });

    $("#newarea_form").submit(function(e) {
        e.preventDefault();
        var form = $('#newarea_form');
        if (form.parsley().isValid()) {
        var formData = $(this).serialize();

        $('#btn_save_area').prop("disabled", true).text("Processing..");
        $.ajax({
            url: "{{route('admin.areas.store')}}",
            type: "POST",
            data: formData,
            success: function(response){
                console.log(response);
                if(response.success){
                  if(response.id > 0){
                  toastr["success"](response.message);
                  $('#btn_save_area').prop("disabled", false).text("Save Now");
                  $('#area_id').append('<option value="' + response.id + '" selected >' + response.name + '</option>');
                  $('#neweareamodel').modal('hide');
                  $('#newarea_form').find('input[type="text"]').val('');
                  $('#newarea_form').find('select').val('0').change();
                  $('#newarea_form').find('select').val('').change();
                  $('#area_already_error').text('');
                  }
                  else{
                    toastr["error"](response.message);
                    $('#area_already_error').text(response.message);
                    $('#btn_save_area').prop("disabled", false).text("Save Now");

                  }
                }

            },
            error: function(xhr, status, error){
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : error;
                toastr["error"](errorMessage);
              $('#btn_save_area').prop("disabled", false).text("Save Now");

            }
        });
      }

    });
    $("#newlang_form").submit(function(e) {
        e.preventDefault();
        var form = $('#newlang_form');
        if (form.parsley().isValid()) {
        var formData = $(this).serialize();
        $('#btn_save_lang').prop("disabled", true).text("Processing..");
        $.ajax({
            url: "{{route('admin.languages.submit')}}",
            type: "POST",
            data: formData,
            success: function(response){
                if(response.success){
                  if(response.id > 0){
                  toastr["success"](response.message);
                  $('#btn_save_lang').prop("disabled", false).text("Save Now");
                  $('#language_spoken_id').append('<option value="' + response.id + '" selected >' + response.name + '</option>');
                  $('#new_lang').modal('hide');
                  $('#newlang_form').find('input[type="text"]').val('');
                  $('#language_already_error').text('');
                  }
                  else
                  {
                    toastr["error"](response.message);
                    $('#language_already_error').text(response.message);
                    $('#btn_save_lang').prop("disabled", false).text("Save Now");
                  }
                }

            },
            error: function(xhr, status, error){
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : error;
                toastr["error"](errorMessage);
              $('#btn_save_lang').prop("disabled", false).text("Save Now");

            }
        });
      }

    });
    $("#newdept_form").submit(function(e) {
      var hospital = document.getElementById('new_hospital_id').value;
      if(hospital == ""){
        $('#hospital_error').text('Please Select Hospital');
        return false;
      }
        e.preventDefault();
        var form = $('#newdept_form');
        if (form.parsley().isValid()) {
        var formData = $(this).serialize();

        $('#btn_save_dept').prop("disabled", true).text("Processing..");
        $.ajax({
            url: "{{route('admin.departments.submit')}}",
            type: "POST",
            data: formData,
            success: function(response){
                if(response.success){
                  if(response.id > 0)
                  {
                    toastr["success"](response.message);
                  $('#btn_save_dept').prop("disabled", false).text("Save Now");
                  $('#departments').append('<option value="' + response.id + '" selected >' + response.name + '</option>');
                  $('#new_dept').modal('hide');
                  $('#newdept_form').find('input[type="text"]').val('');
                  $('#department_already_error').text('');
                  }
                  else
                  {
                    toastr["error"](response.message);
                    $('#department_already_error').text(response.message);
                    $('#btn_save_dept').prop("disabled", false).text("Save Now");
                  }

                }

            },
            error: function(xhr, status, error){
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : error;
                toastr["error"](errorMessage);
              $('#btn_save_dept').prop("disabled", false).text("Save Now");
              $('#new_dept').modal('hide');

            }
        });
      }

    });
    $("#newdspecial_form").submit(function(e) {
        e.preventDefault();
        var form = $('#newdspecial_form');
        if (form.parsley().isValid()) {
        var formData = $(this).serialize();
        $('#btn_save_special').prop("disabled", true).text("Processing..");
        $.ajax({
            url: "{{route('admin.specialties.store')}}",
            type: "POST",
            data: formData,
            success: function(response){
                if(response.success){
                  if(response.id > 0){
                    toastr["success"](response.message);
                  $('#btn_save_special').prop("disabled", false).text("Save Now");
                  $('#specialty').append('<option value="' + response.id + '" selected >' + response.name + '</option>');
                  $('#new_special').modal('hide');
                  $('#newdspecial_form').find('input[type="text"]').val('');
                  $('#special_already_error').text('');
                  }
                  else
                  {
                    toastr["error"](response.message);
                    $('#special_already_error').text(response.message);
                    $('#btn_save_special').prop("disabled", false).text("Save Now");
                  }

                }

            },
            error: function(xhr, status, error){
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : error;
                toastr["error"](errorMessage);
              $('#btn_save_special').prop("disabled", false).text("Save Now");

            }
        });
      }

    });
    $("#newdeducation_form").submit(function(e) {
        e.preventDefault();
        var form = $('#newdeducation_form');
        if (form.parsley().isValid()) {
        var formData = $(this).serialize();

        $('#btn_save_education').prop("disabled", true).text("Processing..");
        $.ajax({
            url: "{{route('admin.qualifications.submit')}}",
            type: "POST",
            data: formData,
            success: function(response){
                console.log(response)
                if(response.success){
                  if(response.id > 0){
                    toastr["success"](response.message);
                  $('#btn_save_education').prop("disabled", false).text("Save Now");
                  $('#qualification').append('<option value="' + response.id + '" selected >' + response.name + '</option>');
                  $('#new_education').modal('hide');
                  $('#newdeducation_form').find('input[type="text"]').val('');
                  $('#education_already_error').text('');
                  }
                  else
                  {
                    toastr["error"](response.message);
                    $('#education_already_error').text(response.message);
                    $('#btn_save_education').prop("disabled", false).text("Save Now");
                  }

                } else {
                    response = JSON.parse(response)
                    if (response['status'] == 0) {
                        if ( typeof response['errors'] !== 'undefined' ) {
                            var error_def = $.Deferred();
                            var error_index = 0;
                            jQuery.each(response['errors'], function (e_field, e_message) {
                                console.log(e_field)
                                console.log(e_message)
                                if ( e_message != '' ) {
                                    $('input[name="'+ e_field +'"]').addClass('is-invalid');
                                    $('<div class="error">'+ e_message[0] +'</div>').insertAfter($('input[name="'+ e_field +'"]'));
                                    if ( error_index == 0 ) {
                                        error_def.resolve();
                                    }
                                    error_index++;
                                }
                            });
                            error_def.done(function() {
                                var error = form.find('.is-invalid').eq(0);
                                $('html, body').animate({
                                    scrollTop: (error.offset().top - 100),
                                }, 500);
                            });
                        }
                    } else {
                        App.alert(res['message']||'Record saved successfully', 'Success!','success');
                        setTimeout(function(){
                            window.location.href = res['oData']['redirect'];
                        },2500);

                    }
                    $('#btn_save_education').prop("disabled", false).text("Save Now");
                }

            },
            error: function(xhr, status, error){
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : error;
                toastr["error"](errorMessage);
              $('#btn_save_education').prop("disabled", false).text("Save Now");
              $('#new_special').modal('hide');

            }
        });
      }

    });
    $("#newdeinterest_form").submit(function(e) {
        e.preventDefault();
        var form = $('#newdeinterest_form');
        if (form.parsley().isValid()) {
        var formData = $(this).serialize();

        $('#btn_save_interest').prop("disabled", true).text("Processing..");
        $.ajax({
            url: "{{route('admin.special_intrests.submit')}}",
            type: "POST",
            data: formData,
            success: function(response){
                if(response.success){
                  if(response.id > 0){
                    toastr["success"](response.message);
                  $('#btn_save_interest').prop("disabled", false).text("Save Now");
                  $('#special_interest').append('<option value="' + response.id + '" selected >' + response.name + '</option>');
                  $('#new_intereset').modal('hide');
                  $('#newdeinterest_form').find('input[type="text"]').val('');
                  $('#specialinterest_already_error').text('');
                  }
                  else
                  {
                    toastr["error"](response.message);
                    $('#specialinterest_already_error').text(response.message);
                    $('#btn_save_interest').prop("disabled", false).text("Save Now");
                  }

                } else {
                    response = JSON.parse(response)
                    if (response['status'] == 0) {
                        if ( typeof response['errors'] !== 'undefined' ) {
                            var error_def = $.Deferred();
                            var error_index = 0;
                            jQuery.each(response['errors'], function (e_field, e_message) {
                                console.log(e_field)
                                console.log(e_message)
                                if ( e_message != '' ) {
                                    $('input[name="'+ e_field +'"]').addClass('is-invalid');
                                    $('<div class="error">'+ e_message[0] +'</div>').insertAfter($('input[name="'+ e_field +'"]'));
                                    if ( error_index == 0 ) {
                                        error_def.resolve();
                                    }
                                    error_index++;
                                }
                            });
                            error_def.done(function() {
                                var error = form.find('.is-invalid').eq(0);
                                $('html, body').animate({
                                    scrollTop: (error.offset().top - 100),
                                }, 500);
                            });
                        }
                    } else {
                        App.alert(res['message']||'Record saved successfully', 'Success!','success');
                        setTimeout(function(){
                            window.location.href = res['oData']['redirect'];
                        },2500);

                    }
                    $('#btn_save_interest').prop("disabled", false).text("Save Now");
                }

            },
            error: function(xhr, status, error){
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : error;
                toastr["error"](errorMessage);
              $('#btn_save_interest').prop("disabled", false).text("Save Now");

            }
        });
      }

    });
    $("#newinsurance_form").submit(function(e) {
        e.preventDefault();
        var form = $('#newinsurance_form');
        if (form.parsley().isValid()) {
        var formData = $(this).serialize();

        $('#btn_save_insurance').prop("disabled", true).text("Processing..");
        $.ajax({
            url: "{{route('admin.insurence_policy.submit')}}",
            type: "POST",
            data: formData,
            success: function(response){
                if(response.success){
                  if(response.id > 0){
                    toastr["success"](response.message);
                  $('#btn_save_insurance').prop("disabled", false).text("Save Now");
                  $('#insurence_id').append('<option value="' + response.id + '" selected >' + response.name + '</option>');
                  $('#new_insurance').modal('hide');
                  $('#newinsurance_form').find('input[type="text"]').val('');
                  $('#insurance_already_error').text('');
                  }
                  else
                  {
                    toastr["error"](response.message);
                    $('#insurance_already_error').text(response.message);
                    $('#btn_save_insurance').prop("disabled", false).text("Save Now");
                  }

                }

            },
            error: function(xhr, status, error){
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : error;
                toastr["error"](errorMessage);
              $('#btn_save_insurance').prop("disabled", false).text("Save Now");
              $('#new_insurance').modal('hide');

            }
        });
      }

    });
    $("#newsubinsurance_form").submit(function(e) {
        e.preventDefault();
        var form = $('#newsubinsurance_form');
        if (form.parsley().isValid()) {
        var formData = $(this).serialize();

        $('#btn_save_subinsurance').prop("disabled", true).text("Processing..");
        $.ajax({
            url: "{{route('admin.sub_insurence_policy.submit')}}",
            type: "POST",
            data: formData,
            success: function(response){
                if(response.success){
                  if(response.id > 0){
                    toastr["success"](response.message);
                  $('#btn_save_subinsurance').prop("disabled", false).text("Save Now");
                  $('#sub_insurence_id').append('<option value="' + response.id + '" selected >' + response.name + '</option>');
                  $('#new_subinsurance').modal('hide');
                  $('#newsubinsurance_form').find('input[type="text"]').val('');
                  $('#insurence_list').val('0').change();
                  $('#subinsurance_already_error').text('');
                  }
                  else
                  {
                    toastr["error"](response.message);
                    $('#subinsurance_already_error').text(response.message);
                    $('#btn_save_subinsurance').prop("disabled", false).text("Save Now");
                  }

                }

            },
            error: function(xhr, status, error){
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : error;
                toastr["error"](errorMessage);
              $('#btn_save_subinsurance').prop("disabled", false).text("Save Now");
              $('#new_subinsurance').modal('hide');

            }
        });
      }

    });




    $('#countryforarea').change(function () {
        var countryId = $(this).val();
        if (countryId) {
            $.ajax({
                type: "GET",
                url: "{{ url('admin/get-emirates') }}/" + countryId,
                success: function (res) {
                    if (res) {
                        $('#emirate_area').empty();
                        $('#emirate_area').append('<option value="">Select Emirate</option>');
                        $.each(res, function (index, emirate) {
                            $('#emirate_area').append('<option value="' + emirate.id + '">' + emirate.name_en + '</option>');
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching emirates:', error);
                }
            });
        } else {
            $('#emirate').empty();
            $('#emirate').append('<option value="">Select Emirate</option>');
        }
    });
    $("#create_new_country_origin").submit(function(e) {
        e.preventDefault();
        var form = $('#create_new_country_origin');
        if (form.parsley().isValid()) {
        var formData = $(this).serialize();
        $('#save_country_btn_origin').prop("disabled", true).text("Processing..");
        $.ajax({
            url: "{{route('admin.countries.storeigin')}}",
            type: "POST",
            data: formData,
            success: function(response){
                console.log(response);
                if(response.success){
                  if(response.id > 0)
                {
                  toastr["success"](response.message);
                  $('#save_country_btn_origin').prop("disabled", false).text("Save Now");
                  $('#country').append('<option value="' + response.id + '" selected >' + response.name + '</option>');
                  $('#country_id').append('<option value="' + response.id + '" selected >' + response.name + '</option>');
                  $('#countryforarea').append('<option value="' + response.id + '" selected >' + response.name + '</option>');
                  $('#newcountrymodel').modal('hide');
                  $('#create_new_country').find('input[type="text"]').val('');
                  $('#country_already_error_origin').text('');
                }

              else
              {
                toastr["error"](response.message);
                $('#country_already_error_origin').text(response.message);
                $('#save_country_btn_origin').prop("disabled", false).text("Save Now");
              }
            }

            },
            error: function(xhr, status, error){
              // Parse the response text as JSON
        var response = JSON.parse(xhr.responseText);

// Access the message property
var errorMessage = response.message;
              toastr["error"](errorMessage);
              $('#save_country_btn_origin').prop("disabled", false).text("Save Now");
                $('#newcountrymodel').modal('hide');
            }
        });
      }


    });
</script>


</script>
