@include('agent.layouts.header')
<div class="position-relative mb-5">
    <div class="d-lg-flex">
    @include('agent.layouts.left_nav_profile')
        <!-- end chat-leftsidebar -->

        <div class="w-100 user-chat mt-4 mt-sm-0 ms-lg-3">
            <div class="card">
                <div class="p-4 pt-5">
                    <form id="hospital_form" action="{{route('agent.save_profile')}}" method="POST" class="registerform">
                           @csrf
                          <input type="hidden" value="{{$hospitalId}}" name="id">
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="username">Hospital Name</label>
                                <div class="position-relative input-custom-icon">
                                    <input type="text" class="form-control" id="name_en" name="name_en" value="{{$name}}" placeholder="Hospital Name" />
                                    <span class="bx bx-building"></span>
                                </div>
                            </div>
                            

                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="username">Country</label>
                                <div class="position-relative select-custom-icon">
                                    <select name="country" id="country" class="select2-single" data-placeholder="Select Country">
                                        @foreach($country_list as $country)
                                            <option {{($country->id==$country_id)?'selected':''}} value="{{$country->id}}">{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                    <i class="bx bx-navigation"></i>
                                </div>
                            </div>



                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="username">City / Province</label>
                                <div class="position-relative select-custom-icon">
                                    <select name="emirate_id" id="emirate_id" class="select2-single" data-placeholder="Select City">
                                        @foreach($emirates_list as $emirate)
                                            <option {{($emirate->id==$emirate_id)?'selected':''}} value="{{$emirate->id}}">{{$emirate->name_en}}</option>
                                        @endforeach
                                    </select>
                                    <i class="bx bx-navigation"></i>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="username">Area</label>
                                <div class="position-relative select-custom-icon">
                                    <select name="area_id" id="area_id" class="select2-single" data-placeholder="Select Area">
                                        @foreach($area_list as $area)
                                            <option {{($area->id==$area_id)?'selected':''}} value="{{$area->id}}">{{$area->name_en}}</option>
                                        @endforeach
                                    </select>
                                    <i class="bx bx-navigation"></i>
                                </div>
                            </div>
                            
                          
                            
                            <div class="col-12 mb-3">
                                <label class="form-label" for="username">Address Of Organization</label>
                                <div class="position-relative input-custom-icon">
                                    <input type="text" class="form-control" id="address" name="address" value="{{$hospital->address}}" placeholder="Enter Address Of Organization" />
                                    <span class="bx bx-building"></span>
                                </div>
                            </div>
        
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="username">Phone Number</label>
                                <div class="position-relative">
                                  <input type="text" class="form-control" id="phone" name="phone" value="{{ Auth::user()->phone }}" placeholder="Enter Phone Number" />
                                  <input type="hidden" id="dial_code" name="dial_code" value="{{ Auth::user()->dial_code }}">
                                    <!-- <span class="bx bx-phone"></span> -->
                                </div>
                            </div>
                            
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="username">Email Address</label>
                                <div class="position-relative input-custom-icon">
                                    <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" placeholder="Enter Address" readonly />
                                    <span class="bx bx-envelope"></span>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="username">Website</label>
                                <div class="position-relative input-custom-icon">
                                    <input type="text" class="form-control" id="website" value="{{$hospital->website}}" name="website" placeholder="Enter Website" />
                                    <span class="bx bx-globe"></span>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="username">Direct Call for Appointment Number</label>
                                <div class="position-relative">
                                 <input type="hidden" id="direct_dial_code" name="direct_dial_code" value="{{$hospital->appointment_dial_code}}">
                                 <input type="text" class="form-control" id="phone1" value="{{$hospital->appointment_phone}}" name="direct_phone" placeholder="Enter Phone Number" />
                                    <!-- <span class="bx bx-phone"></span> -->
                                </div>
                            </div>

                           
                            
                            <div class="col-12 mb-3">
                                <div class="custom-textarea">
                                    <label class="form-label" for="username">Hospital Profile</label>
                                    <textarea class="form-control" name="profile_bio" id="ckeditor-classic" row="5">{{$hospital->profile_description}}</textarea>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="custom-upload">
                                    <label for="uploadphotos" class="form-label">Hospital Images <span>(Allowed 3 Photos with Dim 750px X 750px)</span></label>
                                    <input class="form-control" type="file" id="upload_imgs" accept="image/jpg, image/jpeg" maxlength="3" multiple />
                                </div>
                                <div id="image_preview" class="row"></div>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary waves-effect waves-light" type="submit">Update</button>
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
@include('agent.layouts.footer')

<script src="{{ asset('') }}hospital/assets/js/ckeditor.js"></script>
<script src="{{ asset('') }}hospital/assets/js/form-editor.init.js"></script>


<script>

    $("#base-style").DataTable();
    function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').css('background-image', 'url('+e.target.result +')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#imageUpload").change(function() {
    readURL(this);
});
</script>


<script>
    $(document).ready(function() {
  var fileArr = [];
  $('#hospital_form').on('submit', function(e) {
            e.preventDefault(); // Prevent normal form submission
            
            var formData = new FormData(this); // Serialize form data
            let i = 0;
                $.each(fileArr, function (k, v) {
                    formData.append('images['+i+']', v);
                    i++;
                });
                
            
            
            // Submit form data via Ajax
            $.ajax({
                type: "POST",
                url: $(this).attr('action'), // Form action URL
                data: formData,
                contentType: false, // Tell jQuery not to set content type
                 processData: false,
                dataType: "json", // Expected data type from server
                success: function(response) {
                    
                    // Handle success response
                    console.log(response);
                    
                    // Check if redirect URL is provided in response
                    if (response.oData && response.oData.redirect) {
                        // Redirect to the specified URL
                        window.location.href = response.oData.redirect;
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        });
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
   var existingImages = @json($hospital->images ?? null); // Convert PHP array to JSON format
  
        function previewExistingImages() {
            if(existingImages){
                $('#image_preview').html("");
                for (var i = 0; i < existingImages.length; i++) {
                    $('#image_preview').append("<div class='img-div col-lg-4 col-md-6 mb-3 mt-2' id='existing-img-div" + existingImages[i].image_url + "'><img src='" + existingImages[i].image_url + "' class='img-fluid w-100' title='Existing Image " + (i + 1) + "'><div class='middle'><button id='action-icon' value='existing-img-div" + i + "' class='btn btn-danger btn-icon' role='existing_image'><i class='fa fa-trash'></i></button></div></div>");
                }
            }
        }

        previewExistingImages();
   
  
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


const input = document.querySelector("#phone");
        const ph=window.intlTelInput(input, {
            
         //   initialCountry: '{{INIT_PHONE_C_CODE}}',
            //strictMode: true,
            geoIpLookup:"auto",
            separateDialCode: true,
           
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
        });
        input.addEventListener("input", function () {
            // Get the selected country's dial code
            var dialCode = ph.getSelectedCountryData().dialCode;
            $('#dial_code').val(dialCode);

            // If you want to use the dial code somewhere else, you can do so here
        });

        const input1 = document.querySelector("#phone1");
        const iti = window.intlTelInput(input1, {
            
        //    initialCountry: '{{INIT_PHONE_C_CODE}}',
            //strictMode: true,
            geoIpLookup:"auto",
            separateDialCode: true,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/utils.js",
        });
        input1.addEventListener("input", function () {
            // Get the selected country's dial code
            var dialCode = iti.getSelectedCountryData().dialCode;
            $('#direct_dial_code').val(dialCode);

            // If you want to use the dial code somewhere else, you can do so here
        });
</script>

