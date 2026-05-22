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
    <form method="post" id="admin-form" action="{{ url('admin/areas') }}" enctype="multipart/form-data"
        data-parsley-validate="true">
        @csrf
        <div class="">
            <div class="card-body">
                
                <div class="row">
                    <div class="col-sm-6 col-xs-12 mb-4">
                        <div class="form-group">
                            <label>Name (en)<span style="color:red;">*<span></span></span></label>
                            <input type="text" class="form-control" data-jqv-maxlength="100" name="name_en" value="{{old('name_en')}}" required
                            data-parsley-required-message="Enter Name">
                        </div>
                    </div>
                 
                    <div class="col-sm-6 col-xs-12 mb-4">
                        <div class="form-group">
                            <label>Country</label>
                            <select id="country" class="form-control" name="country_id">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12 mb-4">
                        <div class="form-group">
                            <label>City</label>
                            <select id="emirate" class="form-control" name="emirate_id">
                                <option value="">Select Emirate</option>
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
                        $.each(res, function (index, emirate) {
                            $('#emirate').append('<option value="' + emirate.id + '">' + emirate.name_en + '</option>');
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
</script>



@stop
