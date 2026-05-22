@include('callcenter.layouts.header')



<div class="card mb-5">

<div class="card-header card-header d-flex justify-content-between">
    @if($hospital_id)
    <a href="{{route('callcenter.createDoctor' , ['hospital_id' => $hospital_id])}}" class="btn btn-primary w-auto ml-auto"> Add Doctor</a>
    @elseif($clinic_id)
    <a href="{{route('callcenter.createDoctor' , ['clinic_id' => $clinic_id])}}" class="btn btn-primary w-auto ml-auto"> Add Doctor</a>
    @else
    <a href="{{route('callcenter.createDoctor')}}" class="btn btn-primary w-auto ml-auto"> Add Doctor</a>
    @endif
</div>

    <div class="card-body">
    <input type="hidden" value="{{$hospital_id ?? null}}" id="hospital_id" name="hospital_id">
    <input type="hidden" value="{{$clinic_id ?? null}}" id="clinic_id" name="clinic_id">
        <div class="table-responsive">
        <table class="table table-condensed table-striped" id="table_list">
            <thead>
                <tr>
                <th>#</th>
                <th>Doctors Name</th>
                <th>Email ID</th>
                <th>Clinic Direct Number to Book an Appointment</th>
                <th>Qualification</th>
                <th>Hospital Name</th>
                <th>Country</th>
                <th>Specialty</th>
                <th>Special Interest</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

        </div>
    </div>
</div>
@include('callcenter.layouts.footer')
<script src="{{asset('')}}admin-assets/assets/js/dataTables.min.js"></script>
<script src="{{asset('')}}admin-assets/assets/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    var hospital = $("#hospital_id").val();
    var clinic = $("#clinic_id").val();
    $('#table_list').DataTable({
        processing: true,
        serverSide: true,
        filter: true,
        searching: true,
        ajax: {
            type: 'POST',
            url: '{{ route("callcenter.load-data-doctors") }}',
            data: {
                '_token': '{{csrf_token()}}',
                'hospital_id': hospital,
                'clinic_id': clinic
            }
        },
        columns: [
            {data: 'sl_no', orderable: false, searchable: false},
            {data: 'user.name', name: 'user.name'},
            {data: 'user.email', name: 'user.email'},
            {data: 'phone_number', name: 'phone_number'},
            {data: 'doctor_qualifications', orderable: false, searchable: false},
            {data: 'hospital.name_en', name: 'hospital.name_en'},
            {data: 'country_name', name: 'country.name'},
            {data: 'doctor_specialities', orderable: false, searchable: false},
            {data: 'doctor_interests', orderable: false, searchable: false},
            {data: 'action', orderable: false, searchable: false}
        ],
        order: [],
        language: {
            loadingRecords: "No Data Available",
        },
    });


    // Implement search functionality
    $('#search-form').on('submit', function(e) {
        e.preventDefault();
        $('#table_list').DataTable().search($(this).serialize()).draw();
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


    </script>


    <script>
    $(document).ready(function() {
        // Select2 initialization (if needed)
        $('[role="select2"]').select2();

        // Handle record delete
        $('body').on('click', '[data-role="unlink"]', function(e) {
            e.preventDefault();
            var msg = $(this).data('message') || 'Are you sure that you want to delete this record?';
            var href = $(this).attr('href');

            App.confirm('Confirm Delete', msg, function() {
                // Perform AJAX delete request
                $.ajax({
                    url: href,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}", // Ensure this matches your Laravel setup
                    },
                    success: function(res) {
                        if (res.status == 1) {
                            App.alert(res.message || 'Deleted successfully', 'Success!');
                            setTimeout(function() {
                                window.location.reload(); // Refresh page after successful delete
                            }, 1500);
                        } else {
                            App.alert(res.message || 'Unable to delete the record.', 'Failed!');
                        }
                    },
                    error: function(jqXhr, textStatus, errorMessage) {
                        // Handle error
                    }
                });
            });


        });
    });
</script>


