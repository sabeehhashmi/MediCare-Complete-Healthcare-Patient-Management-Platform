@include('clinic.layouts.header')
<div class="position-relative mb-5">
    <div class="d-lg-flex">
    @include('clinic.layouts.left_nav_profile')
        <!-- end chat-leftsidebar -->

        <div class="w-100 user-chat mt-4 mt-sm-0 ms-lg-3">
            <div class="card">
                <div class="text-center bg-light rounded px-4 py-3">
                    <div class="chat-user-status mt-4">
                        <img src="{{$hospital->user->user_img_url ?? null}}" class="avatar-md rounded-circle" alt="" />
                        <!-- <div class="">
                                                    <div class="status"></div>
                                                </div> -->
                    </div>
                    <h5 class="font-size-16 mb-1 mt-3"><a href="#" class="text-reset">{{$name}} Clinic </a></h5>
                </div>

                <div class="p-4 pt-0">
                    <div class="table-responsive mt-3 pb-3">
                        <table class="table align-middle table-sm table-wrap table-borderless table-centered mb-0">
                            <tbody>

                                <tr>
                                    <td class="text-muted">Name of the Clinic :</td>
                                    <th class="fw-bold">{{$name}} Clinic</th>
                                </tr>
                                <!-- end tr -->

                                <tr>
                                    <td class="text-muted">Country :</td>
                                    <th class="fw-bold">{{$country->name}}</th>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <td class="text-muted">Cities:</td>
                                    <th class="fw-bold">{{$hospital->emirate->name_en ?? null}}</th>
                                </tr>
                                <!-- end tr -->

                                <!--<tr>-->
                                <!--    <td class="text-muted">City :</td>-->
                                <!--    <th class="fw-bold">Abu Dhabi</th>-->
                                <!--</tr>-->
                                <tr>
                                    <td class="text-muted">Area :</td>
                                    <th class="fw-bold">
                                    {{$hospital->area->name_en ?? null}}
                                    </th>
                                </tr>
                               
                                <tr>
                                    <td class="text-muted">Address Of Organization :</td>
                                    <th class="fw-bold">{{$hospital->address}}</th>
                                </tr>
                                <tr>
                                    <td class="text-muted">Clinic Main Number :</td>
                                    <th class="fw-bold">+{{ Auth::user()->dial_code }} {{ Auth::user()->phone }}</th>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <td class="text-muted">Email Address :</td>
                                    <th class="fw-bold">{{ Auth::user()->email }}</th>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <td class="text-muted">Website :</td>
                                    <th class="fw-bold">{{$hospital->website}}</th>
                                </tr>
                                <!-- end tr -->

                               
                                <!-- end tr -->

                            </tbody>
                            <!-- end tbody -->
                        </table>
                    </div>
                </div>
            </div>

            <div class="p-3 mt-3">
                <div class="row text-center">
                    <div class="col-6 border-end">
                        <div class="p-1">
                            <h5 class="mb-1">{{$totalpatients}}</h5>
                            <p class="text-muted mb-0">Patients</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-1">
                            <h5 class="mb-1">{{$totaldoctors}}</h5>
                            <p class="text-muted mb-0">Doctors</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">

                    <h5 class="font-size-24 mb-3">Profile</h5>
                    <div class="mt-3">
                        <p class="text-muted"> <?php echo html_entity_decode($hospital->profile_description, ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                    @if(count($hospital->images))
                    <h5 class="font-size-24">Photos :</h5>
                    <div class="row">
                    @foreach ($hospital->images as $image)
                        <div class="col-lg-4 col-md-6 mb-3">
                            <img src="{{$image->image_url}}" class="img-fluid rounded object-fit-cover w-100 h-100" alt="">
                        </div>
                    @endforeach
                    </div>
                    @endif
                </div>
            </div>
            <div class="card mt-4">
    <div class="card-body">
        <h5 class="font-size-16 mb-3">Quick Actions</h5>

        <div class="row g-2">
            

            <div class="col-12">
                <button id="deleteAccountBtn" class="btn btn-danger w-100">
                    <i class="bx bx-trash"></i> Delete Account
                </button>
            </div>
        </div>

    </div>
</div>
        </div>
         
        <!-- end user chat -->
    </div>
    <!-- End d-lg-flex  -->
</div>
@include('clinic.layouts.footer')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#imageUpload').on('change', function() {
            var formData = new FormData();
            var file = $('#imageUpload')[0].files[0];
            formData.append('image', file);
            formData.append('user_id', "{{$hospital->user_id}}");
            formData.append('_token', '{{ csrf_token() }}'); // Include CSRF token

            $.ajax({
                url: '{{ route("clinic.save_profile_image") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        // Reload the page if the image is successfully uploaded
                        location.reload();
                    } else {
                        // Handle the error
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle the error
                    alert('An error occurred while uploading the image.');
                }
            });
        });
    });

</script>

<script>
$(document).on('click', '#deleteAccountBtn', function () {

    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: "{{ route('clinic.delete.account') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {

                    Swal.fire(
                        'Deleted!',
                        'Your account has been deleted.',
                        'success'
                    ).then(() => {
                        window.location.href = "{{ route('hospital.login') }}";
                    });

                },
                error: function () {
                    Swal.fire(
                        'Error!',
                        'Something went wrong.',
                        'error'
                    );
                }
            });

        }

    });

});
</script>