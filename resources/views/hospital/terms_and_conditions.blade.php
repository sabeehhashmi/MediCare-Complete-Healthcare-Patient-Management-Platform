
<!doctype html>
<html lang="en">

    
<head>

        <meta charset="utf-8" />
        <title>Terms & Condition | Mednero</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- App favicon -->
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('') }}hospital/assets/images/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="assets/images/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="assets/images/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="assets/images/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="assets/images/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="assets/images/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="assets/images/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="assets/images/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="assets/images/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="assets/images/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
        <link rel="manifest" href="assets/images/favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="assets/images/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <!-- plugin css -->
        <link href="assets/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />

        <!-- Bootstrap Css -->
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        
        <link href="assets/css/flatpickr.min.css" rel="stylesheet" type="text/css" />
        <!-- Intel Input Css -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- App Css-->
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
        <link href="assets/css/custom.css" id="app-style" rel="stylesheet" type="text/css" />
        <style>
            .fancybox__content{
                padding: 0 !important;
                width: 100% !important;
                height: 100% !important;
            }
        </style>
</head>

<body>

<div class="authentication-bg min-vh-100">
        <!-- <div class="bg-overlay bg-light"></div> -->
        <div class="container">
            <div class="d-flex flex-column min-vh-100 px-3 pt-4">
                <div class="row justify-content-center my-auto">
                    <div class="col-12 mb-4">

                        

                        <div class="card">
                            <div class="card-body p-4"> 
                                <!--<div class="mb-4 pb-2">-->
                                <!--    <a href="" class="d-block auth-logo">-->
                                <!--        <img src="assets/images/Mednero.svg" alt="" height="60" class="auth-logo-dark me-start">-->
                                <!--        <img src="assets/images/Mednero.svg" alt="" height="60" class="auth-logo-light me-start">-->
                                <!--    </a>-->
                                <!--</div>-->
                                <div class="text-center mt-2">
                                    <h5>{{$datamain->title_en??''}}</h5>
                                </div>
                                
                                <div>
                                    <?=$datamain->desc_en??''?>
                                </div>
                                
                                <button class="btn btn-secondary mx-auto waves-effect waves-light" type="submit">Accept Terms & Conditions</button>
            
                            </div>
                        </div>

                    </div><!-- end col -->
                </div><!-- end row -->

                <!--<div class="row">-->
                <!--    <div class="col-lg-12">-->
                <!--        <div class="text-center p-4">-->
                <!--            <p class="text-white">© <script>document.write(new Date().getFullYear())</script> © Mednero.</p>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->

            </div>
        </div><!-- end container -->
    </div>
    <!-- end authentication section -->


    <!-- JAVASCRIPT -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Intel Input Js-->
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>
    <script src="assets/js/flatpickr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>

        $(document).ready(function() {
            $('.select2-single').select2({
                placeholder: $(this).data('placeholder'),

            });
        });
        
        $(".flatpicker-input").flatpickr({
            dateFormat: "d-m-Y",
            minDate: "today"
        });

        const input = document.querySelector("#phone");
        window.intlTelInput(input, {
            
         //   initialCountry: '{{INIT_PHONE_C_CODE}}',
            strictMode: true,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
        });
        
        const input1 = document.querySelector("#phone1");
        window.intlTelInput(input1, {
            
        //    initialCountry: '{{INIT_PHONE_C_CODE}}',
            strictMode: true,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
        });

        
    </script>

    <!-- <script src="assets/js/ckeditor.js"></script>
    <script src="assets/js/form-editor.init.js"></script> -->
 

    <script src="https://maps.google.com/maps/api/js?key=AIzaSyCtSAR45TFgZjOs4nBFFZnII-6mMHLfSYI"></script>
    <script src="assets/js/gmaps.min.js"></script>
    <script src="assets/js/gmaps.init.js"></script>
    <script src="assets/js/ckeditor.js"></script>
    <script src="assets/js/form-editor.init.js"></script>
    <script>
        $(document).ready(function() {
            var fileArr = [];
            $("#upload_imgs").change(function(){
                // check if fileArr length is greater than 0
                if (fileArr.length > 0) fileArr = [];
                
                    $('#image_preview').html("");
                    var total_file = document.getElementById("upload_imgs").files;
                    if (!total_file.length) return;
                    for (var i = 0; i < total_file.length; i++) {
                    if (total_file[i].size > 1048576) {
                        return false;
                    } else {
                        fileArr.push(total_file[i]);
                        $('#image_preview').append("<div class='img-div col-lg-4 col-md-6 mb-3 mt-2' id='img-div"+i+"'><img src='"+URL.createObjectURL(event.target.files[i])+"' class='img-fluid w-100' title='"+total_file[i].name+"'><div class='middle'><button id='action-icon' value='img-div"+i+"' class='btn btn-danger btn-icon' role='"+total_file[i].name+"'><i class='fa fa-trash'></i></button></div></div>");
                    }
                    }
            });
            
            $('body').on('click', '#action-icon', function(evt){
                var divName = this.value;
                var fileName = $(this).attr('role');
                $(`#${divName}`).remove();
                
                for (var i = 0; i < fileArr.length; i++) {
                    if (fileArr[i].name === fileName) {
                    fileArr.splice(i, 1);
                    }
                }
                document.getElementById('images').files = FileListItem(fileArr);
                evt.preventDefault();
            });
            
            function FileListItem(file) {
                        file = [].slice.call(Array.isArray(file) ? file : arguments)
                        for (var c, b = c = file.length, d = !0; b-- && d;) d = file[b] instanceof File
                        if (!d) throw new TypeError("expected argument to FileList is File or array of File objects")
                        for (b = (new ClipboardEvent("")).clipboardData || new DataTransfer; c--;) b.items.add(file[c])
                        return b.files
                    }
            });
    </script>
    </body>


</html>
