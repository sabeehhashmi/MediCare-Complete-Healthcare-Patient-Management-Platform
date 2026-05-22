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
            <div class="col-12">
                <div class="text-center text-muted my-5">
                    <h1>{{$page_heading}}</h1>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link text-start active" id="v-pills-managing-account-tab" data-bs-toggle="pill" data-bs-target="#v-pills-managing-account" type="button" role="tab" aria-controls="v-pills-managing-account" aria-selected="true">Managing An Account</button>
                    <button class="nav-link text-start" id="v-pills-book-appointment-tab" data-bs-toggle="pill" data-bs-target="#v-pills-book-appointment" type="button" role="tab" aria-controls="v-pills-book-appointment" aria-selected="false">Booking An Appointment</button>
                    <button class="nav-link text-start" id="v-pills-manage-appointment-tab" data-bs-toggle="pill" data-bs-target="#v-pills-manage-appointment" type="button" role="tab" aria-controls="v-pills-manage-appointment" aria-selected="false">Managing An Appointment</button>
                    <button class="nav-link text-start" id="v-pills-adding-family-frndz-tab" data-bs-toggle="pill" data-bs-target="#v-pills-adding-family-frndz" type="button" role="tab" aria-controls="v-pills-adding-family-frndz" aria-selected="false">Adding Family or Friends</button>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-managing-account" role="tabpanel" aria-labelledby="v-pills-managing-account-tab" tabindex="0">
            
                        <div class="accordion" id="accordipOnexample">
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
                    <div class="tab-pane fade" id="v-pills-book-appointment" role="tabpanel" aria-labelledby="v-pills-book-appointment-tab" tabindex="0">
                        


                        <div class="accordion" id="accordipOnexample">
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

                    <div class="tab-pane fade" id="v-pills-manage-appointment" role="tabpanel" aria-labelledby="v-pills-manage-appointment-tab" tabindex="0">
                                            

                    
                        <div class="accordion" id="accordipOnexample">
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
                        <!-- end accordion -->
                        
                    </div>
                    <div class="tab-pane fade" id="v-pills-adding-family-frndz" role="tabpanel" aria-labelledby="v-pills-adding-family-frndz-tab" tabindex="0">
                                            


                        <div class="accordion" id="accordipOnexample">
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


