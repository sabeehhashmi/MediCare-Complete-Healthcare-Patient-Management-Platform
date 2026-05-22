@include('agent.layouts.header')
<div class="position-relative mb-5">
    <div class="d-lg-flex">
    @include('agent.layouts.left_nav_profile')
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
                    <h5 class="font-size-16 mb-1 mt-3"><a href="#" class="text-reset">{{$name}}  </a></h5>
                </div>

                <div class="p-4 pt-0">
                    <div class="table-responsive mt-3 pb-3">
                        <table class="table align-middle table-sm table-wrap table-borderless table-centered mb-0">
                            <tbody>

                                <tr>
                                    <td class="text-muted">Gender :</td>
                                    <th class="fw-bold">{{ GENDERS[Auth::user()->agentDetails->gender] ?? 'N/A' }}</th>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <td class="text-muted">Email Address :</td>
                                    <th class="fw-bold">{{ Auth::user()->email }}</th>
                                </tr>
                                <tr>
                                    <td class="text-muted">Phone Number :</td>
                                    <th class="fw-bold">+{{ Auth::user()->dial_code }} {{ Auth::user()->phone }}</th>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <td class="text-muted">Cities:</td>
                                    <th class="fw-bold">
                                       {{$hospital->emirate->name_en}}
                                    </th>
                                </tr>
                               

                                <tr>
                                    <td class="text-muted">Call Center :</td>
                                    <th class="fw-bold">{{$hospital->country->name}}</th>
                                </tr>
                                
                                <tr>
                                    <td class="text-muted">Country :</td>
                                    <th class="fw-bold">{{$hospital->country->name}}</th>
                                </tr>
                                <!-- end tr -->
                               <!-- end tr -->
                             
                                               
                                              
                                              
                                <tr>
                                   <td class="text-muted">City :</td>
                                  <th class="fw-bold"> {{$hospital->emirate->name_en}}
                                  </th>
                                </tr>
                                <tr>
                                    <td class="text-muted">Area :</td>
                                    <th class="fw-bold">
                                    {{$hospital->area->name_en}}
                                    </th>
                                </tr>
                              
                                <tr>
                                    <td class="text-muted">Address :</td>
                                    <th class="fw-bold">{{ $hospital->address }} </th>
                                </tr>
                               
                                <!-- end tr -->
                              
                                <!-- end tr -->

                            </tbody>
                            <!-- end tbody -->
                        </table>
                    </div>
                </div>
            </div>

   
        </div>
        <!-- end user chat -->
    </div>
    <!-- End d-lg-flex  -->
</div>
@include('agent.layouts.footer')
<script>
    $(document).ready(function() {
        $('#imageUpload').on('change', function() {
            var formData = new FormData();
            var file = $('#imageUpload')[0].files[0];
            formData.append('image', file);
            formData.append('user_id', "{{$hospital->user_id}}");
            formData.append('_token', '{{ csrf_token() }}'); // Include CSRF token

            $.ajax({
                url: '{{ route("agent.save_profile_image") }}',
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