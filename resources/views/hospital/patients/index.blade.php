@extends("hospital.template.layout")

@section("header")
<link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop
<link href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.bootstrap5.min.css" rel="stylesheet">

@section("content")

<div class="card mb-5">
    @if(get_user_permission('patients','c'))
    <div class="card-header"><a href="{{route('hospital.patients.create')}}" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 "><i class="mdi mdi-plus me-1"></i> Add Patient </a></div>
    @endif
    <div class="card-body">
        <form action="#" id="search-form">
            <div class="row align-items-end mt-3 mx-2">
                <div class="col-lg-3 col-md-4 mb-4">
                    <label class="form-label" for="username">From</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" name="booking_from" class="form-control flatpicker-input1" id="from_date"
                               placeholder="From"/>
                        <span class="bx bx-calendar-event"></span>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 mb-4">
                    <label class="form-label" for="username">To</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" name="booking_to" class="form-control flatpicker-input1" id="to_date"
                               placeholder="To"/>
                        <span class="bx bx-calendar-event"></span>
                    </div>
                </div>

                @if(isset($emirates))
                    <div class="col-lg-3 col-md-6 mb-4">
                        <label class="form-label" for="emirate">City </label>
                        <div class="position-relative select-custom-icon">
                            <select name="emirate_id" id="emirate" class="select2-single"
                                    data-placeholder="Select Emirate">
                                <option></option>
                                @foreach($emirates as $emirate)
                                    <option value="{{ $emirate->id }}">{{ $emirate->name_en }}</option>
                                @endforeach
                            </select>
                        <i class="fi fi-rr-earth-americas icon d-flex"></i>
                        </div>
                    </div>
                @endif

                <div class="col-lg-3 col-md-6 mb-4">
                    <label class="form-label" for="hospital_status">Status</label>
                    <div class="position-relative select-custom-icon">
                        <select name="active" id="" class="select2-single select2-nosearch"
                                data-placeholder="Select Type">
                            <option></option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>

                        </select>
                        <i class='bx bx-info-circle' ></i>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <label class="form-label" for="booking_status">Gender</label>
                    <div class="position-relative select-custom-icon">
                        <select name="gender" id="hospital_status" class="select2-single select2-nosearch"
                                data-placeholder="Select Type">
                            <option></option>
                            @foreach(['1'=> 'Male', '2'=> 'Female', '3' => 'Other'] as $gender_key => $gender_value)
                                <option {{($patient->gender ?? 0) == $gender_key ?'selected':''}} value="{{$gender_key}}">{{$gender_value}}</option>
                            @endforeach
                        </select>
                        <i class="fi fi-rr-venus-mars icon d-flex"></i>
                    </div>
                </div>
                <div class="col-sm mb-2">
                    <div class="d-flex gap-2">
                        <!-- <div class="mt-md-0 mb-3 me-3"> -->
                        <button type="submit" id="" class="btn btn-primary">Search</button>
                        <button type="button" id="clear-search" class=" btn btn-dark waves-effect waves-light">
                            Refresh
                        </button>
                        <a href="{{ route('hospital.patients.export') }}" id="export" class="btn btn-primary"><i
                                class="mdi mdi-file-excel"></i> Export</a>
                        <!-- </div> -->

                    </div>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-condensed table-striped" id="table_list11">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Email ID</th>
                        <th>Phone Number</th>
                        <th>WhatsApp Number</th>
                        <!-- <th>Language</th> -->
                        <th>Gender</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section("page_script")
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

    <script>
        $(document).ready(function () {
            function updateExportUrl() {
                let form = $('#search-form')[0];
                let formData = new FormData(form);
                let query = new URLSearchParams(formData).toString();
                let exportUrl = "{{ route('hospital.patients.export') }}" + '?' + query;
                $('#export').attr('href', exportUrl);
            }

            $('#search-form input, #search-form select').on('change', function() {
                updateExportUrl();
            });

            var table = $('#table_list11').DataTable({
                processing: true,
                serverSide: true,
                filter: true,
                ordering: false,
                searching: true,
                ajax: {
                    'type': 'POST',
                    'url': '{{ route("hospital.patients.load") }}',
                    'data': function (d) {
                        // Send additional parameters to the server
                        d._token = '{{ csrf_token() }}';

                        d.search['filters'] = $('#search-form').serializeArray().reduce(function (obj, item) {
                            obj[item.name] = item.value;
                            return obj;
                        }, {});
                    }
                },
                columns: [
                    {data: 'sl_no', orderable: false, searchable: false},
                    {data: 'name'},
                    {data: 'email'},
                    {data: 'phone_number', orderable: false},
                    {data: 'whatsapp_number', orderable: false},
                    {data: 'gender'},
                    {data: 'status', orderable: false, searchable: false},
                    {data: 'action', orderable: false, searchable: false}
                ],
                // dom: 'Bfrtip',
                // buttons: [
                //             {
                //             extend: 'excelHtml5',
                //             text: '<i class="mdi mdi-file-excel"></i>',
                //             titleAttr: 'Excel',
                //             exportOptions: {
                //                 columns: ':not(:last-child)', // Exclude the last column (action)
                //                 format: {
                //                     body: function (data, row, column, node) {
                //                         // Remove HTML tags and format the status column
                //                         if (column === 6) { // status column
                //                             return $(data).find('input').is(':checked') ? 'Active' : 'DeActive';
                //                         }
                //                         return $('<div>').html(data).text(); // Strip HTML tags
                //                     }
                //                 }
                //             }
                //         }
                //     ],
                order: [],
                language: {
                    loadingRecords: "No Data Available",
                },
            });

            // Implement search functionality
            $('#search-form').on('submit', function(e) {
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

                $(form).find('select').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).val(null).trigger('change');
                    }
                });
                table.ajax.reload();
            });

            var fromDate = flatpickr("#from_date", {
                dateFormat: "d-m-Y",
                minDate: "",
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
                minDate: "today",
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

    <script>
        App.initFormView();
    </script>
@stop
