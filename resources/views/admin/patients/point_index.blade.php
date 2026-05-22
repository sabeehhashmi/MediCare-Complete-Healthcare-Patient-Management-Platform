@extends("admin.template.layout")

@section("header")
<link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
<link href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
@stop

@section("content")

<div class="card mb-5">

    <div class="card-body">

        <form action="#" id="search-form">

            <div class="row align-items-end mt-3 mx-2">

                {{-- USER --}}
              <input type="hidden"
                               name="user_id"
                               class="form-control"
                               placeholder="Enter User ID" value="{{ $id }}">

                {{-- BOOKING ID --}}
                <div class="col-lg-3 col-md-4 mb-4">

                    <label class="form-label">Booking ID</label>

                    <div class="position-relative input-custom-icon">

                        <input type="text"
                               name="booking_id"
                               class="form-control"
                               id="booking_id_search"
                               placeholder="Search Booking ID"/>

                        <span class="bx bx-search"></span>

                    </div>
                </div>

                {{-- FROM DATE --}}
                <div class="col-lg-3 col-md-4 mb-4">

                    <label class="form-label">From</label>

                    <div class="position-relative input-custom-icon">

                        <input type="text"
                               name="booking_from"
                               class="form-control flatpicker-input1"
                               id="from_date"
                               placeholder="From"/>

                        <span class="bx bx-calendar-event"></span>

                    </div>
                </div>

                {{-- TO DATE --}}
                <div class="col-lg-3 col-md-4 mb-4">

                    <label class="form-label">To</label>

                    <div class="position-relative input-custom-icon">

                        <input type="text"
                               name="booking_to"
                               class="form-control flatpicker-input1"
                               id="to_date"
                               placeholder="To"/>

                        <span class="bx bx-calendar-event"></span>

                    </div>
                </div>
                <div class="col-lg-3 mb-2"></div>

                {{-- ACTION BUTTONS --}}
                <div class="col-sm mb-2">

                    <div class="d-flex gap-2">

                        <button type="submit" class="btn btn-primary">
                            Search
                        </button>

                        <button type="button"
                                id="clear-search"
                                class="btn btn-dark waves-effect waves-light">
                            Refresh
                        </button>

                        <a href="{{ route('front.points.export') }}"
                           id="export"
                           class="btn btn-primary d-none">

                            <i class="mdi mdi-file-excel"></i> Export
                        </a>

                    </div>
                </div>

                <div class="col-lg-12 mb-2"></div>
                <div class="col-lg-3 mb-2"><h5>Total earned points {{ $user->points + $user->used_points }}</h5></div>
                <div class="col-lg-3 mb-2"><h5>Available points {{ $user->points  }}</h5></div>
                <div class="col-lg-3 mb-2"><h5>Redeem Points {{  $user->used_points }}</h5></div>
            </div>
        </form>

        {{-- TABLE --}}
        <div class="table-responsive">

            <table class="table table-condensed table-striped" id="table_list11">

                <thead>

                <tr>
                    <th>#</th>
                    <th>Booking ID</th>
                    <th>Points</th>
                    <th>Type</th>
                    <th>Booking Status</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Clinic</th>
                    <th>Booking Date</th>
                    <th>Booking Time</th>
                    <th>Amount Paid</th>
                    <th>Created At</th>
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

        // EXPORT URL
        function updateExportUrl() {

            let form = $('#search-form')[0];

            let formData = new FormData(form);

            let query = new URLSearchParams(formData).toString();

            let exportUrl =
                "{{ route('front.points.export') }}" + '?' + query;

            $('#export').attr('href', exportUrl);
        }

        $('#search-form input, #search-form select').on('change', function () {

            updateExportUrl();
        });

        // DATATABLE
        var table = $('#table_list11').DataTable({

            processing: true,

            serverSide: true,

            filter: true,

            ordering: false,

            searching: true,

            ajax: {

                type: 'POST',

                url: '{{ route("admin.points-history.load") }}',

                data: function (d) {

                    d._token = '{{ csrf_token() }}';

                    d.search['filters'] = $('#search-form')
                        .serializeArray()
                        .reduce(function (obj, item) {

                            obj[item.name] = item.value;

                            return obj;

                        }, {});
                }
            },

            columns: [

                {
                    data: 'sl_no',
                    orderable: false,
                    searchable: false
                },

                {
                    data: 'booking_id',
                    name: 'booking_id'
                },

                {
                    data: 'points',
                    name: 'points'
                },

                {
                    data: 'type',
                    name: 'type'
                },

                {
                    data: 'booking_status',
                    name: 'booking_status'
                },

                {
                    data: 'patient',
                    name: 'patient'
                },
                
                {
                    data: 'doctor',
                    name: 'doctor'
                },

                {
                    data: 'clinic',
                    name: 'clinic'
                },

                {
                    data: 'booking_date',
                    name: 'booking_date'
                },

                {
                    data: 'booking_time',
                    name: 'booking_time'
                },
                {
                    data: 'consultation_fee',
                    name: 'consultation_fee'
                },

                {
                    data: 'created_at_formatted',
                    name: 'created_at'
                },
            ],

            order: [],

            language: {
                loadingRecords: "No Data Available",
            },

        });

        // SEARCH
        $('#search-form').on('submit', function (e) {

            e.preventDefault();

            table.ajax.reload();
        });

        // CLEAR SEARCH
        $('#clear-search').on('click', function (e) {

            e.preventDefault();

            let form = $('#search-form')[0];

            form.reset();

            $('#booking_id_search').val('');

            fromDate.clear();

            toDate.clear();

            $(form).find('select').each(function () {

                if ($(this).hasClass('select2-hidden-accessible')) {

                    $(this).val(null).trigger('change');
                }
            });

            table.ajax.reload();
        });

        // DATE PICKERS
        var fromDate = flatpickr("#from_date", {

            dateFormat: "d-m-Y",

            minDate: "",

            onChange: function (selectedDates) {

                if (selectedDates.length > 0) {

                    toDate.set('minDate', selectedDates[0]);

                } else {

                    toDate.set('minDate', null);
                }
            }
        });

        var toDate = flatpickr("#to_date", {

            dateFormat: "d-m-Y",

            minDate: "",

            onChange: function (selectedDates) {

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