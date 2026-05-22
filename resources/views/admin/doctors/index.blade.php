@extends("admin.template.layout")

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">
@stop
@section("header")
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css"
          href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/assets/css/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

@stop


<link href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.bootstrap5.min.css" rel="stylesheet">

@section("content")

    <div class="card mb-5">
        @if(get_user_permission('doctors','c'))
            <div class="card-header">
                <div class="col-lg-12">
                    @if($clinic)
                        <a href=" {{route('admin.clinics.index')}}" class="btn btn-primary float-end">Back</a>
                        <a href="{{route('admin.doctors.create', ['clinic_id' => $clinic->id])}}"
                           class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 "><i
                                class="mdi mdi-plus me-1"></i> Create Doctor</a>
                    @elseif($hospital)
                        <a href=" {{route('admin.hospitals.index')}}" class="btn btn-primary float-end">Back</a>
                        <a href="{{route('admin.doctors.create', ['hospital_id' => $hospital->id])}}"
                           class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 "><i
                                class="mdi mdi-plus me-1"></i> Create Doctor</a>
                    @else
                        <a href="{{route('admin.doctors.create')}}"
                           class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 "><i
                                class="mdi mdi-plus me-1"></i> Create Doctor</a>
                    @endif
                </div>
            </div>
        @endif
        <div class="card-body">
            <form action="#" id="search-form">
                <div class="row align-items-end mt-3 mx-2">
                    <div class="col-lg-3 col-md-6 mb-4">
                        <label class="form-label" for="username">From</label>
                        <div class="position-relative input-custom-icon">
                            <input type="text" name="booking_from" class="form-control flatpicker-input1" id="from_date"
                                   placeholder="From"/>
                            <span class="bx bx-calendar-event"></span>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-4">
                        <label class="form-label" for="username">To</label>
                        <div class="position-relative input-custom-icon">
                            <input type="text" name="booking_to" class="form-control flatpicker-input1" id="to_date"
                                   placeholder="To"/>
                            <span class="bx bx-calendar-event"></span>
                        </div>
                    </div>

                    @if(isset($hospitals) && !isset($_GET['hospital_id']) && !isset($_GET['clinic_id']))
                        <div class="col-lg-3 col-md-6 mb-4">
                            <label class="form-label" for="hospital">Select Hospital</label>
                            <div class="position-relative select-custom-icon">
                                <select name="hospital_id" id="hospital" class="select2-single"
                                        data-placeholder="Select Hospital">
                                    <option></option>
                                    @foreach($hospitals as $hospt)
                                        <option
                                            value="{{ $hospt->id }}" {{(($clinic->id ?? null) == $hospt->id || ($hospital->id ?? null) == $hospt->id) ? 'selected' : ''}} >{{ $hospt->name_en }}</option>
                                    @endforeach
                                </select>
                                <i class="fi fi-rr-bed-alt icon d-flex"></i>
                            </div>
                        </div>
                    @endif
                    @if(isset($departments) && !($clinic ?? null))
                        <div class="col-lg-3 col-md-6 mb-4">
                            <label class="form-label" for="department">Select Department</label>
                            <div class="position-relative select-custom-icon">
                                <select name="department_id" id="department" class="select2-single"
                                        data-placeholder="Select Department">
                                    <option></option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->title }}</option>
                                    @endforeach
                                </select>
                                <i class="fi fi-rr-bed-alt icon d-flex"></i>
                            </div>
                        </div>
                    @endif
                    @if(isset($specialities))
                        <div class="col-lg-3 col-md-6 mb-4">
                            <label class="form-label" for="speciality">Select Speciality</label>
                            <div class="position-relative select-custom-icon">
                                <select name="speciality_id" id="speciality" class="select2-single"
                                        data-placeholder="Select Speciality">
                                    <option></option>
                                    @foreach($specialities as $speciality)
                                        <option value="{{ $speciality->id }}">{{ $speciality->name_en }}</option>
                                    @endforeach
                                </select>
                                <i class="fi fi-rr-bed-alt icon d-flex"></i>
                            </div>
                        </div>
                    @endif
                    @if(isset($special_interestes))
                        <div class="col-lg-3 col-md-6 mb-4">
                            <label class="form-label" for="speciality">Select Special Interest</label>
                            <div class="position-relative select-custom-icon">
                                <select name="special_interest_id" id="special_interest" class="select2-single"
                                        data-placeholder="Select Special Interest">
                                    <option></option>
                                    @foreach($special_interestes as $special_interest)
                                        <option
                                            value="{{ $special_interest->id }}">{{ $special_interest->title }}</option>
                                    @endforeach
                                </select>
                                <i class="fi fi-rr-bed-alt icon d-flex"></i>
                            </div>
                        </div>
                    @endif
                    @if(isset($countries))
                        <div class="col-lg-3 col-md-6 mb-4">
                            <label class="form-label" for="speciality">Select Nationality</label>
                            <div class="position-relative select-custom-icon">
                                <select name="country_id" id="country" class="select2-single"
                                        data-placeholder="Select Nationality">
                                    <option></option>
                                    @foreach($countries as $countries)
                                        <option value="{{ $countries->id }}">{{ $countries->name }}</option>
                                    @endforeach
                                </select>
                                <i class="fi fi-rr-bed-alt icon d-flex"></i>
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-3 col-md-6 mb-4">
                        <label class="form-label" for="booking_status">Status</label>
                        <div class="position-relative select-custom-icon">
                            <select name="clinic_status" id="clinic_status" class="select2-single"
                                    data-placeholder="Select Type">
                                <option></option>
                                <option value="1">Active</option>
                                <option value="0">Deactive</option>

                            </select>
                            <i class="bx bx-calendar-event"></i>
                        </div>
                    </div>
                    <div class="col-sm mb-2">
                        <div class="d-flex gap-2">
                            <!-- <div class="mt-md-0 mb-3 me-3"> -->
                            <button type="submit" id="" class="btn btn-primary">Search</button>
                            <button type="button" id="clear-search" class=" btn btn-dark waves-effect waves-light">
                                Refresh
                            </button>
                            <a href="{{ route('admin.doctors.export') }}" id="export" class="btn btn-primary"><i
                                    class="mdi mdi-file-excel"></i> Export</a>
                            <!-- </div> -->

                        </div>
                    </div>
                </div>
                @if(request()->has('hospital_id'))
                    <input type="hidden" value="{{ request()->hospital_id }}" id="hospital_id" name="hospital_id">
                @endif
                @if(request()->has('clinic_id'))
                    <input type="hidden" value="{{ request()->clinic_id }}" id="hospital_id" name="hospital_id">
                @endif
            </form>
            <div class="table-responsive">
                <table class="table table-condensed table-striped" id="table_list">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Doctor Name</th>
                        <th>Email ID</th>
                        <th>Phone Number</th>
                        <th>Qualifications</th>
                        <th>{{$clinic ? 'Clinic' : 'Hospital'}}</th>
                        @if(!$clinic)
                            <th>Department</th>
                        @endif
                        <th>Speciality</th>
                        <th>Special Interest</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>
@stop

@section("page_script")
    <script src="{{asset('')}}admin-assets/assets/js/dataTables.min.js"></script>
    <script src="{{asset('')}}admin-assets/assets/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{asset('')}}admin-assets/assets/js/dataTables.min.js"></script>
    <script src="{{asset('')}}admin-assets/assets/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="{{asset('')}}admin-assets/assets/js/flatpickr.min.js"></script>

    <!-- Intel Input Js-->
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
            integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function () {
            function updateExportUrl() {
                let form = $('#search-form')[0];
                let formData = new FormData(form);
                let query = new URLSearchParams(formData).toString();
                let exportUrl = "{{ route('admin.doctors.export') }}" + '?' + query;
                $('#export').attr('href', exportUrl);
            }

            $('#search-form input, #search-form select').on('change', function () {
                updateExportUrl();
            });

            updateExportUrl();

            var hospital_id = "{{$hospital->id ?? null}}";
            var clinic_id = "{{$clinic->id ?? null}}";
            var columns = [
                {data: 'sl_no', orderable: false, searchable: false},
                {data: 'dr_name', name: 'users.name'},
                {data: 'email', name: 'users.email'},
                {data: 'phone_number', name: 'users.phone'},
                {data: 'qualifications', orderable: false, searchable: false},
                {data: 'hospital_name', name: 'hospitals.name_en'},
                {data: 'departments', orderable: false, searchable: false},
                {data: 'specialities', orderable: false, searchable: false},
                {data: 'interests', orderable: false, searchable: false},
                {data: 'status', orderable: false, searchable: false},
                {data: 'action', orderable: false, searchable: false}
            ];
            if (clinic_id) {
                columns = [
                    {data: 'sl_no', orderable: false, searchable: false},
                    {data: 'dr_name', name: 'users.name'},
                    {data: 'email', name: 'users.email'},
                    {data: 'phone_number', name: 'users.phone'},
                    {data: 'qualifications', orderable: false, searchable: false},
                    {data: 'hospital_name', name: 'hospitals.name_en'},
                    {data: 'specialities', orderable: false, searchable: false},
                    {data: 'interests', orderable: false, searchable: false},
                    {data: 'status', orderable: false, searchable: false},
                    {data: 'action', orderable: false, searchable: false}
                ];
            }

            var table = $('#table_list').DataTable({
                processing: true,
                serverSide: true,
                filter: true,
                ordering: false,
                searching: true,
                ajax: {
                    'type': 'POST',
                    'url': '{{ route("admin.doctors.load") }}',
                    'data': {
                        '_token': '{{csrf_token()}}',
                        'hospital_id': hospital_id,
                        'clinic_id': clinic_id
                    },
                    'data': function (d) {
                        // Send additional parameters to the server
                        d._token = '{{ csrf_token() }}';
                        d.hospital_id = hospital_id;
                        d.clinic_id = clinic_id;
                        d.search['filters'] = $('#search-form').serializeArray().reduce(function (obj, item) {
                            obj[item.name] = item.value;
                            return obj;
                        }, {});
                    }
                },
                columns: columns,
                dom: 'Bfrtip',

                buttons: [

                    // {
                    //     extend: 'excelHtml5',
                    //     text: '<i class="mdi mdi-file-excel"></i>',
                    //     titleAttr: 'Excel'
                    // },


                ],
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                pageLength: 10,
                order: [],
                language: {
                    loadingRecords: "No Data Available",
                },
            });

            // Implement search functionality
            $('#search-form').on('submit', function (e) {
                e.preventDefault();
                table.ajax.reload();
            });

            // Optional: Clear the search form
            $('#clear-search').on('click', function(e) {
        e.preventDefault();
        let form = $('#search-form')[0];
        form.reset();  // Reset the search form
        $(form).find('select').prop('selectedIndex', 0);

        fromDate.clear();
    toDate.clear();
    toDate.set('minDate', "today");

                $(form).find('select').each(function () {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).val(null).trigger('change');
                    }
                });
                table.ajax.reload();
            });
        });
    </script>
    <script>
        App.initFormView();
        $('body').off('submit', '#admin-form');
        $('body').on('submit', '#admin-form', function (e) {
            e.preventDefault();
            var $form = $(this);
            var formData = new FormData(this);
            $(".invalid-feedback").remove();

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
                success: function (res) {
                    App.loading(false);

                    if (res['status'] == 0) {
                        if (typeof res['errors'] !== 'undefined') {
                            var error_def = $.Deferred();
                            var error_index = 0;
                            jQuery.each(res['errors'], function (e_field, e_message) {
                                if (e_message != '') {
                                    $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                                    $('<div class="invalid-feedback">' + e_message + '</div>')
                                        .insertAfter($('[name="' + e_field + '"]').eq(0));
                                    if (error_index == 0) {
                                        error_def.resolve();
                                    }
                                    error_index++;
                                }
                            });
                            error_def.done(function () {
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
                        App.alert(res['message']);
                        setTimeout(function () {
                            window.location.href = App.siteUrl('/admin/admin_users');
                        }, 1500);
                    }

                    $form.find('button[type="submit"]')
                        .text('Save')
                        .attr('disabled', false);
                },
                error: function (e) {
                    App.loading(false);
                    $form.find('button[type="submit"]')
                        .text('Save')
                        .attr('disabled', false);
                    App.alert(e.responseText, 'Oops!');
                }
            });
        });

        $(".flatpicker-input").flatpickr({
            dateFormat: "d-m-Y",
            minDate: "today",
        });


        var fromDate = flatpickr("#from_date", {
            dateFormat: "d-m-Y",
            minDate: "",
            onChange: function (selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    toDate.set('minDate', selectedDates[0]);
                } else {
                    toDate.set('minDate', null);
                }
            }
        });

        var toDate = flatpickr("#to_date", {
            dateFormat: "d-m-Y",
            minDate: "today",
            onChange: function (selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    fromDate.set('maxDate', selectedDates[0]);
                } else {
                    fromDate.set('maxDate', null);
                }
            }
        });

        // $(".flatpicker-input-date-time").flatpickr({
        //     minDate: "today",
        //     enableTime: true,
        //     dateFormat: "d-m-Y H:i"
        // });

        $(".flatpicker-input-date-time").flatpickr({
            minDate: "today",
            enableTime: true,
            dateFormat: "d-m-Y H:i",
            onReady: function (selectedDates, dateStr, instance) {
                // Create OK button
                var okButton = document.createElement("button");
                okButton.innerText = "OK";
                okButton.classList.add("btn", "btn-primary", "ms-2");

                // Add click event to OK button
                okButton.addEventListener("click", function () {
                    instance.close();
                });

                // Create Clear button
                var clearButton = document.createElement("button");
                clearButton.innerText = "Clear";
                clearButton.classList.add("btn", "btn-outline-secondary"
                    , "waves-effect", "waves-light");

                // Add click event to Clear button
                clearButton.addEventListener("click", function () {
                    instance.clear();
                    // instance.close();
                });

                // Append OK and Clear buttons to flatpickr calendar
                var buttonContainer = document.createElement("div");
                buttonContainer.classList.add("flatpickr-button-container", "d-flex", "justify-content-end", "px-3", "pb-2");
                buttonContainer.appendChild(clearButton);
                buttonContainer.appendChild(okButton);

                instance.calendarContainer.appendChild(buttonContainer);
            }
        });

        $(".flatpicker-input-time").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true
        });
        $(".flatpicker-input-multiple").flatpickr({
            dateFormat: "d-m-Y",
            mode: "multiple",
            minDate: "today"
        });
        $(document).ready(function () {
            $('.select2-single').select2({
                placeholder: $(this).data('placeholder'),

            });
        });
        $('#hospital').on("change", function () {
            loadDepartments($(this).val())
        });

        function loadDepartments(hospital_id) {
            if (hospital_id) {
                $('#department').html('<option value="">Loading..</option>');
                $.ajax({
                    type: "GET",
                    url: "{{ url('admin/get-hospital-departments') }}/" + hospital_id,
                    success: function (res) {
                        if (res) {
                            // $('#department').empty();
                            $('#department').html('<option value="">Select Departments</option>');
                            $.each(res, function (index, department) {
                                $('#department').append('<option value="' + department.id + '">' + department.title + '</option>');
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching departments:', error);
                    }
                });
            } else {
                $('#department').empty();
                $('#department').append('<option value="">Select Departments</option>');
            }
        }
    </script>
@stop
