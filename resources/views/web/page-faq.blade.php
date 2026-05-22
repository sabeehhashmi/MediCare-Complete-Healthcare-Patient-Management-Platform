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
                            <div class="accordion" id="accordipOnexample">

                            @foreach ($faqs as $faq)

                            <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsep{{$faq->id}}" aria-expanded="false" aria-controls="collapsep{{$faq->id}}">
                                    <h5 class="mb-0">{{$faq->title}}</h5>
                                    <h5 class="mb-0"></h5>
                                </button>
                            </h2>
                            <div id="collapsep{{$faq->id}}" class="accordion-collapse collapse" data-bs-parent="#accordipOnexample">
                                <div class="accordion-body">
                                {{$faq->description}}
                                </div>
                            </div>
                        </div>
                                
                            @endforeach

                
                    
                    </div>
                    <!-- end accordion -->
                               
                            </div>
                        </div>
                    </div>
                </div>
                
                @include('web.template.footer-content')
                
            </div>
            <!-- </div> -->
            <!-- end main content-->
@endsection