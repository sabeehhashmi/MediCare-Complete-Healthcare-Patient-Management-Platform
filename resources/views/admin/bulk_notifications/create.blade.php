@extends('admin.template.layout')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form id="bulk-notification-form" action="{{ route('admin.bulk_notifications.store') }}" method="POST">
                    @csrf
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label">User Type</label>
                        <div class="col-sm-9">
                            <select name="user_type" id="user_type" class="form-control select2">
                                <option value="">Select Target Type</option>
                                @foreach($roles as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4" id="specific_user_container">
                        <label class="col-sm-3 col-form-label">Specific Users (Optional)</label>
                        <div class="col-sm-9">
                            <select name="user_ids[]" id="user_ids" class="form-control" multiple>
                                <!-- Populated via AJAX -->
                            </select>
                            <small class="text-muted">If no users are selected, the notification will be sent to ALL users of the selected type.</small>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label">Title</label>
                        <div class="col-sm-9">
                            <input type="text" name="title" class="form-control" placeholder="Notification Title" required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label">Description</label>
                        <div class="col-sm-9">
                            <textarea name="description" class="form-control" rows="4" placeholder="Notification Description" required></textarea>
                        </div>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-sm-9">
                            <div>
                                <button type="submit" id="submit-btn" class="btn btn-primary w-md">Send Notification</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('page_script')
<script>
    $(document).ready(function() {
        // Function to toggle specific user container
        function toggleSpecificUsers() {
            var selectedOption = $('#user_type option:selected');
            var selectedType = String($('#user_type').val());
            var selectedText = selectedOption.text() ? selectedOption.text().toUpperCase() : '';
            
            // Role IDs: 3 (Agent), 4 (Service Center/Call Center), 5 (Hospital/Clinic), 8 (Service Center/Clinic)
            var hideTypes = ['3', '4'];
            
            var shouldHide = hideTypes.indexOf(selectedType) !== -1 || 
                             selectedText.indexOf('SERVICE') !== -1 || 
                             selectedText.indexOf('AGENT') !== -1 ;

            if (shouldHide) {
                $('#specific_user_container').hide();
            } else {
                $('#specific_user_container').show();
            }
        }

        // Initialize Type Select2 (Single)
        $('#user_type').select2({
            placeholder: "Select Target Type"
        });

        // Initialize Users Select2 (Multi, AJAX)
        $('#user_ids').select2({
            placeholder: "Search by Name, Email, or Patient ID (Leave empty for All)",
            allowClear: true,
            ajax: {
                url: "{{ route('admin.bulk_notifications.get_users') }}",
                dataType: 'json',
                type: "POST",
                delay: 250,
                data: function (params) {
                    return {
                        _token: "{{ csrf_token() }}",
                        q: params.term,
                        page: params.page,
                        type: $('#user_type').val()
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            }
        });

        // Toggle on load
        toggleSpecificUsers();

        // Reset user selection when type changes
        $('#user_type').on('change', function() {
            toggleSpecificUsers();
            $('#user_ids').val(null).trigger('change');
        });

        // Form submission via AJAX
        $('#bulk-notification-form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = $('#submit-btn');
            
            if (typeof App !== 'undefined' && App.loading) {
                App.loading(true);
            }
            btn.prop('disabled', true);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(res) {
                    if (typeof App !== 'undefined' && App.loading) {
                        App.loading(false);
                    }
                    if (res.status == 1) {
                        if (typeof App !== 'undefined' && App.alert) {
                            App.alert(res.message, 'Success!');
                        } else {
                            alert(res.message);
                        }
                        setTimeout(function() {
                            window.location.href = res.oData.redirect;
                        }, 1500);
                    } else {
                        if (typeof App !== 'undefined' && App.alert) {
                            App.alert(res.message || 'Something went wrong', 'Oops!');
                        } else {
                            alert(res.message || 'Something went wrong');
                        }
                        btn.prop('disabled', false);
                    }
                },
                error: function(err) {
                    if (typeof App !== 'undefined' && App.loading) {
                        App.loading(false);
                    }
                    btn.prop('disabled', false);
                    if (typeof App !== 'undefined' && App.alert) {
                        App.alert('Error sending notification', 'Oops!');
                    } else {
                        alert('Error sending notification');
                    }
                }
            });
        });
    });
</script>
@stop
