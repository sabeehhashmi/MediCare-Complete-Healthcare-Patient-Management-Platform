@include('doctor.template.header')


<div class="position-relative mb-5">
    <div class="d-lg-flex">
        <div class="chat-leftsidebar card mb-5">
            <div class="card-body" style="flex: 0;">
                <div class="text-center bg-light rounded px-4 py-3">
                    <div class="chat-user-status mt-4">
                        <div class="avatar-upload position-relative">
                            <div class="avatar-edit">
                                <input type='file' id="imageUpload" accept=".png, .jpg, .jpeg" />
                                <label for="imageUpload"></label>
                            </div>
                            <div class="avatar-preview">
                                <div id="imagePreview" class="avatar-md rounded-circle mx-auto">
                                    <img src="{{$doctor->user->user_img_url ?? null}}" class="avatar-md rounded-circle" alt="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <h5 class="font-size-16 mb-1 mt-3"><a href="{{ url('doctor/get_profile') }}" class="text-reset">DR {{$doctor->user->first_name ?? null}} {{$doctor->user->last_name ?? null}} </a></h5>
                    <p class="text-muted mb-0"> 
                    {{ count($doctor->specialities) ? $doctor->specialities->pluck('name_en')->implode(', ') : '' }}
                    </p>
                    <p class="text-muted mb-0">{{ $hospital->name_en ?? '' }}</p>
                </div>
            </div>

            <div class="mail-list">
                <a href="{{ url('doctor/get_profile') }}" class="active bg-primary-subtle">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-user-circle font-size-20 align-middle me-3"></i>
                        <div class="flex-grow-1">
                            <h5 class="font-size-14 mb-0">My Profile</h5>
                        </div>
                    </div>
                </a>
                <a href="{{ url('doctor/edit_profile') }}" class="border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-star-outline font-size-20 align-middle me-3"></i>
                        <div class="flex-grow-1">
                            <h5 class="font-size-14 mb-0">Edit Profile</h5>
                        </div>
                        <div class="flex-shrink-0"></div>
                    </div>
                </a>
                <!-- <a href="my-insurance.php" class="border-bottom">
                   <div class="d-flex align-items-center">
                       <i class="mdi mdi-newspaper-variant-outline font-size-20 align-middle me-3"></i>
                       <div class="flex-grow-1">
                           <h5 class="font-size-14 mb-0">My Insurance</h5>
                       </div>
                       <div class="flex-shrink-0"></div>
                   </div>
                </a> -->

                <a href="{{ url('doctor/change_password') }}" class="border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-key-outline font-size-20 align-middle me-3"></i>
                        <div class="flex-grow-1">
                            <h5 class="font-size-14 mb-0">Change Password</h5>
                        </div>
                        <div class="flex-shrink-0"></div>
                    </div>
                </a>

            </div>
        </div>
        <!-- end chat-leftsidebar -->

        <div class="w-100 user-chat mt-4 mt-sm-0 ms-lg-3">
            <div class="card">
                <div class="text-center bg-light rounded px-4 py-3">
                    <div class="chat-user-status mt-4">
                    <img src="{{$doctor->user->user_img_url ?? null}}" class="avatar-md rounded-circle" alt="" />
                    </div>
                    <h5 class="font-size-16 mb-1 mt-3"><a href="#" class="text-reset">DR {{$doctor->user->first_name ?? null}} {{$doctor->user->last_name ?? null}} </a></h5>
                    <p class="text-muted mb-0">{{ $hospital->name_en ?? '' }}</p>
                    <p class="text-muted mb-0">
                    {{ count($doctor->specialities) ? $doctor->specialities->pluck('name_en')->implode(', ') : '' }}
                    </p>
                    
                </div>

                <div class="p-4 pt-0">
                    <div class="table-responsive mt-3 pb-0">
                        <table class="table align-middle table-sm table-nowrap table-borderless table-centered mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted">Name of Hospital/Clinic Name/ Dental Care/ Home Care :</td>
                                    <th class="fw-bold">{{ $hospital->name_en ?? '' }}</th>
                                </tr>
                                <!-- end tr -->
                                 @if(count($doctor->departments))
                                <tr>
                                    <td class="text-muted">Department :</td>
                                    <th class="fw-bold">
                                        {{ $doctor->departments->pluck('title')->implode(', ')}}
                                    </th>
                                </tr>
                                @endif
                                <!-- end tr -->
                                <tr>
                                    <td class="text-muted">Doctor Name :</td>
                                    <th class="fw-bold">DR {{$doctor->user->first_name ?? null}} {{$doctor->user->last_name ?? null}}</th>
                                </tr>
                                <!-- end tr -->

                                <tr>
                                    <td class="text-muted">Gender :</td>
                                    <th class="fw-bold">{{ GENDERS[$doctor->gender] ?? 'N/A' }}</th>
                                </tr>

                                <tr>
                                    <td class="text-muted">Qualifications :</td>
                                    <th class="fw-bold">
                                    {{ count($doctor->qualifications) ? $doctor->qualifications->pluck('title')->implode(', ') : '' }} <th>
                                    </th>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <td class="text-muted">Specialties :</td>
                                    <th class="fw-bold">
                                    {{ count($doctor->specialities) ? $doctor->specialities->pluck('name_en')->implode(', ') : '' }}
                                   </th>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <td class="text-muted">Special Interest :</td>
                                    <th class="fw-bold">
                                    {{ count($doctor->interests) ? $doctor->interests->pluck('title')->implode(', ') : '' }}
                                    </th>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <td class="text-muted">Experience :</td>
                                    <th class="fw-bold">{{$doctor->year_of_experiance}}+</th>
                                </tr>
                                <!-- end tr -->

                                <tr>
                                    <td colspan="2">
                                        <table class="table table-sm table-nowrap table-striped table-bordered table-centered my-3">
                                        <thead class="">
                                            <th>Insurance Type</th>
                                            <th>Sub Insurances</th>
                                        </thead>
                                        <tbody>
                                            @foreach($insurances as $insurance)
                                            <tr>
                                                <td>
                                                    <b class="fw-bold">{{$insurance['insurance']->title ?? null}}</b>
                                                </td>
                                                <td>
                                                    <ul>
                                                    @foreach($insurance['sub_insurances'] as $sub_insurance)
                                                        <li>{{$sub_insurance->title ?? null}}</li>
                                                    @endforeach
                                                    </ul>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <!-- end tbody -->
                                    </table>
                                    </td>
                                </tr>  

                                <tr>
                                    <td class="text-muted">Country of Origin :</td>
                                    <th class="fw-bold">{{ $doctor->country->name ?? '' }}</th>
                                </tr>

                                <tr>
                                    <td class="text-muted">Languages Spoken :</td>
                                    <th class="fw-bold">
                                        {{ count($doctor->languages) ? $doctor->languages->pluck('title')->implode(', ') : '' }}
                                    </th>
                                </tr>
                                <!-- end tr -->
                                

                                 

                                <tr>
                                    <td class="text-muted"> Clinic Direct number to book an appointment</td>
                                    <th class="fw-bold">@if($doctor->user->phone !='') +{{$doctor->user->dial_code ?? null}} {{$doctor->user->phone ?? null}} @else NA @endif </th>
                                </tr>
                                <tr>
                                    <td class="text-muted">Doctor Direct number to book an appointment</td>
                                    <th class="fw-bold">@if($doctor->appointment_phone !='') +{{$doctor->appointment_dial_code ?? null}} {{$doctor->appointment_phone}} @else NA @endif</th>
                                </tr>

                                <tr>
                                    <td class="text-muted">Email</td>
                                    <th class="fw-bold">{{$doctor->user->email ?? null}}</th>
                                </tr>
                                
                                <!-- <tr>
                                    <td class="text-muted">Available for instant appointment :</td>
                                    <th class="fw-bold">Yes</th>
                                </tr> -->
                                <!-- end tr -->

                                

                                


                                <!-- <tr>
                                    <td class="text-muted">Call Center Number</td>
                                    <th class="fw-bold">+91 5968268555</th>
                                </tr> -->
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
                            <h5 class="mb-1">{{$totalPatients}}</h5>
                            <p class="text-muted mb-0">Patients</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-1">
                            <h5 class="mb-1">{{$totalAppointment}}</h5>
                            <p class="text-muted mb-0">Appointments</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="font-size-16 mb-3">About Me</h5>
                    <div class="mt-3">
                        <p class="font-size-15"><?php echo html_entity_decode($doctor->profile_desciription, ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </div>
            </div>
            <!-- Add Chat Button after About Me section -->
            <div class="card mt-4">
    <div class="card-body">
        <h5 class="font-size-16 mb-3">Quick Actions</h5>

        <div class="row g-2">
            <div class="col-12">
                <a href="{{ route('doctor.chat.index') }}" class="btn btn-primary w-100">
                    <i class="bx bx-chat"></i> View All Messages
                </a>
            </div>

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
@include('doctor.template.footer')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        
        $('#imageUpload').on('change', function() {
            var formData = new FormData();
            var file = $('#imageUpload')[0].files[0];
            formData.append('image', file);
            formData.append('user_id', "{{$doctor->user_id}}");
            formData.append('_token', '{{ csrf_token() }}'); // Include CSRF token

            $.ajax({
                url: '{{ route("doctor.save_profile_image") }}',
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
                url: "{{ route('doctor.delete.account') }}",
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
                        window.location.href = "{{ route('doctorlogin.login') }}";
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