
<!doctype html>
<html lang="en">

    
<head>

        <meta charset="utf-8" />
        <title>Clinic Register | Mednero</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- App favicon -->
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('') }}hospital/assets/images/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('') }}hospital/assets/images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('') }}hospital/assets/images/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('') }}hospital/assets/images/favicon/favicon-16x16.png">
        <link rel="manifest" href="{{ asset('') }}hospital/assets/images/favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('') }}hospital/assets/images/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <!-- plugin css -->
        <link href="{{ asset('') }}hospital/assets/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />

        <!-- Bootstrap Css -->
        <link href="{{ asset('') }}hospital/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('') }}hospital/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        
        <link href="{{ asset('') }}hospital/assets/css/flatpickr.min.css" rel="stylesheet" type="text/css" />
        <!-- Intel Input Css -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">
        <link href="{{ URL::asset('web/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
        <!-- App Css-->
        <link href="{{ asset('') }}hospital/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
        <link href="{{ asset('') }}hospital/assets/css/custom.css" id="app-style" rel="stylesheet" type="text/css" />

        <link
          rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"
        />

       
        <style>
            .has-iframe .fancybox__content{
                padding:0 !important;
            }
            .select2-error{
                display: flex;
                flex-direction: column-reverse;
            }


            #parsley-id-27{
                position: absolute;
                bottom: -18px !important
            }

        </style>

</head>

<body>

<div class="authentication-bg min-vh-100">
        <!-- <div class="bg-overlay bg-light"></div> -->
        <div class="container">
            <div class="d-flex flex-column min-vh-100 px-3 pt-4">
                <div class="row justify-content-center my-auto">
                    <div class="col-12">

                        
                    <div class="card">
                            <div class="card-body p-4"> 
                                <div class="mb-4 pb-2">
                                    <a href="" class="d-block auth-logo">
                                        <img src="{{ asset('') }}hospital/assets/images/logo-mednero.png" alt="" height="60" class="auth-logo-dark me-start">
                                        <img src="{{ asset('') }}hospital/assets/images/logo-mednero.png" alt="" height="60" class="auth-logo-light me-start">
                                    </a>
                                </div>
                                <div class="text-center mt-2">
                                    <h5>Create an Account</h5>
                                    <p class="text-black">Welcome to Clinic Panel.</p>
                                </div>
                                <div class="p-2 mt-4">
                                <form id="msform" action="{{ url('clinic/save_clinic') }}" class="registerform"  enctype="multipart/form-data" method="post" data-parsley-validate="true">
                                @csrf
                                <input type="hidden" value="0" name="termsaccpeted" id="termsaccpeted">
                                        <div class="row">
                                            <div class="col-lg-12 mb-3">
                                                <label class="form-label" for="username">Clinic Name</label>
                                                <div class="position-relative input-custom-icon">
                                                    <input type="text" class="form-control" required name="name_en" placeholder="Clinic Name" data-jqv-required="true">
                                                    <span class="bx bx-building"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label" for="username">Email Address</label>
                                                <div class="position-relative input-custom-icon">
                                                    <input type="email" class="form-control" id="" name="email" required placeholder="Enter Address">
                                                    <span class="bx bx-envelope"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label" for="username">Clinic Main Number</label>
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone Number">
                                                    <input type="hidden" id="dial_code" name="dial_code">
                                                </div>
                                            </div>

                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label" for="password-input">Password</label>
                                                <div class="position-relative auth-pass-inputgroup input-custom-icon">
                                                    <span class="bx bx-lock-alt"></span>
                                                    <input type="password" class="form-control" required id="password-input" name="password" placeholder="Enter password" autocomplete="new-password">
                                                    <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon-1">
                                                        <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label" for="conf-password-input">Confirm Password</label>
                                                <div class="position-relative auth-pass-inputgroup input-custom-icon">
                                                    <span class="bx bx-lock-alt"></span>
                                                    <input type="password" class="form-control" required id="conf-password-input" name="confpassword" placeholder="Enter Confirm Password" autocomplete="new-password">
                                                    <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon-2">
                                                        <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                                    </button>
                                                </div>
                                                <span class="text-danger" id="confirm-pass-match"></span>
                                            </div>


                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label" for="username">Website</label>
                                                <div class="position-relative input-custom-icon">
                                                    <input type="text" class="form-control" name="website" placeholder="Enter Website">
                                                    <span class="bx bx-globe"></span>
                                                </div>
                                            </div>
                                            {{-- <div class="col-lg-6 mb-3">
                                                <label class="form-label" for="username">Direct Call for Appointment Number</label>
                                                <div class="position-relative">
                                                <input type="hidden" id="direct_dial_code" name="direct_dial_code" >
                                                    <input type="number" class="form-control" id="phone1" name="direct_phone" placeholder="Enter Direct Call for Appointment Number">
                                                    <!-- <span class="bx bx-phone"></span> -->
                                                </div>
                                            </div> --}}
                                            
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label" for="username">Country</label>
                                                <div class="position-relative select-custom-icon select2-error">
                                                    <select name="country" id="country" required class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select Country">
                                                        <option value="">Select Country</option>
                                                        @foreach($country_list as $country)
                                                        <option  value="{{$country->id}}">{{$country->name}}</option>
                                                    @endforeach
                                                    
                                                </select>
                                                    <i class="bx bx-navigation"></i>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label" for="username">City</label>
                                                <div class="position-relative select-custom-icon select2-error">
                                                    <select name="emirate_id" required id="emirate_id" class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select City">
                                                        <option value="">Select City</option>
                                                        @foreach($emirates_list as $emirate)
                                                        <option value="{{$emirate->id}}">{{$emirate->name_en}}</option>
                                                    @endforeach
                                                </select>
                                                    <i class="bx bx-navigation"></i>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label" for="username">Area</label>
                                                <div class="position-relative select-custom-icon select2-error select2-error-custom">
                                                    <select name="area_id" id="area_id" required class="select2-single jqv-input" data-jqv-required="true" role="select2" data-placeholder="Select Area">
                                                        @foreach($area_list as $area)
                                                            <option  value="{{$area->id}}">{{$area->name_en}}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="bx bx-navigation"></i>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label" for="username">Address Of Organization</label>
                                                <div class="position-relative input-custom-icon">
                                                    <input type="text" class="form-control" required placeholder="Enter Address" id="address"  name="address"/>
                                                    <span class="bx bx-calendar-event"></span>
                                                
                                                </div>
                                            
                                            </div>

                                            <div class="col-lg-6 mb-3">
                                                <div class="custom-upload">
                                                    <label for="uploadphotos" class="form-label">Upload Logo (Allowed Dim 300px X 300px) (jpg, jpeg, png)</label>
                                                    <input class="form-control jqv-input" id="imageUpload" type="file" name="image" accept="image/jpg, image/jpeg, image/png" />
                                                    <div id="imagePreview" style="display: none;"></div>
                                                </div>
                                            </div>

                                            

                                            <div class="col-12 mb-3">
                                                <label class="form-label" for="username">Location</label>
                                                <div class="position-relative input-custom-icon">
                                                    <input type="text" class="form-control autocomplete" required placeholder="673C+W8V - Dubai - United Arab Emirates" id="txt_location"  name="txt_location"/>
                                                    <span class="bx bx-calendar-event"></span>
                                                    <input type="hidden" id="location" name="location">
                                                </div>
                                                <div class="form-group col-md-12">
                                              <div id="map_canvas" style="height: 200px;width:100%;"></div>
                                             </div>
                                            </div>

                                            <div class="col-12 mb-3">
                                                <div class="custom-textarea">
                                                    <label class="form-label" required for="username">Clinic Profile</label>
                                                    <textarea class="form-control" name="profile_bio" id="ckeditor-classic" row="5"></textarea>
                                                </div>
                                            </div>


                                            {{--<div class="col-lg-12 mb-3">
                                                <label class="form-label" for="username">Services </label>
                                                <div class="position-relative select-without-icon select2-error">
                                                    <select name="" id="" class="select2-single" multiple data-placeholder="Select Services">
                                                        <option></option>
                                                        <option value="1">Lab</option>
                                                        <option value="2">Service 2</option>
                                                    </select>
                                                </div>
                                            </div>--}}


                                            <div class="col-md-12 form-group  imgs-wrap">    


                                            <div class="col-12 mb-3">
                                                <div class="custom-upload">
                                                    <label for="uploadphotos" class="form-label">Upload Photos <span>(Allowed 3 Photos with Dim 750px X 750px)</span></label>
                                                    <input class="form-control" type="file" name="images[]" id="upload_imgs" accept="image/jpg, image/jpeg" multiple/>
                                                </div>
                                                <div id="image_preview" class="row"></div> 
                                            </div>

                                            
                                            
                                            
                                            <div class="col-12 mb-3">
                                                <div class="form-check py-1 d-flex align-items-center">
                                                    <input type="checkbox" class="form-check-input" id="auth-remember-check">
                                                    <label class="form-check-label" for="auth-remember-check" required> By proceeding <a href="#!" data-bs-toggle="modal" data-bs-target="#terms-conditions-modal">I Agree to Privacy Policy</a></label>
                                                    <!-- <label class="form-check-label" for="auth-remember-check" required> by proceeding Agree <a href="{{url('hospital/terms-conditions')}}" data-fancybox data-type="iframe">Terms and conditions</a></label> -->
                                                </div>
                                            </div>
                                            
                                            <!--<div class="col-12 mb-3">-->
                                            <!--    <iframe src="terms-conditions.php" height="500" style="width:100%" title="Iframe Example"></iframe>-->
                                            <!--</div>-->
                                            
                                            <div class="col-12">
                                                <button class="btn btn-primary mx-auto waves-effect waves-light" type="submit">Create An Account</button>
                                            </div>
                                        </div>
                
                                        <div class="mt-3 text-center">
                                            <p class="mb-0">Already have an account? <a href="{{url('clinic/login')}}" class="fw-medium text-primary">Log In</a> </p>
                                        </div>
                                    </form> 
                                </div>
            
                            </div>
                        </div>

                    </div><!-- end col -->
                </div><!-- end row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center p-4">
                            <p class="text-white">© <script>document.write(new Date().getFullYear())</script> © mednero.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- end container -->
    </div>
    <!-- end authentication section -->

    <!-- Modal -->
   <div class="modal fade" id="terms-conditions-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Terms & Conditions</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <!-- style="overflow-y: scroll; height:400px;" -->
        <div style="overflow-y: scroll; height:400px;" id="termsdiv">
            <p>
                <?php echo $terms->desc_en ?? null;?>
            </p>
        </div>
            <button type="button" class="btn btn-primary w-100" id="acceptbutton" disabled data-bs-dismiss="modal">Accept Terms & Conditions</button>
        </div>
        </div>
    </div>
    </div>



    <!-- JAVASCRIPT -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('') }}hospital/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Intel Input Js-->
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>
    <script src="{{ asset('') }}hospital/assets/js/flatpickr.min.js"></script>
    <script src="{{URL::asset('web/js/select2.min.js')}}"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <script src="{{asset('')}}admin-assets/assets/js/pages/pass-addon.init.js"></script>
    <script>
    let fileArr =[];

    document.addEventListener('DOMContentLoaded', function() {
    // Function to toggle password visibility
        function togglePasswordVisibility(buttonId, inputId) {
            const button = document.getElementById(buttonId);
            const input = document.getElementById(inputId);
            button.addEventListener('click', function() {
                // Toggle the type attribute
                if (input.type === 'password') {
                    input.type = 'text';
                    button.querySelector('i').classList.remove('mdi-eye-outline');
                    button.querySelector('i').classList.add('mdi-eye-off-outline');
                } else {
                    input.type = 'password';
                    button.querySelector('i').classList.remove('mdi-eye-off-outline');
                    button.querySelector('i').classList.add('mdi-eye-outline');
                }
            });
        }

        // Initialize the toggle functions
        togglePasswordVisibility('password-addon-1', 'password-input');
        togglePasswordVisibility('password-addon-2', 'conf-password-input');
    });


    $("body").on('change', '#upload_imgs', function(){
        // check if fileArr length is greater than 0
        if (fileArr.length > 0) fileArr = [];
        
            $('#image_preview').html("");
            var total_file = document.getElementById("upload_imgs").files;
            console.log(total_file, 'total_file');
            if(total_file.length > 3){
                App.alert("You can select max of 3 images");
                return false;
            }
            if (!total_file.length) return;
            for (var i = 0; i < total_file.length; i++) {
            //   if (total_file[i].size > 1048576) {
            //     return false;
            //   } else {
                fileArr.push(total_file[i]);
                console.log(fileArr, 'fileArr');
                $('#image_preview').append("<div class='img-div col-lg-4 col-md-6 mb-3 mt-2' id='img-div"+i+"'><img src='"+URL.createObjectURL(total_file[i])+"' class='img-fluid w-100' title='"+total_file[i].name+"'><div class='middle'><button id='action-icon' value='img-div"+i+"' class='btn btn-danger btn-icon' role='"+total_file[i].name+"'><i class='fa fa-trash'></i></button></div></div>");
            //}
            }
    });
        $(document).ready(function() {

            
            $('.select2-single').select2({
                placeholder: $(this).data('placeholder'),

            });
        });
        
        $(".flatpicker-input").flatpickr({
            dateFormat: "d-m-Y",
            minDate: "today"
        });

        document.addEventListener("DOMContentLoaded", function () {

const input = document.querySelector("#phone");

const ph = window.intlTelInput(input, {
 //   initialCountry: '{{INIT_PHONE_C_CODE}}',
    geoIpLookup: "auto",
    separateDialCode: true,
    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
});

function updateDialCode() {
    var dialCode = ph.getSelectedCountryData().dialCode;
    document.getElementById('dial_code').value = dialCode;
}

input.addEventListener("input", updateDialCode);
input.addEventListener("countrychange", updateDialCode);

// 🔹 Set country in EDIT case
var existingDialCode = "";
if(existingDialCode){
    ph.setNumber("+" + existingDialCode + input.value);
}

updateDialCode();

});


        
        const input1 = document.querySelector("#phone1");
        const iti = window.intlTelInput(input1, {
            
        //    initialCountry: '{{INIT_PHONE_C_CODE}}',
        //    onlyCountries: <?php echo json_encode(ONLY_COUNTRY_PHONE); ?>,
            strictMode: true,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
        });
        input1.addEventListener("input", function () {
            var dialCode = iti.getSelectedCountryData().dialCode;
            $('#direct_dial_code').val(dialCode);
        });

        
    </script>

   

    <script src="{{ asset('') }}hospital/assets/js/ckeditor.js"></script>
    <script src="{{ asset('') }}hospital/assets/js/form-editor.init.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script src="{{asset('js/app.js')}}"></script>
                <!-- App js -->
    <!-- <script src="{{asset('admin-assets/assets/js/app.js')}}"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js" integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.js" integrity="sha512-Fq/wHuMI7AraoOK+juE5oYILKvSPe6GC5ZWZnvpOO/ZPdtyA29n+a5kVLP4XaLyDy9D1IBPYzdFycO33Ijd0Pg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <script src="{{URL::asset('web/js/parsley.min.js')}}"></script>
    <script src="{{URL::asset('web/js/parsley.js')}}"></script>
    <script>
        $(document).on('click', '#auth-remember-check', function(e) {
            e.preventDefault();
            $('#terms-conditions-modal').modal('show');
        });
        $(document).ready(function() {
            $("#termsdiv").scroll(function() {
                var div = $(this);
                var scrolled = div.scrollTop() + div.innerHeight();
                var contentHeight = div.get(0).scrollHeight;
                if (scrolled >= (contentHeight - 10)) {
                    $("#acceptbutton").prop("disabled", false);
                }
            });

            // Accept terms button click handler
            $('body').off('click', '#acceptbutton');
            $('body').on('click', '#acceptbutton', function() {
                $("#termsaccepted").val(1);
                $("#auth-remember-check").prop("checked", true).prop("disabled", false);
            });
        });
        
        $('#conf-password-input, #password-input').on('keyup', function(){
            $('#confirm-pass-match').text('');
        })

        $('body').off('submit', '#msform');
        $('body').on('submit', '#msform', function(e) {
            $('#confirm-pass-match').text('');
            e.preventDefault();
            if($('#password-input').val() != $('#conf-password-input').val()){
                $('#confirm-pass-match').text('Confirm Password not matched.');
                return false;
            }
            var $form = $(this);
            var formData = new FormData(this);
            $(".invalid-feedback").remove();
            var terms =  $("#auth-remember-check").prop('checked');
            if(!terms)
            {
                $('#terms-conditions-modal').modal('show'); 
                return false;
            }
            App.loading(true);
            $form.find('button[type="submit"]')
                .text('Saving')
                .attr('disabled', true);

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: $form.attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                dataType: 'json',
                timeout: 600000,
                success: function(res) {
                    App.loading(false);

                    if (res['status'] == 0) {
                        if (typeof res['errors'] !== 'undefined') {
                            var error_def = $.Deferred();
                            var error_index = 0;
                            jQuery.each(res['errors'], function(e_field, e_message) {
                                if (e_message != '') {
                                    $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($('[name="' + e_field + '"]').eq(0));
                                    $('[name="' + e_field + '[]"]').eq(0).addClass('is-invalid');
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($('[name="' + e_field + '[]"]').eq(0));
                                    if (error_index == 0) {
                                        error_def.resolve();
                                    }
                                    error_index++;
                                }
                            });
                            error_def.done(function() {
                                var error = $form.find('.is-invalid').eq(0);
                                $('html, body').animate({
                                    scrollTop: (error.offset().top - 100),
                                }, 500);
                            });
                        } else {
                            var m = res['message'];
                            App.alert(m, 'Oops!');
                        }
                    } else {
                        // $(".fld_set").addClass('d-none');
                        $("#msform").addClass('d-none');
                        $('#s-message').removeClass("d-none");
                        $(".sh_msg").trigger('click');
                        App.loading(false);
                         App.alert(res['message']);
                         setTimeout(function() {
                            window.location.href = "{{url('/clinic/login')}}";
                         }, 3000);
                    }

                    $form.find('button[type="submit"]')
                        .text('Save')
                        .attr('disabled', false);
                },
                error: function(e) {
                    App.loading(false);
                    $form.find('button[type="submit"]')
                        .text('Save')
                        .attr('disabled', false);
                    App.alert(e.responseText, 'Oops!');
                }
            });
        });
        
    </script>
      <script>
      Fancybox.bind("[data-fancybox]", {
        //
      }) 
    </script>
     <script type="text/javascript"
    src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAP_KEY')}}&v=weekly&libraries=places">
</script>
<script>

    var currentLat = <?php echo (isset($datamain) && $datamain->latitude) ? $datamain->latitude : 25.204819 ?>;
var currentLong = <?php echo (isset($datamain) && $datamain->longitude) ? $datamain->longitude : 55.270931 ?>;
$("#location").val(currentLat + "," + currentLong);

currentlocation = {
    "lat": currentLat,
    "lng": currentLong,
};
initMap();
initAutocomplete();

function initMap() {
    map2 = new google.maps.Map(document.getElementById('map_canvas'), {
        center: {
            lat: currentlocation.lat,
            lng: currentlocation.lng
        },
        zoom: 14,
        gestureHandling: 'greedy',
        mapTypeControl: false,
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
        },
        streetViewControlOptions: {
            position: google.maps.ControlPosition.LEFT_BOTTOM
        },
    });

    geocoder = new google.maps.Geocoder();

    // geocoder2 = new google.maps.Geocoder;
    usermarker = new google.maps.Marker({
        position: {
            lat: currentlocation.lat,
            lng: currentlocation.lng
        },
        map: map2,
        draggable: true,

        animation: google.maps.Animation.BOUNCE
    });


    //map click
    google.maps.event.addListener(map2, 'click', function(event) {
        updatepostition(event.latLng, "movemarker");
        //drag end event
        usermarker.addListener('dragend', function(event) {
            // alert();
            updatepostition(event.latLng, "movemarker");

        });
    });

    //drag end event
    usermarker.addListener('dragend', function(event) {
        // alert();
        updatepostition(event.latLng);

    });
}
updatepostition = function(position, movemarker) {
    geocodePosition(position);
    usermarker.setPosition(position);
    map2.panTo(position);
    map2.setZoom(15);
    let createLatLong = position.lat() + "," + position.lng();
    $("#location").val(createLatLong);
}

function geocodePosition(pos) {
    geocoder.geocode({
        latLng: pos
    }, function(responses) {
        if (responses && responses.length > 0) {
            usermarker.formatted_address = responses[0].formatted_address;
        } else {
            usermarker.formatted_address = 'Cannot determine address at this location.';
        }
        $('#txt_location').val(usermarker.formatted_address);
    });
}

function initAutocomplete() {
    // Create the search box and link it to the UI element.
    var input2 = document.getElementById('txt_location');
    var searchBox2 = new google.maps.places.SearchBox(input2);

    map2.addListener('bounds_changed', function() {
        searchBox2.setBounds(map2.getBounds());
    });

    searchBox2.addListener('places_changed', function() {
        var places2 = searchBox2.getPlaces();

        if (places2.length == 0) {
            return;
        }
        $('#txt_location').val(input2.value)

        var bounds2 = new google.maps.LatLngBounds();
        places2.forEach(function(place) {
            if (!place.geometry) {
                console.log("Returned place contains no geometry");
                return;
            }

            updatepostition(place.geometry.location);

            if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds2.union(place.geometry.viewport);
            } else {
                bounds2.extend(place.geometry.location);
            }
        });
        map2.fitBounds(bounds2);
    });
}
updatepostition = function(position, movemarker) {
    console.log(position);
    geocodePosition(position);
    usermarker.setPosition(position);
    map2.panTo(position);
    map2.setZoom(15);
    let createLatLong = position.lat() + "," + position.lng();
    // console.log("Address Lat/long="+createLatLong);
    $("#location").val(createLatLong);
}

</script>
<script>
    $(document).ready(function() {
    function loadArea(emirateId, selectedAreaId = ''){
        if (emirateId) {
            $.ajax({
                type: "GET",
                url: "{{ url('hospital/get-areas') }}/" + emirateId,
                success: function (res) {
                    if (res) {
                        $('#area_id').empty();
                        $('#area_id').append('<option value="">Select Area</option>');
                        $.each(res, function (index, area) {
                            $('#area_id').append('<option value="' + area.id + '">' + area.name_en + '</option>');
                        });

            
                        $('#area_id').val(selectedAreaId).trigger('change');
                        $('#area_id').select2(); // Reinitialize select2
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching area:', error);
                }
            });
        } else {
            $('#area_id').empty();
            $('#area_id').append('<option value="">Select Area</option>');
        }
    }
    // $('body').off("change",'#country');
    function loadEmirates(countryId, selectedEmirateId = ''){
        if (countryId) {
            $.ajax({
                type: "GET",
                url: "{{ url('hospital/get-emirates') }}/" + countryId,
                success: function (res) {
                    if (res) {
                        $('#emirate_id').empty();
                        $('#emirate_id').append('<option value="">Select Emirate</option>');
                        $.each(res, function (index, emirate) {
                            $('#emirate_id').append('<option value="' + emirate.id+'">' + emirate.name_en + '</option>');
                        });
                        $('#emirate_id').val(selectedEmirateId).trigger('change');
                        $('#emirate_id').select2(); // Reinitialize select2
                        if (selectedEmirateId) {
                            loadArea(selectedEmirateId, {{ $hospital->area_id ?? null }});
                        }
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching emirates:', error);
                }
            });
        } else {
            $('#emirate_id').empty();
            $('#emirate_id').append('<option value="">Select Emirate</option>');
        }
    }

    loadEmirates($('#country').val(), {{ $hospital->emirate_id ?? null }});

    $('#country').on("change", function () {
        loadEmirates($(this).val())
    });

    $('#emirate_id').on("change",function(){
        loadArea($(this).val());
    });
});

$('body').on("click", '[data-role="remove-imgs"]', function() {
            $(this).parent().parent().remove();
        });
let img_counter = $("#imgs_counter").val();
      $('[data-role="add-imgs"]').click(function() {
        img_counter++;
            var html = '<div class="form-group custom-upload col-lg-4 mb-4">\
                          <div class="remove_btn_imgs" style="height: 40px !important; width: 40px !important; border-radius: 5px; position: absolute; right: 13px; top: 31px;">\
                            <button type="button" class="btn btn-danger btn_remove_img" data-role="remove-imgs" style="height: 40px !important; width: 40px !important; border-radius: 5px;"><i class="bx bx-trash-alt"></i></button>\
                          </div>\
                            <label>Banner Image<b class="text-danger"></b></label><br>\
                            <img id="image-preview-bnr_'+img_counter+'" style="width:100%; height:160px; object-fit: cover" class="img-responsive" >\
                            <br><br>\
                            <input type="file" name="banners[]" class="form-control" data-role="file-image" data-preview="image-preview-bnr_'+img_counter+'" data-parsley-trigger="change" data-parsley-fileextension="jpg,png,gif,jpeg" data-parsley-fileextension-message="Only files with type jpg,png,gif,jpeg are supported" data-parsley-max-file-size="5120" data-parsley-max-file-size-message="Max file size should be 5MB" data-parsley-imagedimensions="750x750" required data-parsley-required-message="Select Image" >\
                                <span class="text-black d-block mt-2">Upload image with dimension 750px X 750px</span>\
                        </div>\
                        ';
                        $('#imgs-holder').append(html);

        });
        
    </script>
    </body>


</html>