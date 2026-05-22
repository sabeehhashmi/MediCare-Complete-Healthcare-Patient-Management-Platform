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
<link href="{{ URL::asset('admin-assets/assets/css/all/all.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/assets/css/flatpickr.min.css" >
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    .error{
        color:red;
    }

    .checklist {
        position: absolute;
        width: 260px;
        font-size: 14px;
        background: #fff;
        box-shadow: 0px 10px 24px 0px #0000000F;
        z-index: 9;
        top: 60px;
        padding: 1rem;
        display: none;
    }

    .checklist ul {
        list-style-type: none;
        padding: 0;
    }

    .checklist ul li span {
        background: red;
        vertical-align: middle;
        color: #fff;
        border-radius: 50%;
        padding: 0 3px;
    }

    .checklist ul li span.success {
        background: green !important;
    }

    .checklist ul li {
        margin: 5px 0;
    }
</style>
