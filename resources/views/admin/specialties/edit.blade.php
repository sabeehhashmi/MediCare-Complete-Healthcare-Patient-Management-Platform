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
        <form method="post" id="admin-form" action="{{ route('admin.specialties.update',$datamain->id) }}" enctype="multipart/form-data"
            data-parsley-validate="true" class="custom-form">
            @csrf
            @method('PUT')
            <div class="">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="form-group">
                                <label>Name (en)<span style="color:red;">*<span></span></span></label>
                                <input type="text" class="form-control" data-jqv-maxlength="100" name="name_en" value="{{old('name_en',$datamain->name_en)}}" required
                                data-parsley-required-message="Enter Name">
                            </div>
                        </div>
                        
                        <div class="col-lg-6 mb-4">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="active" class="form-control status-selection">
                                    <option @if(!empty($datamain)) {{$datamain->active==1 ? "selected" : null}} @endif value="1">Active</option>
                                    <option @if(!empty($datamain)) {{$datamain->active==0 ? "selected" : null}} @endif value="0">Inactive</option>
                                </select>
                            </div>
                        </div> 
                    </div>
                    
                    <div class="row">
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
    
    @section('script')
    <script>
    </script>
    
    @stop
    