<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title> @yield('title') | {{config('global.site_name')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{config('global.site_name')}}" name="description" />
    <meta content="{{config('global.site_name')}}" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('admin-assets/assets/images/Mednero.svg') }}">

    <!-- include head css -->
    @include('admin.layouts.head-css')
</head>

@yield('body')

<!-- Begin page -->
<div id="layout-wrapper">
    <!-- topbar -->
    @include('admin.layouts.topbar')

    <!-- sidebar components -->
    @include('admin.layouts.sidebar')
    @include('admin.layouts.horizontal')

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                @yield('content')
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <!-- footer -->
        @include('admin.layouts.footer')

    </div>
    <!-- end main content-->
</div>
<!-- END layout-wrapper -->

<!-- customizer -->
@include('admin.layouts.right-sidebar')

<!-- vendor-scripts -->
@include('admin.layouts.vendor-scripts')

</body>

</html>
