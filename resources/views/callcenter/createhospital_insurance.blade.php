@include('callcenter.layouts.header')
<style>
    .check-container {
    }
    /*.checkbox-list {*/
    /*  max-height: 200px;*/
    /*  overflow-y: auto;*/
    /*}*/
    
    .checkbox-item {
      margin-bottom: 10px;
    }
    
    .checkbox-item input[type="checkbox"] {
      margin-right: 10px;
    }
    .input-custom-icon .form-control {
        padding: 10px 20px 10px 40px;
        font-size: 16px;
        border: 1px solid var(--card-bg);
        height: 50px;
        border-radius: 5px;
    }
</style>
<div class="position-relative mb-5">
    <div class="d-lg-flex">
        <div class="chat-leftsidebar card">
            <div class="card-body" style="flex: 0;">
                <div class="text-center bg-light rounded px-4 py-3">
                  
                    <div class="chat-user-status mt-4">
                        <img src="assets/images/clinic-logo.png" class="avatar-md rounded-circle" alt="" />
                    </div>
                    <h5 class="font-size-16 mb-1 mt-3"><a href="#" class="text-reset">{{$name}} Call Center </a></h5>
                    <p class="text-muted mb-0">{{$hospital->address}}</p>
                </div>
            </div>

            <div class="mail-list">
                <a href="{{ url('callcenter/get_profile') }}" class=" ">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-user-circle font-size-20 align-middle me-3"></i>
                        <div class="flex-grow-1">
                            <h5 class="font-size-14 mb-0">Our Profile</h5>
                        </div>
                    </div>
                </a>
                <a href="{{ url('callcenter/edit_profile') }}" class="border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-star-outline font-size-20 align-middle me-3"></i>
                        <div class="flex-grow-1">
                            <h5 class="font-size-14 mb-0">Edit Profile</h5>
                        </div>
                        <div class="flex-shrink-0"></div>
                    </div>
                </a>

                <a href="{{ url('callcenter/ourinsurance') }}" class="border-bottom active bg-primary-subtle">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-newspaper-variant-outline font-size-20 align-middle me-3"></i>
                        <div class="flex-grow-1">
                            <h5 class="font-size-14 mb-0">Our Insurance</h5>
                        </div>
                        <div class="flex-shrink-0"></div>
                    </div>
                </a>

                <a href="{{ url('callcenter/change_password') }}" class="border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-key-outline font-size-20 align-middle me-3"></i>
                        <div class="flex-grow-1">
                            <h5 class="font-size-14 mb-0">Change Password</h5>
                        </div>
                        <div class="flex-shrink-0"></div>
                    </div>
                </a>
            </div>
        </div>
        <!-- end chat-leftsidebar -->

        <div class="w-100 user-chat mt-4 mt-sm-0 ms-lg-3">
            <div class="card">
                <div class="p-4 pt-4">
                <form method="post" id="insuranceform" class="insuranceform" action="{{ url('callcenter/saveinsurance') }}" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="id" value="{{$id}}">   
                <div class="row">
                        <div class="col-lg-6 mb-3"></div>
                        <!-- <div class="col-lg-6 mb-3">
                            <button type="button" onclick="addInsuType()" style="margin-left: auto;" class="btn btn-outline-primary waves-effect waves-light">Add Insurance Type</button>
                        </div> -->
                    </div>     
                    <div id="insurance-form-container">
                        <!-- This div will contain dynamically added insurance type rows -->
                        <div class="row insu-form-row">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label" for="">Insurances Type</label>
                                <div class="position-relative select-custom-icon">
                                    <select name="insurance[]" class="select2-single" data-placeholder="Select Insurances Type">
                                        <option></option>
                                        @foreach($insurancepolices as $key => $insurance)
                                        <option value="{{ $insurance->id }}" {{ ($insurance->id == $insurance_id) ? 'selected' : '' }} {{(!$insurance_id && $key == 0) ? 'selected' : '' }}>{{ $insurance->title }}</option>
                                        @endforeach
                                    </select>
                                    <i class="fi fi-rr-compliance-document" style="margin-top: 2px;"></i>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label" for="">Sub Insurances</label>
                                <div class="checkbox-list">
                                    @foreach($subinsurancepolices as $subinsurance)
                                    <div class="checkbox-item form-check" data-insurance-id="{{ $subinsurance->insurence_id }}">
                                        <input class="form-check-input" type="checkbox" name="sub_insurance[]" value="{{ $subinsurance->id }}" {{ (in_array($subinsurance->id, $subinsurance_id)) ? 'checked' : '' }}>
                                        <label class="form-check-label">{{ $subinsurance->title }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit" onclick="validateForm(event)">Submit</button>
                        </div>
                    </div>
                </form>

                </div>
            </div>
            <!-- end user chat -->
        </div>
        <!-- End d-lg-flex  -->
    </div>
</div>

@include('callcenter.layouts.footer')
<script src="{{ asset('') }}hospital/assets/js/ckeditor.js"></script>
<script src="{{ asset('') }}hospital/assets/js/form-editor.init.js"></script>
<!-- <script>
    $(document).ready(function() {
            $('#insurance').change(function() {
                var selectedInsuranceId = $(this).val();
                filterSubInsurances(selectedInsuranceId);
            });

            function filterSubInsurances(insuranceId) {

                if (insuranceId) {
                    $('#sub-insurance-list .checkbox-item').each(function() {
                        var subInsuranceId = $(this).data('insurance-id');
                        if (subInsuranceId == insuranceId) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                } else {
                    $('#sub-insurance-list .checkbox-item').show();
                }
            }
        });
   
    function addInsuType(){
        $(".insu-form-row").before(`<div class="row insu-form-row">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label" for="">Insurances Type</label>
                                <div class="position-relative select-custom-icon">
                                    <select name="" id="" class="select2-single" data-placeholder="Select Insurances Type">
                                        <option></option>
                                        <option value="1">Abu Dhabi National Insurance Company</option>
                                        <option value="2">Aafiya</option>
                                        <option value="3">Advance Medical Center - Aafiya</option>
                                        <option value="4">AETNA</option>
                                        <option value="5">Al Ain Ahlia Insurance Company</option>
                                        <option value="6">Al Arabiya Group of Companies</option>
                                        <option value="7">AL Buhaira National Insurance Company</option>
                                        <option value="8">Al Khazna Insurance Company</option>
                                        <option value="9">AL RAFAH PHARMACY</option>
                                        <option value="10">Al Saha travels</option>
                                        <option value="11">Allianz Arab Orient Insurance Company</option>
                                        <option value="12">Allianz Marine Services LLC</option>
                                        <option value="13">AlMadallah HealthCare</option>
                                        <option value="14">Amity</option>
                                        <option value="15">Arabian Scandinavaian Insurance</option>
                                        <option value="16">Axa</option>
                                        <option value="17">BARWIL ABU DHABI RUWAIS LLC</option>
                                        <option value="18">Better To Know</option>
                                        <option value="19">Daman National Health Insurance Company</option>
                                        <option value="20">DAMAN THIQA</option>
                                    </select>
                                    <i class="fi fi-rr-compliance-document" style="margin-top: 2px;"></i>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label" for="">Sub Insurances</label>
                                    <div class="checkbox-list">
                                        <div class="checkbox-item form-check">
                                          <input class="form-check-input" type="checkbox" id="item16" value="Item 16">
                                          <label for="item16" class="form-check-label">ABUDHABI NATIONAL NETWORK</label>
                                        </div>
                                        <div class="checkbox-item form-check">
                                          <input class="form-check-input" type="checkbox" id="item26" value="Item 26">
                                          <label for="item26" class="form-check-label">HIGH-GN,GNPLUS,GNR-INS017</label>
                                        </div>
                                        <div class="checkbox-item form-check">
                                          <input class="form-check-input" type="checkbox" id="item36" value="Item 36">
                                          <label for="item36" class="form-check-label">HIGH-RNA,RNB-INS017</label>
                                        </div>
                                        
                                        <div class="checkbox-item form-check">
                                          <input class="form-check-input" type="checkbox" id="item46" value="Item 46">
                                          <label for="item46" class="form-check-label">HIGH-SILVER-INS017</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="mb-3">`)
        $('.select2-single').select2({
            placeholder: $(this).data('placeholder'),

        });
    }

</script> -->
<script>
    $(document).ready(function() {
        // Function to initialize Select2 for dynamically added select elements
        filterSubInsurances($('select[name="insurance[]"]').val(), $('select[name="insurance[]"]').closest('.insu-form-row'));

        // Initial setup for existing elements
        initializeSelect2();

        // Event listener for adding insurance type rows
       

        // Event listener for adding insurance type row
        $('.add-insu-type-btn').on('click', function() {
            addInsuType();
        });

        // Event listener for insurance dropdown change
        $(document).on('change', 'select[name="insurance[]"]', function() {
            var insuranceId = $(this).val();
            filterSubInsurances(insuranceId, $(this).closest('.insu-form-row'));
        });

        // Function to filter sub-insurances based on selected insurance
        function filterSubInsurances(insuranceId, $formRow) {
            if (insuranceId) {
                $formRow.find('.checkbox-item').each(function() {
                    var subInsuranceId = $(this).data('insurance-id'); // Assuming you have an input for value
                    if (subInsuranceId == insuranceId) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            } else {
                $formRow.find('.checkbox-item').show();
            }
        }

    });
    function initializeSelect2() {
            $('.select2-single').select2({
                placeholder: "Select Insurances Type",
            });
    }
    function addInsuType() {
            var newRow = `<div class="row insu-form-row">
                <div class="col-lg-12 mb-3">
                    <label class="form-label" for="">Insurances Type</label>
                    <div class="position-relative select-custom-icon">
                        <select name="insurance[]" class="select2-single" data-placeholder="Select Insurances Type">
                            <option></option>
                            @foreach($insurancepolices as $insurance)
                            <option value="{{ $insurance->id }}">{{ $insurance->title }}</option>
                            @endforeach
                        </select>
                        <i class="fi fi-rr-compliance-document" style="margin-top: 2px;"></i>
                    </div>
                </div>
                <div class="col-lg-12 mb-3">
                    <label class="form-label" for="">Sub Insurances</label>
                    <div class="checkbox-list">
                        @foreach($subinsurancepolices as $subinsurance)
                        <div class="checkbox-item form-check" data-insurance-id="{{ $subinsurance->insurence_id }}">
                            <input class="form-check-input" type="checkbox" name="sub_insurance[]" value="{{ $subinsurance->id }}">
                            <label class="form-check-label">{{ $subinsurance->title }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <hr class="mb-3">`;

            $('#insurance-form-container').append(newRow); // Append new row to the container
            initializeSelect2(); // Reinitialize Select2 for the new select element
    }

    function validateForm(event) {
        // Prevent form submission
        event.preventDefault();

        let valid = true;
        let errorMessage = '';

        // Check that at least one insurance type is selected
        $('select[name^="insurance"]').each(function() {
            if (!$(this).val()) {
                valid = false;
                errorMessage = 'Please select at least one Insurance Type.';
                return false; // Exit loop early
            }
        });

        // Check that at least one sub insurance is checked
        if (valid) {
            let subInsuranceChecked = false;
            $('input[name^="sub_insurance"]').each(function() {
                if ($(this).is(':checked')) {
                    subInsuranceChecked = true;
                    return false; // Exit loop early
                }
            });

            if (!subInsuranceChecked) {
                valid = false;
                errorMessage = 'Please select at least one Sub Insurance.';
            }
        }

        if (!valid) {
            alert(errorMessage);
        } else {
            // If validation passes, submit the form
            $('#insuranceform').submit();
        }
    }


      
</script>

