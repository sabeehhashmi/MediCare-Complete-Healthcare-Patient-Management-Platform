@extends('admin.template.layout')

@section('content')
@if(!empty($Review->vendordatils ?? null)) 
@php
$vendor     = $Review->vendordatils ?? null;
$bankdata   = $Review->bankdetails ?? null;
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
    <form method="post" id="admin-form" action="{{ route('admin.review_update' ,$Review->id) }}" enctype="multipart/form-data"
        data-parsley-validate="true" >
        @csrf
        @method('post') <!-- Add this line for Laravel to recognize the PUT method -->
        <input type="hidden" value="{{$id}}" name="id">
            <div class="">
                <div class="card-body">
                    <div class="row"> 

 
                
                
                <div class="col-lg-6 mb-4">
                    <label class="form-label" for="username">Rating</label>
                    <div class="position-relative input-custom-icon">

                        <select class="form-control" name="rating" required>
                            <option value="">Select Rating</option>
                            <option value="1" {{$Review->rating =='1'?'selected':''}}>⭐</option>
                            <option value="2" {{$Review->rating =='2'?'selected':''}}>⭐⭐</option>
                            <option value="3" {{$Review->rating =='3'?'selected':''}}>⭐⭐⭐</option>
                            <option value="4" {{$Review->rating =='4'?'selected':''}}>⭐⭐⭐⭐</option>
                            <option value="5" {{$Review->rating =='5'?'selected':''}}>⭐⭐⭐⭐⭐</option>
                        </select>
                    </div>
                </div>

                 

                
                
                <div class="col-12 mb-4">
                    <div class="custom-textarea">
                        <label class="form-label" for="review">Review</label>
                        <textarea class="form-control" id="review" name="review" row="5">{!! $Review->feeback_message !!}</textarea>
                    </div>
                </div> 



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
 
</script>
@stop
