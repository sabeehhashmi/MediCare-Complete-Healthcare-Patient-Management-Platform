@extends('admin.template.layout')

@section('content')

<style>
    #admin-form {
        background-color: #f8f8f8;
    }

    .card_wrap {
        background-color: white;
    }

    .card_wrap>.ihdr {
        display: inline-block;
        width: 100%;
        background-color: #89124b;
        color: white;
        font-size: 18px;
        padding: 10px 28px;
    }

    .img_w input {
        margin-top: 10px;
    }

    .form-group textarea.form-control {
        height: 70px;
    }
</style>

@php

if (!function_exists('getImageT')) {
    function getImageT($image_key, $data)
    {
        // Check if the image key exists in the data
        if (isset($data[$image_key])) {
            return $data[$image_key];
        } else {
            return asset('assets/images/no-image.png');
        }
    }
}

if (!function_exists('getValueT')) {
    function getValueT($imeta_key, $data)
    {
        // Check if the meta key exists in the data
        if (isset($data[$imeta_key])) {
            return $data[$imeta_key];
        } else {
            return "";
        }
    }
}
@endphp

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
    <form method="post" id="admin-form" action="{{ route("admin.homepage-management.update") }}" enctype="multipart/form-data" data-parsley-validate="true">
        @csrf
       


        <div class="card_wrap shadow-lg mb-5">
            <div class="ihdr">Section 2</div>
            <div class="card-body">

                <div class="row">


                    <div class="col-sm-12 col-lg-6">
                        <div class="form-group">
                            <label>Title<span style="color:red;">*<span></span></span></label>
                            <input type="text" class="form-control" data-jqv-maxlength="100" name="frm_sct_2_title" value="{{getValueT("frm_sct_2_title", $data)}}" required2 data-parsley-required2-message="Enter Title">
                        </div>
                    </div>

                    <div class="col-sm-12 col-lg-6 mb-3">
                        <div class="form-group">
                            <label>Sub Title<span style="color:red;">*<span></span></span></label>
                            <input type="text" class="form-control" data-jqv-maxlength="100" name="frm_sct_2_subtitle" value="{{getValueT("frm_sct_2_subtitle", $data)}}" required2 data-parsley-required2-message="Enter Sub Title">
                        </div>
                    </div>

                    <div class="col-sm-12 col-lg-3 mb-3">
                        <div class="form-group shadow-lg p-2">
                            <p><strong>Box 1</strong></p>
                            <div class="d-inline-block w-100 wrp mb-3">
                                <label>Title<span style="color:red;">*<span></span></span></label>
                                <input type="text" class="form-control" data-jqv-maxlength="100" name="frm_sct_2_box1_title" value="{{getValueT("frm_sct_2_box1_title", $data)}}" required2 data-parsley-required2-message="Enter title">
                            </div>
                            <label>Description<span style="color:red;">*<span></span></span></label>
                            <textarea class="form-control" data-jqv-maxlength="150" name="frm_sct_2_box1_desc" rows="20" required2>{{ getValueT('frm_sct_2_box1_desc', $data) }}</textarea>
                        </div>
                    </div>

                    <div class="col-sm-12 col-lg-3 mb-3">
                        <div class="form-group shadow-lg p-2">
                            <p><strong>Box 2</strong></p>
                            <div class="d-inline-block w-100 wrp mb-3">
                                <label>Title<span style="color:red;">*<span></span></span></label>
                                <input type="text" class="form-control" data-jqv-maxlength="100" name="frm_sct_2_box2_title" value="{{getValueT("frm_sct_2_box2_title", $data)}}" required2 data-parsley-required2-message="Enter title">
                            </div>
                            <label>Description<span style="color:red;">*<span></span></span></label>
                            <textarea class="form-control" data-jqv-maxlength="150" name="frm_sct_2_box2_desc" rows="20" required2>{{getValueT("frm_sct_2_box2_desc", $data)}}</textarea>
                        </div>
                    </div>

                    <div class="col-sm-12 col-lg-3 mb-3">
                        <div class="form-group shadow-lg p-2">
                            <p><strong>Box 3</strong></p>
                            <div class="d-inline-block w-100 wrp mb-3">
                                <label>Title<span style="color:red;">*<span></span></span></label>
                                <input type="text" class="form-control" data-jqv-maxlength="100" name="frm_sct_2_box3_title" value="{{getValueT("frm_sct_2_box3_title", $data)}}" required2 data-parsley-required2-message="Enter title">
                            </div>
                            <label>Description<span style="color:red;">*<span></span></span></label>
                            <textarea class="form-control" data-jqv-maxlength="150" name="frm_sct_2_box3_desc" rows="20" required2>{{getValueT("frm_sct_2_box3_desc", $data)}}</textarea>
                        </div>
                    </div>

                    <div class="col-sm-12 col-lg-3 mb-3">
                        <div class="form-group shadow-lg p-2">
                            <p><strong>Box 4</strong></p>
                            <div class="d-inline-block w-100 wrp mb-3">
                                <label>Title<span style="color:red;">*<span></span></span></label>
                                <input type="text" class="form-control" data-jqv-maxlength="100" name="frm_sct_2_box4_title" value="{{getValueT("frm_sct_2_box4_title", $data)}}" required2 data-parsley-required2-message="Enter title">
                            </div>
                            <label>Description<span style="color:red;">*<span></span></span></label>
                            <textarea class="form-control" data-jqv-maxlength="150" name="frm_sct_2_box4_desc" rows="20" required2>{{getValueT("frm_sct_2_box4_desc", $data)}}</textarea>
                        </div>
                    </div>


                </div>



            </div>

        </div>

        <div class="card_wrap shadow-lg mb-5">
            <div class="ihdr">Section 3</div>
            <div class="card-body">

                <div class="row">


                    <div class="col-sm-12 col-md-6">
                        <div class="form-group mb-3">
                            <label>Title<span style="color:red;">*<span></span></span></label>
                            <input type="text" class="form-control" data-jqv-maxlength="100" name="frm_sct_3_title" value="{{getValueT("frm_sct_3_title", $data)}}" required2 data-parsley-required2-message="Enter Title">
                        </div>

                        <div class="form-group mb-3">
                            <div class="custom-textarea">
                                <label class="form-label">Content<span style="color:red;">*<span></span></span></label>
                                <textarea class="form-control ckeditor-classic" name="frm_sct_3_content" row="5" required2 data-parsley-required2-message="Enter Content">{{getValueT("frm_sct_3_content", $data)}}</textarea>
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-12 col-md-6">
                        <div class="form-group">
                            <label>Add Image<span style="color:red;">*<span></span></span></label>
                            <div class="img_w">
                                <img class="img-fluid img_preview" id="imagePreview" src="{{ getImageT("frm_sct_3_img", $data) }}" alt="Image Preview" />

                                <input class="form-control jqv-input smart_img" id="imageUpload" type="file" name="frm_sct_3_img" accept="image/jpg, image/jpeg, image/png" />
                                <p class="mt-2">Prefer image dimension is 950px X 890px</p>
                            </div>

                        </div>
                    </div>



                </div>

            </div>

        </div>

        <div class="card_wrap shadow-lg mb-5">
            <div class="ihdr">Section 4</div>
            <div class="card-body">

                <div class="row">


                    <div class="col-sm-12 col-md-6">
                        <div class="form-group">
                            <label>Add Image<span style="color:red;">*<span></span></span></label>
                            <div class="img_w">
                                <img class="img-fluid img_preview" id="imagePreview" src="{{ getImageT("frm_sct_4_img", $data) }}" alt="Image Preview" />

                                <input class="form-control jqv-input smart_img" id="imageUpload" type="file" name="frm_sct_4_img" accept="image/jpg, image/jpeg, image/png" />
                                <p class="mt-2">Prefer image dimension is 550px X 450px</p>
                            </div>

                        </div>
                    </div>


                    <div class="col-sm-12 col-md-6">
                        <div class="form-group mb-3">
                            <label>Title<span style="color:red;">*<span></span></span></label>
                            <input type="text" class="form-control" data-jqv-maxlength="100" name="frm_sct_4_title" value="{{getValueT("frm_sct_4_title", $data)}}" required2 data-parsley-required2-message="Enter Title">
                        </div>

                        <div class="form-group mb-3">
                            <div class="custom-textarea">
                                <label class="form-label">Content<span style="color:red;">*<span></span></span></label>
                                <textarea class="form-control ckeditor-classic" name="frm_sct_4_content" row="5" required2 data-parsley-required2-message="Enter Content">{{getValueT("frm_sct_4_content", $data)}}</textarea>
                            </div>
                        </div>
                    </div>



                </div>

            </div>

        </div>


        <div class="card_wrap shadow-lg mb-5">
            <div class="ihdr">Section 5</div>
            <div class="card-body">

                <div class="row">


                    <div class="col-sm-12 col-md-6">
                        <div class="form-group">
                            <label>Add Image<span style="color:red;">*<span></span></span></label>
                            <div class="img_w">
                                <img class="img-fluid img_preview" id="imagePreview" src="{{ getImageT("frm_sct_5_img", $data) }}" alt="Image Preview" />

                                <input class="form-control jqv-input smart_img" id="imageUpload" type="file" name="frm_sct_5_img" accept="image/jpg, image/jpeg, image/png" />
                                <p class="mt-2">Prefer image dimension is 519px X 519px</p>
                            </div>

                        </div>
                    </div>


                    <div class="col-sm-12 col-md-6">
                        <div class="form-group mb-3">
                            <label>Title<span style="color:red;">*<span></span></span></label>
                            <input type="text" class="form-control" data-jqv-maxlength="100" name="frm_sct_5_title" value="{{getValueT("frm_sct_5_title", $data)}}" required2 data-parsley-required2-message="Enter Title">
                        </div>

                        <div class="form-group mb-3">
                            <label>Sub Title<span style="color:red;">*<span></span></span></label>
                            <input type="text" class="form-control" data-jqv-maxlength="100" name="frm_sct_5_sub_title" value="{{getValueT("frm_sct_5_sub_title", $data)}}" required2 data-parsley-required2-message="Enter Sub Title">
                        </div>

                        <div class="form-group mb-3">
                            <div class="custom-textarea">
                                <label class="form-label">Content<span style="color:red;">*<span></span></span></label>
                                <textarea class="form-control ckeditor-classic" name="frm_sct_5_content" row="5" required2 data-parsley-required2-message="Enter Content">{{getValueT("frm_sct_5_content", $data)}}</textarea>
                            </div>
                        </div>
                    </div>



                </div>

            </div>

        </div>


        <div class="card_wrap shadow-lg mb-5">
            <div class="ihdr">Section 6</div>
            <div class="card-body">

                <div class="row">


                    <div class="col-12 mb-3">
                        <div class="form-group">
                            <label>Title<span style="color:red;">*<span></span></span></label>
                            <input type="text" class="form-control" data-jqv-maxlength="100" name="frm_sct_6_title" value="{{getValueT("frm_sct_6_title", $data)}}" required2 data-parsley-required2-message="Enter Title">
                        </div>
                    </div>


                    <div class="col-sm-12 col-md-3">

                        <div class="form-group shadow-lg p-2 mb-3">
                            <p><strong>Box 1</strong></p>
                            <div class="d-inline-block w-100 wrp mb-3">
                                <label>Title<span style="color:red;">*<span></span></span></label>
                                <input type="text" class="form-control" data-jqv-maxlength="100" name="frm_sct_6_box1_title" value="{{getValueT("frm_sct_6_box1_title", $data)}}" required2 data-parsley-required2-message="Enter title">
                            </div>
                            <label>Description<span style="color:red;">*<span></span></span></label>
                            <textarea class="form-control" data-jqv-maxlength="150" name="frm_sct_6_box1_desc" rows="20" required2>{{getValueT("frm_sct_6_box1_desc", $data)}}</textarea>
                        </div>

                        <div class="form-group shadow-lg p-2 mb-3">
                            <p><strong>Box 2</strong></p>
                            <div class="d-inline-block w-100 wrp mb-3">
                                <label>Title<span style="color:red;">*<span></span></span></label>
                                <input type="text" class="form-control" data-jqv-maxlength="100" name="frm_sct_6_box2_title" value="{{getValueT("frm_sct_6_box2_title", $data)}}" required2 data-parsley-required2-message="Enter title">
                            </div>
                            <label>Description<span style="color:red;">*<span></span></span></label>
                            <textarea class="form-control" data-jqv-maxlength="150" name="frm_sct_6_box2_desc" rows="20" required2>{{getValueT("frm_sct_6_box2_desc", $data)}}</textarea>
                        </div>

                        <div class="form-group shadow-lg p-2 mb-3">
                            <p><strong>Box 3</strong></p>
                            <div class="d-inline-block w-100 wrp mb-3">
                                <label>Title<span style="color:red;">*<span></span></span></label>
                                <input type="text" class="form-control" data-jqv-maxlength="100" name="frm_sct_6_box3_title" value="{{getValueT("frm_sct_6_box3_title", $data)}}" required2 data-parsley-required2-message="Enter title">
                            </div>
                            <label>Description<span style="color:red;">*<span></span></span></label>
                            <textarea class="form-control" data-jqv-maxlength="150" name="frm_sct_6_box3_desc" rows="20" required2>{{getValueT("frm_sct_6_box3_desc", $data)}}</textarea>
                        </div>

                    </div>


                    <div class="col-sm-12 col-md-6 mb-3">
                        <div class="form-group">
                            <label>Add Image<span style="color:red;">*<span></span></span></label>
                            <div class="img_w">
                                <img class="img-fluid img_preview" id="imagePreview" src="{{ getImageT("frm_sct_6_img", $data) }}" alt="Image Preview" />

                                <input class="form-control jqv-input smart_img" id="imageUpload" type="file" name="frm_sct_6_img" accept="image/jpg, image/jpeg, image/png" />
                                <p class="mt-2">Prefer image dimension is 800px X 800px</p>
                            </div>

                        </div>
                    </div>
            


                    <div class="col-sm-12 col-md-3">

                        <div class="form-group shadow-lg p-2 mb-3">
                            <p><strong>Box 4</strong></p>
                            <div class="d-inline-block w-100 wrp mb-3">
                                <label>Title<span style="color:red;">*<span></span></span></label>
                                <input type="text" class="form-control" data-jqv-maxlength="100" name="frm_sct_6_box4_title" value="{{getValueT("frm_sct_6_box4_title", $data)}}" required2 data-parsley-required2-message="Enter title">
                            </div>
                            <label>Description<span style="color:red;">*<span></span></span></label>
                            <textarea class="form-control" data-jqv-maxlength="150" name="frm_sct_6_box4_desc" rows="20" required2>{{getValueT("frm_sct_6_box4_desc", $data)}}</textarea>
                        </div>

                        <div class="form-group shadow-lg p-2 mb-3">
                            <p><strong>Box 5</strong></p>
                            <div class="d-inline-block w-100 wrp mb-3">
                                <label>Title<span style="color:red;">*<span></span></span></label>
                                <input type="text" class="form-control" data-jqv-maxlength="100" name="frm_sct_6_box5_title" value="{{getValueT("frm_sct_6_box5_title", $data)}}" required2 data-parsley-required2-message="Enter title">
                            </div>
                            <label>Description<span style="color:red;">*<span></span></span></label>
                            <textarea class="form-control" data-jqv-maxlength="150" name="frm_sct_6_box5_desc" rows="20" required2>{{getValueT("frm_sct_6_box5_desc", $data)}}</textarea>
                        </div>

                        <div class="form-group shadow-lg p-2 mb-3">
                            <p><strong>Box 6</strong></p>
                            <div class="d-inline-block w-100 wrp mb-3">
                                <label>Title<span style="color:red;">*<span></span></span></label>
                                <input type="text" class="form-control" data-jqv-maxlength="100" name="frm_sct_6_box6_title" value="{{getValueT("frm_sct_6_box6_title", $data)}}" required2 data-parsley-required2-message="Enter title">
                            </div>
                            <label>Description<span style="color:red;">*<span></span></span></label>
                            <textarea class="form-control" data-jqv-maxlength="150" name="frm_sct_6_box6_desc" rows="20" required2>{{getValueT("frm_sct_6_box6_desc", $data)}}</textarea>
                        </div>

                    </div>




                </div>



            </div>

        </div>


        <div class="card_wrap shadow-lg mb-5">
            <div class="ihdr">Section 7</div>
            <div class="card-body">

                <div class="row">


                    <div class="col-sm-12 col-md-6">
                        <div class="form-group">
                            <label>Add Image<span style="color:red;">*<span></span></span></label>
                            <div class="img_w">
                                <img class="img-fluid img_preview" id="imagePreview" src="{{ getImageT("frm_sct_7_img", $data) }}" alt="Image Preview" />

                                <input class="form-control jqv-input smart_img" id="imageUpload" type="file" name="frm_sct_7_img" accept="image/jpg, image/jpeg, image/png" />
                                <p class="mt-2">Prefer image dimension is 800px X 800px</p>
                            </div>

                        </div>
                    </div>


                    <div class="col-sm-12 col-md-6">
                        <div class="form-group mb-3">
                            <label>Title<span style="color:red;">*<span></span></span></label>
                            <input type="text" class="form-control" data-jqv-maxlength="100" name="frm_sct_7_title" value="{{getValueT("frm_sct_7_title", $data)}}" required2 data-parsley-required2-message="Enter Title">
                        </div>

                        <div class="form-group mb-3">
                            <label>Sub Title<span style="color:red;">*<span></span></span></label>
                            <input type="text" class="form-control" data-jqv-maxlength="100" name="frm_sct_7_sub_title" value="{{getValueT("frm_sct_7_sub_title", $data)}}" required2 data-parsley-required2-message="Enter Sub Title">
                        </div>

                        <div class="form-group mb-3">
                            <div class="custom-textarea">
                                <label class="form-label">Content<span style="color:red;">*<span></span></span></label>
                                <textarea class="form-control ckeditor-classic" name="frm_sct_7_content" row="5" required2 data-parsley-required2-message="Enter Content">{{getValueT("frm_sct_7_content", $data)}}</textarea>
                            </div>
                        </div>
                    </div>



                </div>

            </div>

        </div>


        <div class="card_wrap">
            <div class="card-body">


                <div class="row mt-2">

                    <div class="col-12 d-flex">
                        <button class="btn btn-primary waves-effect waves-light me-2" type="submit">Save</button>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>
@stop

@section('page_script')

<script>
    // On image change show it in the preview
    $(document).on('change', '.smart_img', function() {
        var fileInput = this;
        if (fileInput.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(fileInput).parents('.img_w').find('.img_preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(fileInput.files[0]);
        }
    });
</script>

<script src="{{ URL::asset('admin-assets/assets/js/ckeditor.js') }}"></script>
<script src="{{ URL::asset('admin-assets/assets/js/form-editor.init.js') }}"></script>

<script>
    // Loop through the .ckeditor-classic and create the editor
    $('.ckeditor-classic').each(function() {
        ClassicEditor.create($(this)[0])
            .then(function(e) {
                e.ui.view.editable.element.style.height = "200px";
            })
            .catch(function(e) {
                console.error(e);
            });
    });
</script>

@stop