@extends("admin.template.layout")

@section("header")
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
@stop

@section("content")

<div class="card mb-5">
    <div class="card-header">
        <h4 class="mb-0">{{ $page_heading }}</h4>
    </div>
    <div class="card-body">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.reports.hospitals') }}" id="filter-form">
            <div class="row align-items-end mt-3 mx-2">
                <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select select2">
                        <option value="">All Types</option>
                        <option value="hospital" {{ request('type') == 'hospital' ? 'selected' : '' }}>Hospitals Only</option>
                        <option value="clinic" {{ request('type') == 'clinic' ? 'selected' : '' }}>Clinics Only</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label">City</label>
                    <select name="emirate_id" class="form-select select2">
                        <option value="">All Cities</option>
                        @foreach($emirates as $emirate)
                            <option value="{{ $emirate->id }}" {{ request('emirate_id') == $emirate->id ? 'selected' : '' }}>{{ $emirate->name_en }}</option>
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
                <div class="col-sm mb-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route('admin.reports.hospitals') }}" class="btn btn-dark">Reset</a>
                        <a href="{{ route('admin.reports.hospitals.export', request()->all()) }}" class="btn btn-success">
                            <i class="mdi mdi-file-excel"></i> Export Excel
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="bg-light">
                        <th>#</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Address</th>
                        <th>Doctors</th>
                        <th>Appointments</th>
                        <th>Contract Signed</th>
                        <th>Status</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @forelse($hospitals as $key => $hospital)
                    <tr>
                        <td>{{ $hospitals->firstItem() + $key }}</td>
                        <td>
                            {{ $hospital->name_en }}
                            @if($hospital->user->email_verified_at)
                                <i class="bx bx-check-circle text-success" title="Verified"></i>
                            @endif
                        </td>
                         <td>
                            @if($hospital->type == TYPE_HOSPITAL)
                                <span class="badge bg-primary">Hospital</span>
                            @else
                                <span class="badge bg-info">Clinic</span>
                            @endif
                        </td>
                        <td>{{ $hospital->user->email ?? 'N/A' }}</td>
                        <td>{{ ($hospital->user->dial_code ?? '') . ($hospital->user->phone ?? '') }}</td>
                        <td>{{ $hospital->emirate->name_en ?? 'N/A' }}</td>
                        <td>{{ Str::limit($hospital->address ?? 'N/A', 50) }}</td>
                        <td>{{ $hospital->doctor_count }}</td>
                        <td>{{ $hospital->appointment_count }}</td>
                        <td>
                            <span class="badge {{ $hospital->is_contract_signed ? 'bg-success' : 'bg-warning' }}">
                                {{ $hospital->is_contract_signed ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $hospital->user->active == 1 ? 'bg-success' : 'bg-danger' }}">
                                {{ $hospital->user->active == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                       
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center">No facilities found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $hospitals->appends(request()->all())->links() }}
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
</script>
@stop