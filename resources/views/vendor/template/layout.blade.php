@extends('template.backend-Dashboard')

@section('header')
<link href="{{ asset('') }}admin-assets/assets/css/support-chat.css" rel="stylesheet" type="text/css" />
<link href="{{ asset('') }}admin-assets/plugins/maps/vector/jvector/jquery-jvectormap-2.0.3.css" rel="stylesheet" type="text/css" />
<link href="{{ asset('') }}admin-assets/plugins/charts/chartist/chartist.css" rel="stylesheet" type="text/css">
<link href="{{ asset('') }}admin-assets/assets/css/default-dashboard/style.css" rel="stylesheet" type="text/css" />

@stop


@section('sidebar_ul')
<ul class="nav-links">
    <li>
        <div class="iocn-link">
            <a href="#">
                <i class='bx bx-cube'></i>
                <span class="link_name">Yatch</span>
            </a>
            <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
            <li>
                <a href="{{ url('vendor/yatchs') }}">All Yatchs </a>
            </li>
            <li>
                <a href="{{ url('vendor/add-yatch') }}">Add Yatch</a>
            </li>
        </ul>
    </li>

    <li>
        <div class="iocn-link">
            <a href="#">
                <i class='bx bx-cube'></i>
                <span class="link_name">Packages</span>
            </a>
            <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
            <li>
                <a href="{{ url('vendor/addons') }}">All Packages </a>
            </li>
            <li>
                <a href="{{ url('vendor/add-addon') }}">Add Package</a>
            </li>
        </ul>
    </li>
</ul>
@stop


@section('right_bar_dropdown')
<div class="dropdown">
    <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="profile-name">Hi, {{ Auth::user()->name }}</span>
        <img src="{{ asset('') }}admin-assets/assets/img/profile-icon.svg" alt="mdo" width="32" height="32" class="rounded-circle">
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="{{ url('vendor/dashboard') }}"><i class='bx bx-grid-alt'></i> Dashboard</a>
        <a class="dropdown-item" href="{{ url('vendor/change_password') }}"><i class='bx bxs-key'></i> Change Password</a>
        <a class="dropdown-item" href="{{ url('vendor/logout') }}"><i class='bx bx-log-out'></i>
            Log Out</a>
    </div>
</div>
@stop


<style>
    .home-section footer {
        bottom: auto !important;
    }

    .table>tbody>tr>td {
        white-space: nowrap;
    }
</style>

<div class="row">
    <div class="col-12">
        @section('content')
            @yield('content')
        @stop
    </div>
</div>


@section('footer')
<script src="{{asset('')}}admin-assets/plugins/charts/chartist/chartist.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0-rc"></script>
@stop