@include('clinic.layouts.header')
<div class="position-relative mb-5">
<div class="card mb-5">

        <div class="card-header card-header d-flex justify-content-between">
            <a href="{{ route('clinic.createDoctor') }}" class="btn btn-primary w-auto ml-auto"> Add Doctor</a>
        </div>
        <div class="card-body">
            <div class="table-wrap" id="tableDiv">
                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered align-middle w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Doctor Name</th>
                                <th>Email ID</th>
                                <th>Clinic Direct Number to Book an Appointment</th>
                                <th>Qualification</th>
                                <!-- <th>Department</th> -->
                                <th>Specialty</th>
                                <th>Special Interest</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($doctors as $key => $doctor)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</td>
                                <td>{{ $doctor->email }}</td>
                                <td>{{ $doctor->dial_code ? ('+ '. $doctor->dial_code) : null }} {{ $doctor->phone }}</td>                                <td>
                                {{ $doctor->qualifications ? $doctor->qualifications->pluck('title')->implode(', ') : null}}
                                <!-- <td>
                                {{ $doctor->departments ? $doctor->departments->pluck('title')->implode(', ') : null}}
                                </td> -->
                                <td>
                                {{ $doctor->specialities ? $doctor->specialities->pluck('name_en')->implode(', ') : null}}
                                </td>
                                <td>
                                {{ $doctor->interests ? $doctor->interests->pluck('title')->implode(', ') : null}}
                                </td>
                                <td>
                                    <div class="dropdown mt-4 mt-sm-0">
                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bx bx-dots-horizontal-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item complete-link" href="{{route('clinic.doctordetail',['id'=>$doctor->id])}}">View Profile </a>
                                            <a class="dropdown-item cancel-link" href="{{route('clinic.editDoctor',['id'=>$doctor->id])}}">Edit Profile</a>
                                            <a class="dropdown-item delete" data-role="unlink" data-id="{{encrypt($doctor->id)}}"
                                                data-message="Do you want to remove the doctor?  This may be linked with other sections"
                                                href="{{ route('clinic.deleteDoctor', ['id' => encrypt($doctor->id)]) }}">
                                                <i class="flaticon-delete-1"></i> Delete Profile
                                            </a>
                                            <a class="dropdown-item complete-link" href="{{route('clinic.appointments',['id'=>$doctor->id])}}">View Appointments</a>
                                            <a class="dropdown-item complete-link" href="{{route('clinic.availability',['id'=>$doctor->id])}}">Schedule Appointment Slots</a>
                                            <a class="dropdown-item complete-link" href="{{route('clinic.temporaryunavailable',['id'=>$doctor->id])}}">Marks Temporary Unavailability </a>
                                            <a class="dropdown-item complete-link" href="{{route('clinic.holiday',['id'=>$doctor->id])}}">Mark Holiday Date </a>
                                            <a class="dropdown-item complete-link" href="{{route('clinic.instantappointment',['id'=>$doctor->id])}}">Mark Instant Appointment Date</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('clinic.layouts.footer')
<script>
    $("#example2").DataTable();
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('.delete').on('click', function(e) {
            var deleteDepartmentUrl = "{{ route('clinic.deleteDoctor', ['id' => ':id']) }}";
            e.preventDefault();
            var id = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request to delete department
                    $.ajax({
                        url: deleteDepartmentUrl.replace(':id', id),
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == '1') {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                );
                                setTimeout(function() {
                                    location.reload(); // Reload the page
                                }, 3000);
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            Swal.fire(
                                'Error!',
                                'Failed to delete department.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>
