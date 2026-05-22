<!-- JAVASCRIPT -->
<script src="{{ URL::asset('admin-assets/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('admin-assets/assets/libs/metismenujs/metismenujs.min.js') }}"></script>
<script src="{{ URL::asset('admin-assets/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('admin-assets/assets/libs/eva-icons/eva.min.js') }}"></script>

<!-- fontawesome icons init -->
<script src="{{ URL::asset('admin-assets/assets/js/pages/fontawesome.init.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
<script src="{{URL::asset('web/js/select2.min.js')}}"></script>
<script src="{{ URL::asset('admin-assets/assets/js/dataTables.min.js') }}"></script>
<script src="{{ URL::asset('admin-assets/assets/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js" integrity="sha512-WMEKGZ7L5LWgaPeJtw9MBM4i5w5OSBlSjTjCtSnvFJGSVD26gE5+Td12qN5pvWXhuWaWcVwF++F7aqu9cvqP0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>$('#example2').DataTable();</script>
<script>
$('.table-responsive').on('click', 'button[data-bs-toggle="dropdown"]', function (e) {
  const { top, left } = $(this).next(".dropdown-menu")[0].getBoundingClientRect();
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

</script>
@yield('scripts')