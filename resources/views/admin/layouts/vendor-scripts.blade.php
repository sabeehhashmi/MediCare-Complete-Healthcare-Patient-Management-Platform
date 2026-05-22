<!-- JAVASCRIPT -->
<script src="{{ URL::asset('admin-assets/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('admin-assets/assets/libs/metismenujs/metismenujs.min.js') }}"></script>
<script src="{{ URL::asset('admin-assets/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('admin-assets/assets/libs/eva-icons/eva.min.js') }}"></script>

<!-- fontawesome icons init -->
<script src="{{ URL::asset('admin-assets/assets/js/pages/fontawesome.init.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
<script src="{{URL::asset('web/js/select2.min.js')}}"></script>
<script src="{{ URL::asset('admin-assets/assets/js/dataTables.min.js') }}"></script>
<script src="{{ URL::asset('admin-assets/assets/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"
        integrity="sha512-WMEKGZ7L5LWgaPeJtw9MBM4i5w5OSBlSjTjCtSnvFJGSVD26gE5+Td12qN5pvWXhuWaWcVwF++F7aqu9cvqP0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>$('#example2').DataTable();</script>

<script>
    
    
    $('.table-responsive').on('click', 'button[data-bs-toggle="dropdown"]', function (e) {
        const {top, left} = $(this).next(".dropdown-menu")[0].getBoundingClientRect();
        $(this).next(".dropdown-menu").css({
            position: "fixed",
            inset: "unset",
            transform: "unset",
            top: top + "px",
            left: left + "px",
        });
    });

    if ($('.table-responsive').length) {
        $(window).on('scroll', function (e) {
            $('.table-responsive .dropdown-menu').removeClass('show');
            $('.table-responsive button[data-bs-toggle="dropdown"]').removeClass('show');
        });
    }

    $(document).ready(function () {
        $('#reset-password-btn').on('click', function (event) {
            event.preventDefault();

            $('#old_password_error').text('');
            $('#new_password_error').text('');
            $('#confirm_password_error').text('');

            let oldPassword = $('#old_password').val().trim();
            let newPassword = $('#new_password').val().trim();
            let confirmPassword = $('#confirm_password').val().trim();

            let isValid = true;

            if (oldPassword === '') {
                $('#old_password_error').text('Old password is required.');
                isValid = false;
            }

            if (newPassword === '') {
                $('#new_password_error').text('New password is required.');
                isValid = false;
            }

            if (confirmPassword === '') {
                $('#confirm_password_error').text('Please confirm your new password.');
                isValid = false;
            } else if (newPassword !== confirmPassword) {
                $('#confirm_password_error').text('Passwords do not match.');
                isValid = false;
            }

            if (isValid) {
                $.ajax({
                    url: "{{ route('change-password') }}",
                    method: 'post',
                    headers: {
                      'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: {
                        oldPassword: oldPassword,
                        newPassword: newPassword,
                        confirmPassword: confirmPassword
                    },
                    success: function (response) {
                        if (!response.success) {
                            $('#old_password_error').text(response.message)
                        } else {
                            $('#reset-password-success-modal').modal('show')
                            $('#reset-password-modal').modal('hide')
                        }
                    },
                    error: function (error) {
                        console.log(error.message)
                    }
                });
            }
        });

        $('#new_password').on('focus', function () {
            $('#checklist').show();
        });

        $('#new_password').on('blur', function () {
            $('#checklist').hide();
        });


        $('.show-password').on('click', function() {
            const input = $(this).siblings('input');
            const icon = $(this).find('i');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        $('#new_password').on('input', function () {
            const password = $(this).val();

            const patterns = {
                length: /^.{6,12}$/,
                uppercase: /[A-Z]/,
                lowercase: /[a-z]/,
                number: /\d/,
                special: /[!@$%^&*(),.?":{}|<>]/
            };

            function updateChecklist() {
                $('#password_limit').toggleClass('valid', patterns.length.test(password));
                $('#uppercase_letter').toggleClass('valid', patterns.uppercase.test(password));
                $('#lowercase_letter').toggleClass('valid', patterns.lowercase.test(password));
                $('#number').toggleClass('valid', patterns.number.test(password));
                $('#special_character').toggleClass('valid', patterns.special.test(password));

                function updateIcon(elementId, isValid) {
                    const icon = $(`#${elementId} span i`);
                    const span = $(`#${elementId} span`);
                    icon.attr('class', isValid ? 'fas fa-check' : 'fas fa-times');
                    span.attr('class', isValid ? 'success' : '');
                }

                updateIcon('password_limit', patterns.length.test(password));
                updateIcon('uppercase_letter', patterns.uppercase.test(password));
                updateIcon('lowercase_letter', patterns.lowercase.test(password));
                updateIcon('number', patterns.number.test(password));
                updateIcon('special_character', patterns.special.test(password));
            }

            updateChecklist();
        });


        function myFunction() {
            let role = '{{ Auth::user()->role }}';
            let email = '{{ Auth::user()->email }}';
            $.ajax({
                url: "{{ route('check-login') }}",
                type: 'post',
                data: {role: role, email: email},
                headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.status) {
                        window.location.href = response.url
                    }
                }
            })
            console.log("This function is called every second");
            // Add your logic here
        }

        //setInterval(myFunction, 5000);
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
           // field.val('');
        }, 100);

    });

});
</script>

@yield('scripts')

