@extends("admin.template.layout")

@section("header")
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <style>
        .rating-stars {
            color: #ffc107;
            font-size: 14px;
        }
        .rating-value {
            font-weight: bold;
            margin-left: 5px;
        }
        .rating-filter {
            min-width: 150px;
        }
    </style>
@stop

@section("content")

<div class="card mb-5">
    <div class="card-header">
        <h4 class="mb-0">{{ $page_heading }}</h4>
    </div>
    <div class="card-body">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.reports.doctors') }}" id="filter-form">
            <div class="row align-items-end mt-3 mx-2">
                <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label">Hospital/Clinic</label>
                    <select name="hospital_id" class="form-select select2">
                        <option value="">All Facilities</option>
                        @foreach($hospitals as $hospital)
                            <option value="{{ $hospital->id }}" {{ request('hospital_id') == $hospital->id ? 'selected' : '' }}>{{ $hospital->name_en }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-select select2">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>{{ $department->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label">Speciality</label>
                    <select name="speciality_id" class="form-select select2">
                        <option value="">All Specialities</option>
                        @foreach($specialities as $speciality)
                            <option value="{{ $speciality->id }}" {{ request('speciality_id') == $speciality->id ? 'selected' : '' }}>{{ $speciality->name_en }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select select2">
                        <option value="">All</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                
                <!-- NEW: Rating Filter Dropdown -->
                <div class="col-lg-2 col-md-4 mb-3">
                    <label class="form-label">Min Rating</label>
                    <select name="rating" class="form-select select2 rating-filter">
                        <option value="">All Ratings</option>
                        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>★★★★★ (5.0)</option>
                        <option value="4.5" {{ request('rating') == '4.5' ? 'selected' : '' }}>★★★★☆ (4.5+)</option>
                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>★★★★☆ (4.0+)</option>
                        <option value="3.5" {{ request('rating') == '3.5' ? 'selected' : '' }}>★★★☆☆ (3.5+)</option>
                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>★★★☆☆ (3.0+)</option>
                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>★★☆☆☆ (2.0+)</option>
                        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>★☆☆☆☆ (1.0+)</option>
                    </select>
                </div>
                
                <div class="col-sm mb-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route('admin.reports.doctors') }}" class="btn btn-dark">Reset</a>
                        <a href="{{ route('admin.reports.doctors.export', request()->all()) }}" class="btn btn-success">
                            <i class="mdi mdi-file-excel"></i> Export Excel
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <!-- Display active filters info -->
        @if(request('rating'))
        <div class="alert alert-info mt-3">
            <strong>Active Filter:</strong> Showing doctors with minimum rating of {{ request('rating') }} stars
            <a href="{{ route('admin.reports.doctors', request()->except('rating', 'page')) }}" class="float-end text-danger">Remove Filter</a>
        </div>
        @endif

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="bg-light">
                        <th>#</th>
                        <th>Doctor Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Hospital</th>
                        <th>Departments</th>
                        <th>Specialities</th>
                        <th>Experience</th>
                        <th>Rating</th>
                        <th>Reviews</th>
                        <th>Total Appointments</th>
                        <th>Completed</th>
                        <th>Status</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @forelse($doctors as $key => $doctor)
                    <tr>
                        <td>{{ $doctors->firstItem() + $key }}</td>
                        <td>
                            {{ $doctor->user->name ?? 'N/A' }}
                            @if($doctor->user->email_verified_at)
                                <i class="bx bx-check-circle text-success" title="Verified"></i>
                            @endif
                        </td>
                        <td>{{ $doctor->user->email ?? 'N/A' }}</td>
                        <td>{{ ($doctor->user->dial_code ?? '') . ($doctor->user->phone ?? '') }}</td>
                        <td>{{ $doctor->hospital->name_en ?? 'N/A' }}</td>
                        <td>{{ $doctor->departments->pluck('title')->implode(', ') ?: 'N/A' }}</td>
                        <td>{{ $doctor->specialities->pluck('name_en')->implode(', ') ?: 'N/A' }}</td>
                        <td>{{ $doctor->year_of_experiance ?? 'N/A' }} yrs</td>
                        <td>
                            @if($doctor->average_rating > 0)
                                <div class="d-flex align-items-center">
                                    
                                    <span class="rating-value">{{ number_format($doctor->average_rating, 1) }}</span>
                                </div>
                            @else
                                <span class="text-muted">No reviews</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $doctor->total_reviews }}</span>
                            @if($doctor->total_reviews > 0)
                                <button class="btn btn-sm btn-link view-reviews" 
                                        data-doctor-id="{{ $doctor->id }}" 
                                        data-doctor-name="{{ $doctor->user->name ?? 'Doctor' }}">
                                    View
                                </button>
                            @endif
                        </td>
                        <td>{{ $doctor->total_appointments }}</td>
                        <td>{{ $doctor->completed_appointments }}</td>
                        <td>
                            <span class="badge {{ $doctor->user->active == 1 ? 'bg-success' : 'bg-danger' }}">
                                {{ $doctor->user->active == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-horizontal-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                  
                                    <a class="dropdown-item" href="{{ route('admin.appointments.index', ['doctor_id' => $doctor->id]) }}" target="_blank">
                                        <i class="bx bx-calendar"></i> View Appointments
                                    </a>
                                    @if($doctor->total_reviews > 0)
                                        <a class="dropdown-item view-reviews" href="#" data-doctor-id="{{ $doctor->id }}" data-doctor-name="{{ $doctor->user->name ?? 'Doctor' }}">
                                            <i class="bx bx-star"></i> View Reviews ({{ $doctor->total_reviews }})
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="14" class="text-center">No doctors found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $doctors->appends(request()->all())->links() }}
        </div>
    </div>
</div>

<!-- Reviews Modal -->
<div class="modal fade" id="reviewsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Doctor Reviews</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="reviewsList"></div>
            </div>
        </div>
    </div>
</div>

@stop

@section("page_script")
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $('.select2').select2({
        width: '100%'
    });

    // View Reviews
    $('.view-reviews').click(function() {
        var doctorId = $(this).data('doctor-id');
        var doctorName = $(this).data('doctor-name');
        $('#reviewsModal .modal-title').text('Reviews for Dr. ' + doctorName);
        
        $.ajax({
            url: "{{ route('admin.get.doctor.reviews', '') }}/" + doctorId,
            type: "GET",
            dataType: "json",
            success: function(res) {
                var html = '';
                if(res.success && res.reviews && res.reviews.length > 0) {
                    html = '<div class="table-responsive"><table class="table table-bordered">';
                    html += '<thead><tr><th>#</th><th>Patient</th><th>Rating</th><th>Feedback</th><th>Date</th></tr></thead><tbody>';
                    $.each(res.reviews, function(i, review) {
                        var stars = '';
                        for(var s = 1; s <= 5; s++) {
                            if(s <= review.rating) {
                                stars += '<i class="bx bxs-star text-warning"></i>';
                            } else {
                                stars += '<i class="bx bx-star text-muted"></i>';
                            }
                        }
                        html += '<tr>';
                        html += '<td>' + (i+1) + '</td>';
                        html += '<td>' + (review.patient_name || 'N/A') + '</td>';
                        html += '<td>' + stars + ' (' + review.rating + ')' + '</td>';
                        html += '<td>' + (review.feeback_message || 'N/A') + '</td>';
                        html += '<td>' + review.created_at + '</td>';
                        html += '</tr>';
                    });
                    html += '</tbody></table></div>';
                } else {
                    html = '<p class="text-center text-muted">No reviews found for this doctor.</p>';
                }
                $('#reviewsList').html(html);
                $('#reviewsModal').modal('show');
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                $('#reviewsList').html('<p class="text-center text-danger">Error loading reviews. Please try again.</p>');
                $('#reviewsModal').modal('show');
            }
        });
    });
</script>
@stop