@extends("admin.template.layout")

@section("header")
<link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop
<link href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.bootstrap5.min.css" rel="stylesheet">

@section("content")

<div class="card mb-5">
    @if(get_user_permission('doctors','c'))
    <div class="card-header"><a href="{{route('admin.callcenter.create', ($hospital_id ?? null))}}" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 "><i class="mdi mdi-plus me-1"></i> Create Service Center</a></div>
    @endif
    <div class="card-body">
    <input type="hidden" value="{{$hospital_id ?? null}}" id="hospital_id" name="hospital_id">
        <form action="#" id="search-form">
            <div class="row align-items-end mt-3 mx-2">
                <div class="col-lg-3 col-md-4 mb-4">
                    <label class="form-label" for="username">From</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" name="from_date" class="form-control flatpicker-input1" id="from_date" placeholder="From" />
                        <span class="bx bx-calendar-event"></span>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 mb-4">
                    <label class="form-label" for="username">To</label>
                    <div class="position-relative input-custom-icon">
                        <input type="text" name="to_date" class="form-control flatpicker-input1" id="to_date" placeholder="To" />
                        <span class="bx bx-calendar-event"></span>
                    </div>
                </div>

                @if(isset($emirates))
                    <div class="col-lg-3 col-md-4 mb-4">
                        <label class="form-label" for="speciality">Select Emirate</label>
                        <div class="position-relative select-custom-icon">
                            <select name="emirate_id" id="emirate_id" class="select2-single" data-placeholder="Select Emirate">
                                <option></option>
                                @foreach($emirates as $emirate)
                                    <option value="{{ $emirate->id }}">{{ $emirate->name_en }}</option>
                                @endforeach
                            </select>
                        <i class="fi fi-rr-earth-americas icon d-flex"></i>
                        </div>
                    </div>
                @endif

                <div class="col-lg-3 col-md-4 mb-4">
                    <label class="form-label" for="speciality">Select Area</label>
                    <div class="position-relative select-custom-icon">
                        <select name="area_id" id="area_id" class="select2-single" data-placeholder="Select Area">
                        </select>
                        <i class="bx bx-navigation"></i>
                    </div>
                </div>

                <div class="col-12 mb-2">
                    <div class="d-flex gap-2">
                        <!-- <div class="mt-md-0 mb-3 me-3"> -->
                        <button type="submit" id="" class="btn btn-primary">Search</button>
                        <button type="button" id="clear-search" class=" btn btn-dark waves-effect waves-light">Refresh</button>
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
                <th>Service Center Name</th>
                <th>Email ID</th>
                <th>Phone Number</th>
                 <th>City</th>
                <th>Area</th>
                <th>Status</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
            </form>
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
<script src="{{asset('admin-assets/assets/js/flatpickr.min.js')}}"></script>

<script>
$(document).ready(function () {
    var value = $("#hospital_id").val();
    var table = $('#table_list').DataTable({
        processing: true,
        serverSide: true,
        filter: true,
        ordering: false,
        searching:true,
        ajax: {
            'type':'POST',
            'url' : '{{ route("admin.callcenter.load") }}',
            'data': function(d) {
                // Send additional parameters to the server
                d._token = '{{ csrf_token() }}';
                d.hospital_id = value;
                d.search['filters'] = $('#search-form').serializeArray().reduce(function(obj, item) {
                    obj[item.name] = item.value;
                    return obj;
                }, {});
            }
        },
        columns: [
            {data: 'sl_no', orderable: false, searchable: false},
            {data: 'name', name: 'users.name'},
            {data: 'email', name: 'users.email'},
            {data: 'phone_number', name: 'users.phone'},
            {data: 'emirate_name', name: 'emirates.name_en'},
            {data: 'area_name', name: 'areas.name_en'},
            {data: 'status',   orderable: false, searchable: false},
            {data: 'action',  orderable: false, searchable: false}
        ],

       lengthMenu: [ [10, 25, 50, 100], [10, 25, 50, 100] ],
       pageLength: 10,
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

        function loadArea(emirateId, selectedAreaId = ''){
            $('#area_id').html('<option disabled >Loading..</option>');
            if (emirateId) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('admin/get-areas') }}/" + emirateId,
                    success: function (res) {
                        if (res) {
                            // $('#area_id').empty();
                            $('#area_id').html('<option value="">Select Area</option>');
                            $.each(res, function (index, area) {
                                $('#area_id').append('<option value="' + area.id + '">' + area.name_en + '</option>');
                            });


                            $('#area_id').val(selectedAreaId).trigger('change');
                            $('#area_id').select2(); // Reinitialize select2
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching area:', error);
                    }
                });
            } else {
                $('#area_id').empty();
                $('#area_id').append('<option value="">Select Area</option>');
            }
        }

        $('#emirate_id').on("change",function(){
            loadArea($(this).val());
        });

    </script>
@stop
