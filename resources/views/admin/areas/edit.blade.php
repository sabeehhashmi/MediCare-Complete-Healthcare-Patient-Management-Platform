@extends('admin.template.layout')

@section('content')
@if(!empty($datamain->vendordatils)) 
@php
$vendor     = $datamain->vendordatils;
$bankdata   = $datamain->bankdetails;
@endphp
@endif

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@if ( session('success'))
<div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong> {{ session('success') }} </strong>
</div>
@endif
@if ( session('error'))
<div class="alert alert-danger alert-dismissable custom-danger-box" style="margin: 15px;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong> {{ session('error') }} </strong>
</div>
@endif
<div class="card mb-5">
    <!--<div class="card p-4">-->
        <form method="post" id="admin-form" action="{{ route('admin.areas.update',$datamain->id) }}" enctype="multipart/form-data"
            data-parsley-validate="true">
            @csrf
            @method('PUT')
            <div class="">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 col-xs-12 mb-4">
                            <div class="form-group">
                                <label>Name (en)<span style="color:red;">*<span></span></span></label>
                                <input type="text" class="form-control" data-jqv-maxlength="100" name="name_en" value="{{old('name_en',$datamain->name_en)}}" required
                                data-parsley-required-message="Enter Name">
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12 mb-4 d-none">
                            <div class="form-group">
                                <label>Name (ar) <span style="color:red;">*<span></span></span></label>
                                <input type="text" class="form-control" data-jqv-maxlength="100" name="name_ar" value="{{old('name_ar',$datamain->name_en)}}" required
                                data-parsley-required-message="Enter Name" dir="rtl">
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12 mb-4">
                            <div class="form-group">
                                <label>Country</label>
                                <select id="country" class="form-control" name="country_id">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ $datamain->country_id == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12 mb-4">
                            <div class="form-group">
                                <label>City</label>
                                <select id="emirate" class="form-control" name="emirate_id">
                                    <option value="">Select Emirate</option>
                                    @foreach($emirates as $emirate)
                                    <option value="{{ $emirate->id }}" {{ $datamain->emirate_id == $emirate->id ? 'selected' : '' }}>
                                        {{ $emirate->name_en }}
                                        @if ($emirate->name_ar) <!-- Check if Arabic name exists -->
                                        ({{ $emirate->name_ar }})
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12 mb-4">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="active" class="form-control status-selection">
                                    <option @if(!empty($datamain)) {{$datamain->active==1 ? "selected" : null}} @endif value="1">Active</option>
                                    <option @if(!empty($datamain)) {{$datamain->active==0 ? "selected" : null}} @endif value="0">Inactive</option>
                                </select>
                            </div>
                        </div> 
                    </div>
                    
                    <div class="row mt-2">
                        <!-- <div class="col-sm-4 col-xs-12 other_docs" id="certificate_product_registration_div" >
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div> -->
                        <div class="col-12 d-flex">
                            <button class="btn btn-primary waves-effect waves-light me-2" type="submit">{{(($id ?? null) !='')?'Update':'Save'}}</button>
                            <button type="button" class="reset-form btn btn-info waves-effect waves-light">Clear</button>
                        </div>
                    </div>
                </div>
                
            </div>
        </form>
    </div>
    @stop
    
    @section('page_script')
 <script>
    $('#country').change(function () {
        var countryId = $(this).val();
        if (countryId) {
            $.ajax({
                type: "GET",
                url: "{{ url('admin/get-emirates') }}/" + countryId,
                success: function (res) {
                    if (res) {
                        $('#emirate').empty();
                        $('#emirate').append('<option value="">Select Emirate</option>');
                         $.each(res, function (key, value) {
                            var selected = (value.id == '{{ $datamain->emirate_id }}') ? 'selected' : '';
                            var nameAr = value.name_ar ? ' (' + value.name_ar + ')' : ''; // Check if name_ar exists
                            $('#emirate').append('<option value="' + value.id + '" ' + selected + '>' + value.name_en + nameAr + '</option>');
                        });
                    }
                }
            });
        } else {
            $('#emirate').empty();
            $('#emirate').append('<option value="">Select Emirate</option>');
        }
    });

    // Trigger change event on country select on page load if country is selected
    $(document).ready(function () {
        var selectedCountry = $('#country').val();
        if (selectedCountry) {
            $('#country').trigger('change');
        }
    });
</script>

    
    @stop
    