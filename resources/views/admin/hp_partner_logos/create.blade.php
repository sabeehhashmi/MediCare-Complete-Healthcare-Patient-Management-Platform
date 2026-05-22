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
    <form method="post" id="admin-form" action="{{ url('admin/hp-partner-logos') }}" enctype="multipart/form-data"
        data-parsley-validate="true">
        @csrf
        <div class="">
            <div class="card-body">
                
                <div class="row">

                    <div class="col-sm-12 col-xs-12 mb-4">
                        <div class="form-group">
                            <label>Add Logo Image<span style="color:red;">*<span></span></span></label>   
                            <input class="form-control jqv-input" id="imageUpload" type="file" name="image" accept="image/jpg, image/jpeg, image/png" /> 
                            
                        </div>
                    </div>

                        
                    <div class="col-sm-4 col-xs-12 mb-4">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control status-selection">
                                <option  value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div> 
                </div>
                
                <div class="row mt-2">

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

 
@stop
