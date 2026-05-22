@yield('css')
<!-- Bootstrap Css -->
<link href="{{ URL::asset('admin-assets/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ URL::asset('admin-assets/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->
<link href="{{ URL::asset('web/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ URL::asset('admin-assets/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('admin-assets/assets/css/dataTables.bootstrap5.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('admin-assets/assets/css/custom.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<style>
    .error{
        color:red;
    }
</style>
