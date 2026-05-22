@extends('front.template.layout')

@section('title', 'Faqs')

@section('styles')

@endsection

@section('content')


                
                
                
                <!-- faq Page Start-->
                <div class="faq-page pt-100 mb-100">
                    <div class="container">
                        <div class="row justify-content-center mb-50">
                            <div class="col-xl-8 col-lg-10">
                                <div class="section-title text-center">
                                    <h2>Frequently Asked Questions</h2>
                                    <p>We are committed to delivering more than just healthcare services—we strive to provide a comprehensive and patient-centered experience.</p>
                                </div>
                            </div>
                        </div> 
                        <div class="row justify-content-center">
                            <div class="col-xl-8 col-lg-10">
                                <div class="faq-wrap">
                                    <div class="accordion accordion-flush" id="accordionFlushExample">
                                        @if($faqs->first())
                                        @foreach($faqs as $faq)
                                         <div class="accordion-item">
                                            <h5 class="accordion-header" id="flush-heading{{ $faq->id }}">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $faq->id }}"
                                                    aria-expanded="false" aria-controls="flush-collapse{{ $faq->id }}">{{ $faq->title }}</button>
                                            </h5>
                                            <div id="flush-collapse{{ $faq->id }}" class="accordion-collapse collapse"
                                                aria-labelledby="flush-heading{{ $faq->id }}" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">
                                                    {{ $faq->description }}
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
                <!--faq Page End-->
                
                
<!--<div class="checkout-page user-account-page pt-100 mb-100">-->
<!--    <div class="container">-->
<!--        <div class="row">-->
<!--            <div class="col-lg-12">-->
<!--                <div class="notification-list">-->
<!--                    <div class="checkout-form-title d-flex justify-content-between align-items-center">-->
<!--                        <h4>Faqs</h4>-->
                       
<!--                    </div>-->

                  

<!--                    <div id="empty-state" style="display: none;" class="text-center p-5">-->
<!--                        <i class="bx bx-bell-off" style="font-size: 48px; color: #ccc;"></i>-->
<!--                        <p class="mt-2 text-muted">No faqs found.</p>-->
<!--                    </div>-->
                    
<!--                </div>-->
                
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
@endsection

@section('scripts')
<script type="module">
</script>
@endsection