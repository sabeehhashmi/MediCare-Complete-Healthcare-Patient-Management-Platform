@include('hospital.layouts.header')
<style>
    .check-container {
    }
    
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
        @include('hospital.layouts.left_nav_profile')

        <div class="w-100 user-chat mt-4 mt-sm-0 ms-lg-3">
            <div class="card">
                <div class="p-4 pt-4">
                <form method="post" id="insuranceform" class="insuranceform" action="{{ url('hospital/saveinsurance') }}" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="id" value="{{$id}}">   
                <div class="row">
                        <div class="col-lg-6 mb-3"></div>
                    </div>     
                    <div id="insurance-form-container">
                        <div class="row insu-form-row">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label" for="">Insurances Type</label>
                                <div class="position-relative select-custom-icon">
                                    <select name="insurance" class="select2-single" data-placeholder="Select Insurances Type">
                                        <option></option>
                                        @foreach($insurancepolices as $key => $insurance)
                                        <option value="{{ $insurance->id }}" {{ ($insurance->id == ($requestParam['insurance'] ?? null)) ? 'selected' : '' }} {{(!$insurance_id && $key == 0) ? 'selected' : '' }}>{{ $insurance->title }}</option>
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
                                        <input class="form-check-input" type="checkbox" name="sub_insurance[]" value="{{ $subinsurance->id }}" {{ (in_array($subinsurance->id, ($requestParam['sub_insurance'] ?? []))) ? 'checked' : '' }}>
                                        <label class="form-check-label">{{ $subinsurance->title }}</label>
                                    </div>
                                    @if(($duplicate_insurance ?? null) == $subinsurance->id)
                                        <span class="text-danger insurance-error">Insurance is already associated with the hospital</span>
                                    @endif
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
        </div>
    </div>
</div>

@include('hospital.layouts.footer')
<script src="{{ asset('') }}hospital/assets/js/ckeditor.js"></script>
<script src="{{ asset('') }}hospital/assets/js/form-editor.init.js"></script>

<script>
    var insurancesData = @json($insurances);
    $(document).ready(function() {

       
    // When the insurance dropdown changes

    // Trigger change on page load to initialize state
    $('select[name="insurance"]').trigger('change');
    
        filterSubInsurances($('select[name="insurance"]').val(), $('select[name="insurance"]').closest('.insu-form-row'));

        initializeSelect2();
        $('.add-insu-type-btn').on('click', function() {
            addInsuType();
        });

        $(document).on('change', 'select[name="insurance"]', function () {

    let insuranceId = $(this).val();

    $('.insurance-error').text('');

    filterSubInsurances(
        insuranceId,
        $(this).closest('.insu-form-row')
    );
});

        function filterSubInsurances(insuranceId, $formRow) {

    // hide all first
    $formRow.find('.checkbox-item').hide();

    // uncheck hidden checkboxes
    $formRow.find('.checkbox-item input[type="checkbox"]').prop('checked', false);

    if (insuranceId) {

        // show only matching insurance_id
        $formRow.find('.checkbox-item').each(function () {

            let subInsuranceId = $(this).data('insurance-id');

            if (subInsuranceId == insuranceId) {
                $(this).show();
            }
        });
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
                    <select name="insurance" class="select2-single" data-placeholder="Select Insurances Type">
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

        $('#insurance-form-container').append(newRow); 
        initializeSelect2(); 
    }

    function validateForm(event) {
        event.preventDefault();

        let valid = true;
        let errorMessage = '';

        $('select[name^="insurance"]').each(function() {
            if (!$(this).val()) {
                valid = false;
                errorMessage = 'Please select at least one Insurance Type.';
                return false;
            }
        });

        if (valid) {
            let subInsuranceChecked = false;
            $('input[name^="sub_insurance"]').each(function() {
                if ($(this).is(':checked')) {
                    subInsuranceChecked = true;
                    return false;
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
            $('#insuranceform').submit();
        }
    }
</script>
