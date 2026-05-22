@extends('web.template.layout')

@section('title', 'Home')

@section('content')
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <!-- <div class="main-content"> -->
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="text-center text-muted my-5">
                                <h1>{{$page_heading}}</h1>
                            </div>
                            <div class="cms-content">
                                @php
                                    echo $content;
                                @endphp
                               
                            </div>
                        </div>
                    </div>
                </div>
                
                @include('web.template.footer-content')
                
            </div>
            <!-- </div> -->
            <!-- end main content-->
@endsection