@extends('web.template.layout')

@section('title', 'Home')

@section('content')
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <!-- <div class="main-content"> -->
            <form action="{{url('web/booking-confirm')}}" method="POST" id="appointment-confirm-form">
                @csrf
            <!-- <div class="main-content"> -->
                <div class="action-bottom-area py-2">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-end align-items-center">
                                <!-- <div class="d-md-block d-none">
                                    <span class="font-size-10">Availability Status</span>
                                    <p class="status fw-bold mb-0 available">Available</p>
                                </div> -->
                                <button id="confirm-booking" type="submit" class="btn btn-primary w-md-280 w-100">Confirm Your Booking</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="page-content">
                    <div class="container-fluid">

                        <div class="row">

                            <div class="col-lg-5 px-md-2 px-0">
                                <div class="product-box rounded p-md-3 p-2 doctor-card in-detail-page">
                                    <div class="row align-items-center">
                                        <div class="col-md-5 col-4 pe-0">
                                            <!-- <div class="pricing-badge">
                                                            <span class="badge">Available</span>
                                                        </div> -->
                                            <div class="product-img bg-light">
                                                <img src="{{$doctor->user->user_img_url ?? null}}" alt="" class="img-fluid mx-auto d-block" />
                                            </div>
                                        </div>
                                        <div class="col-md-7 col-8">
                                            <div class="product-content pt-0">
                                                <h5 class="mt-1 mb-2"><a href="#" class="text-body fw-bold font-size-18">Dr. {{$doctor->user->name}}</a></h5>
                                                <p class="text-body font-size-16 mb-1">{{ count($doctor->specialities) ? $doctor->specialities->pluck('name_en')->implode(', ') : '' }}</p>
                                                <p class="text-muted font-size-13 mb-0">{{$doctor->year_of_experiance ?? 0}} years experience overall</p>
                                                <h5 class="font-size-15 mt-1 mb-2">{{$doctor->hospital->name_en ?? null}}</h5>
                                                <p class="text-muted font-size-14 mb-0">{{round($doctor->hospital->location[0]->distance ?? 0.0)}} km away</p>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-12 d-flex justify-content-between mt-2 align-items-center">
                                            <div class="">
                                                <span class="font-size-10">Availability Status</span>
                                                <p class="status fw-bold mb-0 available">Available</p>
                                            </div>
                                            <a href="bookin-appointment.php" class="btn btn-primary">Book an Appointment</a>
                                        </div> -->
                                    </div>
                                </div>

                                <div class="bg-light-green rounded p-md-3 p-2 mt-md-4 mt-2 doctor-card">
                                    <div class="row">
                                        <div class="col-md-12 d-flex justify-content-center align-items-center flex-column">
                                            <span class="font-size-12">Chosen Slot</span>
                                            @php
                                                $formattedDate = \Carbon\Carbon::parse($bookingData['booking_date'])->format('d F Y');
                                            @endphp
                                            <p class="status font-size-18 fw-bold mb-0 available">{{$formattedDate}} - {{convertToAmPm($bookingData['booking_time_slot'])}}</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-7 px-md-2 px-0">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <h5 class="font-size-16 me-3 mb-0">Info</h5>
                                            <div class="table-responsive mt-3 pb-0">
                                                <table class="table align-middle table-sm table-borderless table-centered mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-muted">Qualifications :</td>
                                                            <th class="fw-bold">{{ count($doctor->qualifications) ? $doctor->qualifications->pluck('title')->implode(', ') : 'N/A' }}</th>
                                                        </tr>
                                                        <!-- end tr -->
                                                        <tr>
                                                            <td class="text-muted">Specialties :</td>
                                                            <th class="fw-bold">{{ count($doctor->specialities) ? $doctor->specialities->pluck('name_en')->implode(', ') : 'N/A' }}</th>
                                                        </tr>
                                                        <!-- end tr -->
                                                        <tr>
                                                            <td class="text-muted">Special Interest :</td>
                                                            <th class="fw-bold">{{ count($doctor->interests) ? $doctor->interests->pluck('title')->implode(', ') : 'N/A' }}</th>
                                                        </tr>
                                                        <!-- end tr -->
                                                        <tr>
                                                            <td class="text-muted">Experience :</td>
                                                            <th class="fw-bold">{{$doctor->year_of_experiance}}+</th>
                                                        </tr>
                                                        <!-- end tr -->
                        
                                                        <tr>
                                                            <td class="text-muted">Languages Spoken :</td>
                                                            <th class="fw-bold">{{ count($doctor->languages) ? $doctor->languages->pluck('title')->implode(', ') : 'N/A' }}</th>
                                                        </tr>
                                                        <!-- end tr -->
                        
                                                        <tr>
                                                            <td class="text-muted">Country of Origin :</td>
                                                            <th class="fw-bold">{{ $doctor->country->name ?? 'N/A' }}</th>
                                                        </tr>
                        
                                                        <tr>
                                                            <td class="text-muted">Doctor Direct Number to Book Appointment</td>
                                                            <th class="fw-bold">+{{$doctor->appointment_dial_code ?? null}} {{$doctor->appointment_phone}}</th>
                                                        </tr>
                                                        <!-- end tr -->
                                                    </tbody>
                                                    <!-- end tbody -->
                                                </table>
                                            </div>
                                        </div>
                                        <!-- end row -->
                                    </div>
                                </div>
                                <div class="product-box rounded p-md-3 p-2 doctor-card in-detail-page">
                                    <h5 class="font-size-16 me-3 mb-3">Patient Details</h5>
                                    <div class="row align-items-center">
                                        <div class="col-md-3 col-4 pe-0">
                                            <div class="product-img bg-light">
                                                <img src="{{ $bookingData['member'] ? ($bookingData['member']->user_img_url ?? null) : ($bookingData['patient']->user_img_url ?? null) }}" alt=""
                                                    class="img-fluid mx-auto d-block">
                                            </div>
                                        </div>
                                        <div class="col-md-9 col-8">
                                            <div class="product-content pt-0">
                                                <h5 class="mt-1 mb-2"><a href="#" class="text-body fw-bold font-size-18">{{$bookingData['member'] ? $bookingData['member']->full_name : (($bookingData['patient']->first_name ?? '') . ' ' . ($bookingData['patient']->last_name ?? ''))}}</a></h5>
                                                <p class="text-body font-size-16 mb-1">{{ GENDERS[$bookingData['patient']->gender] ?? 'N/A'}}</p>
                                                <p class="text-muted font-size-13 mb-0">{{$bookingData['patient']->country->name ?? 'N/A'}}</p>
                                                <h5 class="font-size-15 mt-1 mb-2">+{{$bookingData['patient']->dial_code ?? null}} {{$bookingData['patient']->phone ?? null}}</h5>
                                                <p class="text-muted font-size-14 mb-0">{{$bookingData['patient']->addresss ?? null}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->

                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->
            </form>

            <!-- Add New Event MODAL -->
            <div class="modal fade" id="event-modal" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <!-- <div class="modal-header py-3 px-4 border-bottom-0">
                            <h5 class="modal-title" id="modal-title">Add Members</h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div> -->
                        <div class="modal-body p-4 text-center">
                            <img src="{{ URL::asset('web/images/success-img-icon.svg') }}" style="width: 200px;" alt="" class="icn my-4">
                            <h4 class="fw-bold">Thank You!</h4>
                            <p class="text-body">Your Booking Has Been Received.</p>

                            <h3 class="id-bookin text-primary mt-4 fw-bold" id="bookin-number">Booking No : #MYDW1025</h3>
                            <p class="text-body">Mednero team will contact you soon</p>
                        </div>
                        <div class="modal-footer flex-nowrap">
                            <a href="{{url('/website')}}" class="btn btn-outline-primary w-50">Back To Home</a>
                            <a href="{{route('find_a_doctor')}}" class="btn btn-primary w-50">Search Doctor</a>
                        </div>
                    </div>
                    <!-- end modal-content-->
                </div>
                <!-- end modal dialog-->
            </div>
            
            <!-- Add New Event MODAL -->
            <div class="modal fade" id="fail-event-modal" tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <!-- <div class="modal-header py-3 px-4 border-bottom-0">
                            <h5 class="modal-title" id="modal-title">Add Members</h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div> -->
                        <div class="modal-body p-4 text-center">
                            <img src="{{ URL::asset('web/images/success-img-icon.svg') }}" style="width: 200px;" alt="" class="icn my-4">
                            <h4 class="fw-bold">Sorry!</h4>
                            <p class="text-body">Cannot Book this Appointment.</p>

                            <!-- <h3 class="id-bookin text-primary mt-4 fw-bold" id="bookin-number">Booking No : #MYDW1025</h3>
                            <p class="text-body">Mednero team will contact you soon</p> -->
                        </div>
                        <div class="modal-footer flex-nowrap">
                            <a href="{{url('/website')}}" class="btn btn-outline-primary w-50"  data-bs-dismiss="modal">Back To Home</a>
                            <a href="{{url('/website/doctors-list')}}" class="btn btn-primary w-50">Search Doctor</a>
                        </div>
                    </div>
                    <!-- end modal-content-->
                </div>
                <!-- end modal dialog-->
            </div>
            <!-- </div> -->
            <!-- end main content-->
@endsection

@section('custom_js')
    <script>
        $('#appointment-confirm-form').on('submit', function (event) {
                event.preventDefault();
                $('#event-modal').modal('hide');
                $('#bookin-number').text('')
                $('#fail-event-modal').modal('hide');
                var $form = $(this);
                let url = $form.attr('action');
                $form.find('button[type="submit"]').text('Processing..').attr('disabled', true);
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        $form.find('button[type="submit"]').text('Confirm Your Booking').attr('disabled', false);
                        if (response.status == '1') {
                            $('#event-modal').modal('show');
                            $('#bookin-number').text(`Booking No : ${response.oData['data'].booking_id}`)
                            $form.find('button[type="submit"]').remove();
                        } else {
                            $('#fail-event-modal').modal('show');
                        }
                    },
                    error: function (xhr, status, error) {
                        $form.find('button[type="submit"]').text('Confirm Your Booking').attr('disabled', false);
                        App.alert('Something went wrong', 'Fail!', 'error');
                    }
                });
            });

    </script>
@endsection