@include('agent.layouts.header')
<div class="card">
    <div class="card-body">
        <div class="position-relative">
        <form action="{{ route('agent.reports') }}" method="GET" id="search-form">
            <div class="row align-items-end">
                <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label" for="from_date">From</label>
                    <div class="position-relative input-custom-icon">
                    <input type="text" class="form-control flatpicker-input1" id="from_date" name="from_date" value="{{old('from_date', request('from_date'))}}" placeholder="From" />
                    <span class="bx bx-calendar-event"></span>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label" for="to_date">To</label>
                    <div class="position-relative input-custom-icon">
                    <input type="text" class="form-control flatpicker-input1" id="to_date" name="to_date" value="{{old('to_date', request('to_date'))}}" placeholder="To" />
                    <span class="bx bx-calendar-event"></span>
                    </div>
                </div>

                <!-- <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label" for="department_id">Select Department</label>
                    <div class="position-relative select-custom-icon">
                        <select name="department_id" id="department_id" class="select2-single" data-placeholder="Select Department">
                            <option></option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id', request('department_id')) == $department->id ? 'selected' : '' }}>{{ $department->department_name }}</option>
                            @endforeach
                        </select>
                        <i class="fi fi-rr-bed-alt icon d-flex"></i>
                    </div>
                </div> -->

                <div class="col-lg-3 col-md-6 mb-3">
                    <label class="form-label" for="doctor_id">Select Doctor</label>
                    <div class="position-relative select-custom-icon">
                        <select name="doctor_id" id="doctor_id" class="select2-single" data-placeholder="Select Doctor">
                            <option></option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id', request('doctor_id')) == $doctor->id ? 'selected' : '' }} >{{ $doctor->user->name }}</option>
                            @endforeach
                        </select>
                        <i class="fi fi-rr-user-md icon d-flex"></i>
                    </div>
                </div>

                <!-- Other filters -->

                <div class="col-lg-3 col-md-6 mb-3">
                        <label class="form-label" for="booking_status">Booking Status</label>
                        <div class="position-relative select-custom-icon">
                            <select name="booking_status" id="booking_status" class="select2-single" data-placeholder="Select Type">
                                <option></option>
                                <option value="{{BOOKING_STATUS_PENDING}}" {{ old('booking_status', request('booking_status')) == BOOKING_STATUS_PENDING ? 'selected' : '' }}>Pending</option>
                                <option value="{{BOOKING_STATUS_CONFIRMED}}" {{ old('booking_status', request('booking_status')) == BOOKING_STATUS_CONFIRMED ? 'selected' : '' }}>Confirmed</option>
                                <option value="{{BOOKING_STATUS_CANCELLED}}" {{ old('booking_status', request('booking_status')) == BOOKING_STATUS_CANCELLED ? 'selected' : '' }}>Cancelled</option>
                                <option value="{{BOOKING_STATUS_RESCHEDULED}}" {{ old('booking_status', request('booking_status')) == BOOKING_STATUS_RESCHEDULED ? 'selected' : '' }}>Rescheduled</option>
                                <option value="{{BOOKING_STATUS_COMPLETED}}" {{ old('booking_status', request('booking_status')) == BOOKING_STATUS_COMPLETED ? 'selected' : '' }}>Completed</option>
                            </select>
                            <i class='bx bx-sync'></i>
                        </div>
                    </div>

                <div class="col-sm">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex mt-3 mb-3">
                            <button type="submit" id="" class="btn btn-primary">Search</button>
                            <a href="{{route('agent.reports')}}" type="button"  class=" btn btn-info waves-effect waves-light">Refresh</a>
                            <a href="{{ route('agent.appointments.export') }}" id="export" class="btn btn-primary"><i class="mdi mdi-file-excel"></i> Export</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
            <!-- end row -->

            <div class="table-responsive">
                 <table id="base-style1" class="table table-striped table-bordered  align-middle">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Booking Id</th>
                        <th>Patient Name</th>
                        <th>Age</th>
                        <th>Doctor</th>
                        <!-- <th>Department</th> -->
                        <th>Specialties</th>
                        <th>Booking Status</th>
                        <th>Booking Date</th>
                        <!-- <th>Action</th> -->
                      </tr>
                    </thead>
                    <tbody>
                    @if($appointments->count() == 0)
                        <tr>
                            <td colspan="8">No Data Available</td>
                        </tr>
                    @endif
                    @foreach ($appointments as $key => $appointment)
                        <tr>
                              <td>
    {{ ($appointments->currentPage() - 1) * $appointments->perPage() + $loop->iteration }}
</td>
                            
                            <td>{{ $appointment->booking_id }}</td>
                            <td>
                            <a href="#!" class="patient-link"><img src="{{ $appointment->member ? ($appointment->member->user_img_url ?? null) : ($appointment->user->user_img_url ?? null) }}" width="32" height="32" class="me-2" alt="" />{{$appointment->member ? $appointment->member->full_name : (($appointment->user->first_name ?? '') . ' ' . ($appointment->user->last_name ?? ''))}}</a>
                            </td>
                            <td>{{$appointment->member ? $appointment->member->age : (calculate_age($appointment->user->dob ?? null))}}</td>
                            <td>DR {{$appointment->doctor->user->name ?? null}}</td>
                            <!-- <td>{{$appointment->department->title ?? ""}}</td> -->
                            <td>{{count($appointment->doctor->specialities ?? []) ? $appointment->doctor->specialities->pluck('name_en')->unique()->implode(', ') : null}}</td>
                            <td><div class="status-badge @if($appointment->booking_status == BOOKING_STATUS_PENDING) pending-badge
                                                                @elseif($appointment->booking_status == BOOKING_STATUS_COMPLETED) completed-badge
                                                                @elseif($appointment->booking_status == BOOKING_STATUS_CANCELLED) cancelled-badge
                                                                @elseif($appointment->booking_status == BOOKING_STATUS_CONFIRMED) confirmed-badge
                                                                @elseif($appointment->booking_status == BOOKING_STATUS_RESCHEDULED) reschedule-badge
                                                                @endif">
                                    <span></span> {{strtoupper($appointment->booking_status)}}
                                </div></td>
                            <td>{{ $appointment->booking_date }} | {{ $appointment->booking_time_slot }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                  </table>
                  <div class="mt-4">
                    <div class="col-sm-12 col-md-12 pull-right">
                        <span>
                            Showing
                            {{
                                (($appointments->currentPage() - 1) * $appointments->perPage()) + 1
                            }}
                            to
                            {{
                                min($appointments->currentPage() * $appointments->perPage(), $appointments->total())
                            }}
                            of {{$appointments->total()}} entries
                        </span>
                        <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                            {!! $appointments->appends(request()->input())->links('admin.template.pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('agent.layouts.footer')
<script>
function updateExportUrl() {
    let form = $('#search-form')[0];
    let formData = new FormData(form);
    let newQuery = new URLSearchParams(formData).toString();
    let exportElement = $('#export');
    let exportUrl = new URL(exportElement.attr('href') || "{{ route('agent.appointments.export') }}");

    let existingQueryParams = new URLSearchParams(exportUrl.search);
    let newQueryParams = new URLSearchParams(newQuery);

    for (let [key, value] of newQueryParams.entries()) {
        existingQueryParams.set(key, value);
    }

    exportUrl.search = existingQueryParams.toString();
    exportElement.attr('href', exportUrl.toString());
}

$('#search-form input, #search-form select').on('change', function() {
    updateExportUrl();
});

$(document).ready(function() {
        updateExportUrl();

        var fromDate = flatpickr("#from_date", {
            dateFormat: "d-m-Y",
            // minDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    toDate.set('minDate', selectedDates[0]);
                } else {
                    toDate.set('minDate', null);
                }
            }
        });

        var toDate = flatpickr("#to_date", {
            dateFormat: "d-m-Y",
            // minDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    fromDate.set('maxDate', selectedDates[0]);
                } else {
                    fromDate.set('maxDate', null);
                }
            }
        });
    });

</script>
