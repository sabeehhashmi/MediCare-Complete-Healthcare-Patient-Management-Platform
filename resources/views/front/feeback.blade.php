@extends('front.template.layout')

@section('title', 'My Reviews - MedNero')

@section('content')
<style>
    .nice-select .current{
        line-height:42px !important;
    }
</style>
<div class="checkout-page user-account-page pt-100 mb-100">
    <div class="container">
        <div class="row g-lg-4 gy-5">

        <!-- Sidebar -->
        <div class="col-lg-4">
            @include('front.layouts.user-sidebar')
        </div>

        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="checkout-form-wrapper">
                
                <div class="checkout-form-title">
                    <h4>My Reviews</h4>
                </div>

                <div class="home1-faq-section mb-100">
                    <div class="row justify-content-center">
                        <div class="col-xl-12">
                            <div class="faq-wrap">

                                @if($feedbacks && $feedbacks->count())
                                    <div class="accordion accordion-flush" id="accordionFlushExample">

                                        @foreach($feedbacks as $feedback)
                                            <div class="accordion-item wow animate fadeInDown" data-wow-delay="200ms">
                                                
                                                <h5 class="accordion-header" id="heading{{$feedback->id}}">
                                                    <button class="accordion-button collapsed d-flex justify-content-between" 
                                                            type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#collapse{{$feedback->id}}">

                                                        {{ $feedback->appointment->booking_id ?? 'N/A' }}
                                                        <span class="badge text-bg-secondary ms-3">
                                                            {{ $feedback->created_at->format('d-M-Y h:i A') }}
                                                        </span>

                                                        <span class="badge text-bg-success ms-2">
                                                            ⭐ {{ $feedback->rating }}/5
                                                        </span>
                                                    </button>
                                                </h5>

                                                <div id="collapse{{$feedback->id}}" 
                                                     class="accordion-collapse collapse"
                                                     data-bs-parent="#accordionFlushExample">

                                                    <div class="accordion-body">

                                                        <div class="d-flex justify-content-between">
                                                            <span>
                                                                Booking No:
                                                                <strong>
                                                                    {{ $feedback->appointment->booking_id ?? 'N/A' }}
                                                                </strong>
                                                            </span>

                                                            <span>
                                                                Date:
                                                                <strong>
                                                                    {{ $feedback->appointment->booking_date ?? 'N/A' }}
                                                                </strong>
                                                            </span>
                                                        </div>

                                                        <div class="d-flex justify-content-between mt-2">
                                                            <span>
                                                                Doctor:
                                                                <strong>
                                                                    {{ $feedback->appointment->doctor->user->name ?? 'N/A' }}
                                                                </strong>
                                                            </span>

                                                            <span>
                                                                Hospital:
                                                                <strong>
                                                                    {{ $feedback->appointment->hospital->name_en ?? 'N/A' }}
                                                                </strong>
                                                            </span>
                                                        </div>

                                                        <div class="mt-3">
                                                            <strong>Review:</strong>
                                                            <p class="mb-0">
                                                                {{ $feedback->feeback_message }}
                                                            </p>
                                                        </div>
                                                        <div class="mt-3">

    <div class="d-flex gap-2">
        <!-- ✏️ Edit -->
        <button 
            class="btn btn-sm btn-primary edit-feedback"
            data-id="{{ $feedback->id }}"
            data-rating="{{ $feedback->rating }}"
            data-message="{{ $feedback->feeback_message }}"
            data-appointment="{{ $feedback->appointment_id }}"
            data-doctor="{{ $feedback->doctor_id }}"
        >
            Edit
        </button>

        <!-- 🗑 Delete -->
        <button 
            class="btn btn-sm btn-danger delete-feedback"
            data-id="{{ $feedback->id }}"
        >
            Delete
        </button>
    </div>
</div>

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>

                                    <!-- Pagination -->
                                    <div class="col-12 py-3">
                                        {{ $feedbacks->links() }}
                                    </div>

                                @else
                                    <p>You have not submitted any feedback yet.</p>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

</div>
@endsection

@section('modals')

<div class="modal enquiry-modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel"
    aria-hidden="true">
    
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Close Button -->
            <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                <svg width="10" height="10" viewBox="0 0 10 10">
                    <path d="M2.00247 0.500545C1.79016 0.505525 1.58918 0.582706 1.4362 0.735547L0.694403 1.479C0.345704 1.82743 0.389689 2.43243 0.79164 2.83493L3.00694 5.05341L0.79164 7.27092C0.389689 7.67328 0.345566 8.27842 0.694403 8.62753L1.4362 9.37044C1.7849 9.71872 2.38879 9.67543 2.7913 9.27293L5.00659 7.05473L7.22189 9.27293C7.62467 9.67543 8.22898 9.71872 8.57699 9.37044L9.31989 8.62753C9.6679 8.27856 9.62461 7.67342 9.22182 7.27092L7.00653 5.05341L9.22182 2.83493C9.62461 2.43243 9.6679 1.82743 9.31989 1.479L8.57699 0.735547C8.22898 0.386433 7.62467 0.430557 7.22189 0.833614L5.00659 3.05126L2.7913 0.833753Z"/>
                </svg>
            </button>

            <div class="modal-body">

                <!-- Title -->
                <h4 class="modal-title" id="reviewModalLabel">Update Feedback</h4>

                <!-- FORM -->
                <form id="review_form">
                    @csrf

                    <input type="hidden" name="feedback_id" id="feedback_id">
                    <input type="hidden" name="appointment_id" id="review_appointment_id">
                    <input type="hidden" name="review_doctor_id" id="review_doctor_id">

                    <!-- Rating -->
                    <div class="form-inner mt-3">
                        <label>Rating</label>
                        <select class="form-control user_rating" name="rating" id="rating" required>
                           <option value="">Select Rating</option>
                            <option value="1">⭐</option>
                            <option value="2">⭐⭐</option>
                            <option value="3">⭐⭐⭐</option>
                            <option value="4">⭐⭐⭐⭐</option>
                            <option value="5">⭐⭐⭐⭐⭐</option>
                        </select>
                    </div>
                    
                    <br>
                    <br>

                    <!-- Feedback -->
                    <div class="form-inner mt-3">
                        <label>Feedback</label>
                        <textarea class="form-control" name="feeback_message" rows="3" required></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-inner mt-3">
                        <button type="button" id="submitReview" class="primary-btn1 black-bg">
                            <span>
                                Submit Review
                                <svg width="10" height="10" viewBox="0 0 10 10">
                                    <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438Z"/>
                                </svg>
                            </span>
                            <span>
                                Submit Review
                                <svg width="10" height="10" viewBox="0 0 10 10">
                                    <path d="M9.73535 1.14746C9.57033 1.97255 9.32924 3.26406 9.24902 4.66797C9.16817 6.08312 9.25559 7.5453 9.70214 8.73633C9.84754 9.12406 9.65129 9.55659 9.26367 9.70215C8.9001 9.83849 8.4969 9.67455 8.32812 9.33398L8.29785 9.26367L8.19921 8.98438Z"/>
                                </svg>
                            </span>
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<!-- You can keep or remove modals if not needed -->

@endsection

@section('scripts')

<script>
   $(document).on('click', '.edit-feedback', function () {

    let rating = $(this).attr('data-rating');

    $('#feedback_id').val($(this).attr('data-id'));
    $('#review_appointment_id').val($(this).attr('data-appointment'));
    $('#review_doctor_id').val($(this).attr('data-doctor'));

    $('#reviewModal textarea[name="feeback_message"]').val($(this).attr('data-message'));

    $('#reviewModal').modal('show');

    setTimeout(function () {
        $('#reviewModal select[name="rating"]')
            .val(String(rating))
            .niceSelect('update'); // ✅ VERY IMPORTANT
    }, 100);
});
    // Optional JS (keep if needed)


    $('#submitReview').on('click', function (e) {

    e.preventDefault();

    let form = $('#review_form')[0];
    let formData = new FormData(form);

    let feedbackId = $('#feedback_id').val();

    let url = feedbackId 
        ? "{{ url('appointments/update-feedback') }}" 
        : "{{ url('appointments/save-feedback') }}";

    $.ajax({
        type: "POST",
        url: url,
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',

        success: function (res) {

            $('#reviewModal').modal('hide');

            if (res.status == 0) {
                toastr.error(res.message);
            } else {
                toastr.success(res.message);

                setTimeout(function () {
                    location.reload();
                }, 1000);
            }
        },

        error: function () {
            toastr.error('Something went wrong');
        }
    });
});

$(document).on('click', '.delete-feedback', function () {

    let id = $(this).data('id');

    if (!confirm('Are you sure you want to delete this feedback?')) {
        return;
    }

    $.ajax({
        type: "POST",
        url: "{{ url('appointments/delete-feedback') }}",
        data: {
            _token: "{{ csrf_token() }}",
            id: id
        },
        success: function (res) {

            if (res.status == 1) {
                toastr.success(res.message);
                location.reload();
            } else {
                toastr.error(res.message);
            }
        }
    });
});
</script>


@endsection
