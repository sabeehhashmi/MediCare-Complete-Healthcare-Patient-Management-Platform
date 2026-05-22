@extends('web.template.layout')

@section('title', 'Home')

@section('content')
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<!-- <div class="main-content"> -->
<div class="page-content">
    <div class="container-fluid cms-page">
        <div class="row justify-content-center">
            
            <div class="col-12 text-primary text-center mb-4 mt-4">
                <h1>{{$page_heading}}</h1>
            </div>
            <div class="col-lg-3">
                <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link text-start active" id="v-pills-managing-account-doctors-tab" data-bs-toggle="pill" data-bs-target="#v-pills-managing-account-doctors" type="button" role="tab" aria-controls="v-pills-managing-account-doctors" aria-selected="true">Registering a healthcare provider </button>
                    <button class="nav-link text-start" id="v-pills-managing-appointments-tab" data-bs-toggle="pill" data-bs-target="#v-pills-managing-appointments" type="button" role="tab" aria-controls="v-pills-managing-appointments" aria-selected="false">Managing Doctors Panel</button>
                    <button class="nav-link text-start" id="v-pills-managing-doctors-tab" data-bs-toggle="pill" data-bs-target="#v-pills-managing-doctors" type="button" role="tab" aria-controls="v-pills-managing-doctors" aria-selected="false">Managing Availability of Doctors</button>
                    <button class="nav-link text-start" id="v-pills-managing-doctor-availability-tab" data-bs-toggle="pill" data-bs-target="#v-pills-managing-doctor-availability" type="button" role="tab" aria-controls="v-pills-managing-doctor-availability" aria-selected="false">Managing Reports in Doctor Panel </button>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-managing-account-doctors" role="tabpanel" aria-labelledby="v-pills-managing-account-doctors-tab" tabindex="0">

                        
                        <div class="accordion accordion-flush" id="accordipOnexample">
                            @if($instructions_type1->first())
                            @php
                            $pill_cont=1;
                            @endphp
                            @foreach($instructions_type1 as $instructions_type)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapsep01" aria-expanded="{{$pill_cont==1?'true':'false'}}" aria-controls="collapsep{{$instructions_type->id}}">
                                        <h5 class="mb-0">{{$instructions_type->title}}</h5>
                                    </button>
                                </h2>
                                <div id="collapsep01" class="accordion-collapse collapse show" data-bs-parent="#accordipOnexample">
                                    <div class="accordion-body">
                                        {!! $instructions_type->description !!}

                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-managing-appointments" role="tabpanel" aria-labelledby="v-pills-managing-appointments-tab" tabindex="0">
                    

                        
                        
                        <div class="accordion accordion-flush" id="accordipOnexample">
                            @if($instructions_type2->first())
                            @php
                            $pill_cont=1;
                            @endphp
                            @foreach($instructions_type2 as $instructions_type)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapsep01" aria-expanded="{{$pill_cont==1?'true':'false'}}" aria-controls="collapsep{{$instructions_type->id}}">
                                        <h5 class="mb-0">{{$instructions_type->title}}</h5>
                                    </button>
                                </h2>
                                <div id="collapsep01" class="accordion-collapse collapse show" data-bs-parent="#accordipOnexample">
                                    <div class="accordion-body">
                                        {!! $instructions_type->description !!}

                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-managing-doctors" role="tabpanel" aria-labelledby="v-pills-managing-doctors-tab" tabindex="0">

                        
                        <div class="accordion accordion-flush" id="accordipOnexample">
                            @if($instructions_type3->first())
                            @php
                            $pill_cont=1;
                            @endphp
                            @foreach($instructions_type3 as $instructions_type)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapsep01" aria-expanded="{{$pill_cont==1?'true':'false'}}" aria-controls="collapsep{{$instructions_type->id}}">
                                        <h5 class="mb-0">{{$instructions_type->title}}</h5>
                                    </button>
                                </h2>
                                <div id="collapsep01" class="accordion-collapse collapse show" data-bs-parent="#accordipOnexample">
                                    <div class="accordion-body">
                                        {!! $instructions_type->description !!}

                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>

                        <h3 class="text-primary mb-4"></h3>
                        
                    </div>
                    <div class="tab-pane fade" id="v-pills-managing-doctor-availability" role="tabpanel" aria-labelledby="v-pills-managing-doctor-availability-tab" tabindex="0">
                        

                    <div class="accordion accordion-flush" id="accordipOnexample">
                        @if($instructions_type4->first())
                        @php
                        $pill_cont=1;
                        @endphp
                        @foreach($instructions_type4 as $instructions_type)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapsep01" aria-expanded="{{$pill_cont==1?'true':'false'}}" aria-controls="collapsep{{$instructions_type->id}}">
                                    <h5 class="mb-0">{{$instructions_type->title}}</h5>
                                </button>
                            </h2>
                            <div id="collapsep01" class="accordion-collapse collapse show" data-bs-parent="#accordipOnexample">
                                <div class="accordion-body">
                                    {!! $instructions_type->description !!}

                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                        </div>

                        

                        


                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('web.template.footer-content')
</div>


