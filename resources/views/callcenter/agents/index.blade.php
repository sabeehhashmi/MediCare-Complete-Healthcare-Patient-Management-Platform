@include('callcenter.layouts.header')



<div class="card mb-5">

<div class="card-header card-header d-flex justify-content-between">
            <a href="{{route('callcenter.agents.create')}}" class="btn btn-primary w-auto ml-auto"> Add Agent</a>
        </div>
    <div class="card-body">
    <!-- <input type="hidden" value="{{$hospital_id ?? null}}" id="hospital_id" name="hospital_id"> -->
    <!-- <div class="search-container">
    <input type="text" id="searchInput" placeholder="Search...">
</div>    -->
    <div class="table-responsive">

        <table class="table table-condensed table-striped" id="table_list">
            <thead>
                <tr>
                <th>#</th>
                <th>Agent Name</th>
                <th>Email ID</th>
                <th>Phone Number</th>
                <th>Country</th>
                 <th>City</th>
                <th>Area</th>
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
@include('callcenter.layouts.footer')
<script src="{{asset('')}}admin-assets/assets/js/dataTables.min.js"></script>
<script src="{{asset('')}}admin-assets/assets/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    var value = $("#hospital_id").val();
  var table =  $('#table_list').DataTable({
        processing: true,
        serverSide: true,

        ajax: {
            'type':'POST',
            'url' : '{{ route("callcenter.load-data-agents") }}',
            'data':{
                '_token': '{{csrf_token()}}',
                'hospital_id': value
            }
        },
        columns: [
            {data: 'sl_no', orderable: false, searchable: false},

            {
            data: 'name',
            orderable: false, searchable: false,
            render: function(data, type, full, meta) {
                return `<div class="flex-shrink-0 me-3">
                            <img class="rounded-circle avatar-sm" src="${full.user_img_url}" /> ${full.name}
                        </div>` ;
            }
        },
            {data: 'email', name:'users.email'},
            {data: 'phone_number', name:'users.phone'},
            {data: 'country_name',name:'country.name'},
            {data: 'emirate_name',name:'emirates.name_en'},
            {data: 'area_name',name:'areas.name_en'},

            {
                data: 'status',
                orderable: false, searchable: false,
                render: function(status, type, row, meta) {
                    // Check if status is 1 (active) or 0 (inactive)
                    var isChecked = (status === 1 ) ? 'checked' : '';

                    return '<div class="form-check form-switch form-switch-lg mb-0" dir="ltr">' +
                        '<input type="checkbox" class="form-check-input" id="switchHospital_' + row.id + '" data-agent-id="' + row.id + '" ' + isChecked + '>' +
                    '</div>';
                }
            },

            {data: 'action',  orderable: false, searchable: false}
        ],
        order: [],
        language: {
            loadingRecords: "No Data Available",
        },
        search: {
            "regex": true,
            "smart": true
        }
    });

    // Implement search functionality
    $('#searchInput').on('keyup', function () {
        table.search(this.value).draw();
    });
});
    </script>
    <script>
    $(document).on('change', '.form-check-input', function() {
    var agentId = $(this).data('agent-id');
    var isChecked = $(this).prop('checked');

    // AJAX request to update hospital status
    $.ajax({
        url: "{{route('callcenter.agents.agentStatus')}}", // Example URL for update status
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            agentId: agentId,
            status: isChecked ? 1 : 0
        },
        success: function(response) {
            // Handle success response
            console.log('Status updated successfully.');
        },
        error: function(xhr, status, error) {
            // Handle error
            console.error('Error updating status:', error);
        }
    });
});
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

