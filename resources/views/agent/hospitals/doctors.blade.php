@include('agent.layouts.header')
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">


<div class="card mb-5">
    @if(get_user_permission('doctors','c'))
    <div class="card-header"><a href="{{route('agent.doctors.create', ($hospital_id ?? null))}}" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 "><i class="mdi mdi-plus me-1"></i> Create Doctor</a></div>
    @endif
    <div class="card-body">
    <input type="hidden" value="{{$hospital_id ?? null}}" id="hospital_id" name="hospital_id">
        <div class="table-responsive">
        <table class="table table-condensed table-striped" id="table_list">
            <thead>
                <tr>
                <th>#</th>
                <th>Doctor Name</th>
                <th>Email ID</th>
                <th>Phone Number</th>
                <th>Country</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody>
               
            </tbody>
        </table>
        
        </div>
    </div>
</div>
@include('agent.layouts.footer')
<script src="{{asset('')}}admin-assets/assets/js/dataTables.min.js"></script>
<script src="{{asset('')}}admin-assets/assets/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    var value = $("#hospital_id").val();
    $('#table_list').DataTable({
        processing: true,
        serverSide: true,
        filter: true,
        searching:true,
        ajax: {
            'type':'POST',
            'url' : '{{ route("agent.doctors.load") }}',
            'data':{
                '_token': '{{csrf_token()}}',
                'hospital_id': value
            }
        },
        columns: [
            {data: 'sl_no'},
            {data: 'first_name'},
            {data: 'email'},
            {data: 'phone_number'},
            {data: 'country_name'},
            {data: 'action',  orderable: false, searchable: false}
        ],
        order: [] 
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
