@extends('admin.layouts.master')
@section('title')
    {{$page_heading??''}}
@endsection
@section('page-title')
    {{$page_heading??''}}
@endsection
@section('body')

    <body>
    @endsection
    @section('content')
    <!-- start content here -->

    @endsection
    @section('scripts')

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js" integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.js" integrity="sha512-Fq/wHuMI7AraoOK+juE5oYILKvSPe6GC5ZWZnvpOO/ZPdtyA29n+a5kVLP4XaLyDy9D1IBPYzdFycO33Ijd0Pg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
    <script src="{{URL::asset('web/js/parsley.min.js')}}"></script>
    <script src="{{URL::asset('web/js/parsley.js')}}"></script>
    <script src="{{ URL::asset('js/app.js') }}"></script>
    
    @yield('page_script')
    <!-- App js -->
    <script src="{{ URL::asset('admin-assets/assets/js/app.js') }}"></script>
    <script src="{{asset('/')}}admin-assets/assets/js/pages/pass-addon.init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <!-- SweetAlert2 CSS -->


        <script>
        $('[role="select2"]').select2();
            // Handle record delete
        $('body').off('click', '[data-role="unlink"]');
        $('body').on('click', '[data-role="unlink"]', function(e) {
            e.preventDefault();
            var msg = $(this).data('message') || 'Are you sure that you want to delete this record?';
            var href = $(this).attr('href');

            App.confirm('Confirm Delete', msg, function() {
                var ajxReq = $.ajax({
                    url: href,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        if (res['status'] == 1) {
                            App.alert(res['message'] || 'Deleted successfully', 'Success!');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);

                        } else {
                            App.alert(res['message'] || 'Unable to delete the record.',
                                'Failed!');
                        }
                    },
                    error: function(jqXhr, textStatus, errorMessage) {

                    }
                });
            });

        });
        $(document).on('change', '.change_status', function() {
            status = 0;
            if (this.checked) {
                status = 1;
            }

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: $(this).data('url'),
                data: {
                    "id": $(this).data('id'),
                    'status': status,
                    "_token": "{{ csrf_token() }}"
                },
                timeout: 600000,
                dataType: 'json',
                success: function(res) {
                    App.loading(false);

                    if (res['status'] == 0) {
                        var m = res['message']
                        App.alert(m, 'Oops!');
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    } else {
                        App.alert(res['message']);
                    }
                },
                error: function(e) {
                    App.alert(e.responseText, 'Oops!');
                }
            });
        });

        var validNumber = new RegExp(/^\d*\.?\d*$/);
var lastValid = 0;
function validateNumber(elem) {
  if (validNumber.test(elem.value)) {
    lastValid = elem.value;
  } else {
    elem.value = lastValid;
  }
}

$('.reset-form').on('click', function() {
    var form = $(this).closest('form')[0];
    form.reset();
    $(form).find('input[type="text"], input[type="password"], input[type="email"], input[type="number"], input[type="tel"], input[type="url"]').val('');

    $(form).find('input[type="checkbox"], input[type="radio"]').prop('checked', false);

    $(form).find('select').prop('selectedIndex', 0);

    $(form).find('select').each(function() {
        if ($(this).hasClass('select2-hidden-accessible')) {
            $(this).val(null).trigger('change');
        }
    });

    $(form).find('textarea').val('');

    const domEditableElement = document.querySelector('.ck-editor__editable');

    const editorInstance = domEditableElement.ckeditorInstance;
    editorInstance.setData('');
});

$(document).on('click', '.reset-modal', function(){
    var modalId = $(this).data('bs-target');
    var form = $(modalId).find('form')[0];
    if (form) {
        form.reset();
        $(form).find('input[type="text"], input[type="password"], input[type="email"], input[type="number"], input[type="tel"], input[type="url"]').val('');
        $(form).find('input[type="checkbox"], input[type="radio"]').prop('checked', false);
        $(form).find('select').prop('selectedIndex', 0);
        $(form).find('select').each(function() {
            if ($(this).hasClass('select2-hidden-accessible')) {
                $(this).val(null).trigger('change');
            }
        });
        $(form).find('textarea').val('');
    }
});


        </script>
        <script>
            $('#upload-docs #confirm_docs_upload').on('click', function (e) {
                 e.preventDefault();
             
                 var $form = $('#confirm_docs_upload_form');
                 var formData = new FormData($form[0]);
                 var btn=$('#confirm_docs_upload');
                 var modal=$('#upload-docs');
                 btn.html('Uploading..');
             
                 btn.prop('disabled', true);
                    
                 App.loading(true);
             
                 $.ajax({
                     type: "POST",
                     url: $form.attr('action'),
                     data: formData,
                     processData: false, // REQUIRED
                     contentType: false, // REQUIRED
                     cache: false,
                     dataType: 'json',
             
                     success: function (res) {
                         setTimeout(() => {
                 window.location.reload();
             }, 2000);
             
                 App.loading(false);
                 btn.prop('disabled', false);
                 btn.html('Upload Documents');
                 $form[0].reset();
             
             // ✅ CLOSE MODAL (Bootstrap 5 safe way)
             
             modal.modal('hide');
             
                 if (res.status === 1) {
             
                     App.alert(res.message || 'Documents uploaded successfully', 'Success!', 'success');
             
                     // ✅ RESET FORM
                    
             
                     // OPTIONAL redirect
                     if (res.oData && res.oData.redirect) {
                         setTimeout(() => {
                             window.location.href = res.oData.redirect;
                         }, 1500);
                     }
             
                 } else {
                     App.alert(res.message || 'Upload failed', 'Oops!', 'error');
                 }
             },
                     error: function (e) {
                         $('#confirm_docs_upload').prop('disabled', false);
                         App.loading(false);
                         console.error(e);
                         App.alert("Network error, please try again", 'Oops!', 'error');
                     }
                 });
             });
             
             $(document).on('click', '.upload-link', function() {

var booking_data = $(this).data('booking-data');
var booking_id   = $(this).data('booking-id');
var file_type    = $(this).data('file-type');

if (file_type === 'xray') {
    $('.xray_upload').show();
    $('.lab_upload').hide();
} 
else if (file_type === 'lab') {
    $('.xray_upload').hide();
    $('.lab_upload').show();
}

$('#upload-docs, #idUplpad').val(booking_id);
$('#upload-docs .modal-title')
    .text('Upload Documents - ' + booking_data.booking_id);
});
</script>
<script>
$(document).ready(function () {

    

    // Disable autocomplete on all forms
    $('form').attr('autocomplete', 'off');

    // Handle all fields
    $('input, textarea, select').each(function () {

        // Disable autocomplete
        $(this).attr('autocomplete', 'off');

        // Extra security for passwords
        if ($(this).attr('type') === 'password') {
            $(this).attr('autocomplete', 'new-password');
        }

        // Disable browser suggestions
        $(this).attr('autocorrect', 'off');
        $(this).attr('autocapitalize', 'off');
        $(this).attr('spellcheck', false);

        // Clear autofilled values after page load
        var field = $(this);

        setTimeout(function () {
            //field.val('');
        }, 100);

    });

});
</script>
                

    @endsection
