@extends("admin.template.layout")
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/css/intlTelInput.css">
@stop
@section("header")
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/assets/css/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    @stop


    <link href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
@section("content")

<div class="card mb-5">
    @if(get_user_permission('hospitals','c'))
    <div class="card-header"><a href="{{route('admin.hospitals.create')}}" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 "><i class="mdi mdi-plus me-1"></i> Create Hospital</a></div>
    @endif
    <div class="card-body">
        @if(session()->has('success'))
            <div class="alert alert-success">
                {!! session()->get('success') !!}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger">
                {!! session()->get('error') !!}
            </div>
        @endif
        <form action="#" id="search-form">
            <div class="row align-items-end mt-3 mx-2">
                <div class="col-lg-3 col-md-4 mb-4">
                    <label class="form-label" for="username">From</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" name="booking_from" class="form-control flatpicker-input1" id="from_date" placeholder="From" />
                        <span class="bx bx-calendar-event"></span>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 mb-4">
                    <label class="form-label" for="username">To</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" name="booking_to" class="form-control flatpicker-input1" id="to_date" placeholder="To" />
                        <span class="bx bx-calendar-event"></span>
                    </div>
                </div>

                @if(isset($emirates))
                <div class="col-lg-3 col-md-6 mb-4">
                    <label class="form-label" for="emirate">City </label>
                    <div class="position-relative select-custom-icon">
                        <select name="emirate_id" id="emirate" class="select2-single" data-placeholder="Select Emirate">
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
                    <label class="form-label" for="booking_status">Status</label>
                    <div class="position-relative select-custom-icon">
                        <select name="hospital_status" id="hospital_status" class="select2-single" data-placeholder="Select Type">
                            <option></option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>

                        </select>
                        <i class='bx bx-info-circle' ></i>
                    </div>
                </div>
                <div class="col-sm mb-2">
                    <div class="d-flex gap-2">
                        <!-- <div class="mt-md-0 mb-3 me-3"> -->
                            <button type="submit" id="" class="btn btn-primary">Search</button>
                            <button type="button" id="clear-search" class=" btn btn-dark waves-effect waves-light">Refresh</button>
                            <a href="{{ route('admin.hospitals.export') }}" id="export" class="btn btn-primary"><i class="mdi mdi-file-excel"></i> Export</a>
                        <!-- </div> -->

                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <form autocomplete="off">
            <table class="table table-condensed table-striped" id="table_list">
                <thead>
                    <tr>
                    <th>#</th>
                    <th>Hospital Name</th>
                    <th>Email ID</th>
                    <th>Hospital Main Number</th>
                     <th>City</th>
                    <th>Country</th>
                    <th>Status</th>
                    <th>Profile Status</th>
                    <th>Action</th>
                    </tr>
                    <!-- <tr>
                    <td></td>
                    <td><input type="text" class="search-field" name="hospital_name"></input> </td>
                    <td><input type="text" class="search-field" name="email"></input></td>
                    <td><input type="text" class="search-field" name="phone"></input></td>
                    <td><input type="text" class="search-field" name="emirate"></input></td>
                    <td><input type="text" class="search-field" name="country"></input></td>
                    <td></td>
                    </tr> -->
                </thead>
                <tbody>

                </tbody>
            </table>
            </form>
        </div>
    </div>
</div>

<div id="importHospitalModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

      </div>
      <div class="modal-body">
      <form action="{{ route('admin.hospitals.import') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="file">Choose Excel file to import:</label>
                <input type="file" class="form-control" id="file" name="file" required accept=".xlsx, .xls">
            </div>
             <div class="form-group">
            <button type="submit" class="btn btn-primary">Import Data</button>
            <a href="{{route('admin.hospitals.export_excel')}}?blank=1"  style="padding:0 10px !important;">Export Blank Excel</a>
            </div>
        </form>
      </div>

    </div>

  </div>
</div>
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Reason</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="reject_id">
                <textarea id="reject_reason" class="form-control" placeholder="Enter reason"></textarea>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger" id="submitReject">Submit</button>
            </div>
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

   <!-- Intel Input Js-->
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@21.0.4/build/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
$('#excel-import').click(function(){
    $('#importHospitalModal').modal("show");
})
$(document).ready(function () {
    function updateExportUrl() {
        let form = $('#search-form')[0];
        let formData = new FormData(form);
        let query = new URLSearchParams(formData).toString();
        let exportUrl = "{{ route('admin.hospitals.export') }}" + '?' + query;
        $('#export').attr('href', exportUrl);
    }

    $('#search-form input, #search-form select').on('change', function() {
        updateExportUrl();
    });

    var table =  $('#table_list').DataTable({
        processing: true,
        serverSide: true,
        filter: true,
        ordering: false,
        searching: true,
        autoFill: false,
        ajax: {
            'type':'POST',
            'url' : '{{ route("admin.hospitals.load") }}',
            'data': function(d) {
                // Send additional parameters to the server
                d._token = '{{ csrf_token() }}';

                d.search['filters'] = $('#search-form').serializeArray().reduce(function(obj, item) {
                    obj[item.name] = item.value;
                    return obj;
                }, {});
            }
        },
        columns: [
            {data: 'sl_no', orderable: false, searchable: false},
            {data: 'name_en', name: 'hospitals.name_en'},
            {data: 'email', name: 'users.email'},
            {data: 'phone_number', name: 'users.phone'},
            {data: 'emirate_name', name: 'emirates.name_en'},
            {data: 'country_name', name: 'country.name'},
            {data: 'status',   orderable: false, searchable: false},
            {data: 'aprroval_status',   orderable: false, searchable: false},
            {data: 'action',  orderable: false, searchable: false}
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
});
    </script>
    <script>
        App.initFormView();
        $('body').off('submit', '#admin-form');
        $('body').on('submit', '#admin-form', function(e) {
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
                success: function(res) {
                    App.loading(false);

                    if (res['status'] == 0) {
                        if (typeof res['errors'] !== 'undefined') {
                            var error_def = $.Deferred();
                            var error_index = 0;
                            jQuery.each(res['errors'], function(e_field, e_message) {
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
                            error_def.done(function() {
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
                        setTimeout(function() {
                            window.location.href = App.siteUrl('/admin/admin_users');
                        }, 1500);
                    }

                    $form.find('button[type="submit"]')
                        .text('Save')
                        .attr('disabled', false);
                },
                error: function(e) {
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

    // $(".flatpicker-input-date-time").flatpickr({
    //     minDate: "today",
    //     enableTime: true,
    //     dateFormat: "d-m-Y H:i"
    // });

    $(".flatpicker-input-date-time").flatpickr({
        minDate: "today",
        enableTime: true,
        dateFormat: "d-m-Y H:i",
        onReady: function(selectedDates, dateStr, instance) {
                // Create OK button
                var okButton = document.createElement("button");
                okButton.innerText = "OK";
                okButton.classList.add("btn", "btn-primary", "ms-2");

                // Add click event to OK button
                okButton.addEventListener("click", function() {
                    instance.close();
                });

                // Create Clear button
                var clearButton = document.createElement("button");
                clearButton.innerText = "Clear";
                clearButton.classList.add("btn", "btn-outline-secondary"
                , "waves-effect",  "waves-light");

                // Add click event to Clear button
                clearButton.addEventListener("click", function() {
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
    $(document).ready(function() {
        $('.select2-single').select2({
            placeholder: $(this).data('placeholder'),

        });
        // $("#HospitalSelct", "#DepartmentSelct",  "#doctorSelct", "#PatientSelct").select2({ dropdownParent: "#appointment-modal" });
        $("#PatientSelct").select2({ dropdownParent: "#appointment-modal" });
        $("#doctorSelct").select2({ dropdownParent: "#appointment-modal" });
        $("#DepartmentSelct").select2({ dropdownParent: "#appointment-modal" });
        $("#HospitalSelct").select2({ dropdownParent: "#appointment-modal" });
        let selectedOption = $('#HospitalSelct').find('option:selected')
        if(selectedOption.data('type') == '{{TYPE_HOSPITAL}}'){
            $('#department-field').show();
        }else{
            $('#DepartmentSelct').html('<option value=""></option>');
            $('#department-field').hide();
        }

        $(document).on('change', '.approval-status', function () {

    let id = $(this).data('id');
    let url = $(this).data('url');
    let status = $(this).val();

    // ✅ APPROVED → Direct AJAX
    if (status === 'approved') {

        sendStatusAjax(id, status, url);

    }

    // ❌ REJECTED → Open modal
    else if (status === 'rejected') {

        $('#reject_id').val(id);
        $('#rejectModal').modal('show');

    }
});

$('#submitReject').on('click', function () {

    let id = $('#reject_id').val();
    let reason = $('#reject_reason').val();
    let url = $('.approval-status[data-id="'+id+'"]').data('url');

    if (reason.trim() === '') {
        alert('Please enter reject reason');
        return;
    }

    sendStatusAjax(id, 'rejected', url, reason);

    $('#rejectModal').modal('hide');
});

function sendStatusAjax(id, status, url, reason = '') {

    $.ajax({
        type: "POST",
        url: url,
        data: {
            id: id,
            status: status,
            reason: reason,
            _token: "{{ csrf_token() }}" // ✅ DIRECT FIX
        },
        dataType: 'json',

        success: function (res) {
            if (res.status == 0) {
                App.alert(res.message, 'Oops!');
                setTimeout(() => location.reload(), 1500);
            } else {
                App.alert(res.message);
            }
        },

        error: function (e) {
            App.alert(e.responseText, 'Error');
        }
    });
}

});
    </script>
@stop
