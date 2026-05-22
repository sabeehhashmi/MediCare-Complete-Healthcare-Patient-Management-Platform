<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\front\FrontController;
use App\Http\Controllers\front\AuthController;
use App\Http\Controllers\front\ProfileController;
use App\Http\Controllers\front\PharmacyListController;
use App\Http\Controllers\front\DoctorController;
use App\Http\Controllers\front\PharmacyDetailController;
use App\Http\Controllers\front\CartController;
use App\Http\Controllers\front\CheckoutController;
use App\Http\Controllers\front\AddressController;
use Illuminate\Support\Str;


Route::get('/', [FrontController::class, 'index'])->name('front.index');
Route::post('/contact-us', [FrontController::class, 'contactUsForm'])->name('front.post.contactus');
Route::get('/terms-conditions', [FrontController::class, 'termsConditions'])->name('front.terms-conditions');
Route::get('/privacy-policy', [FrontController::class, 'privacyPolicy'])->name('front.privacy-policy');

// Pharmacy Routes
Route::get('/pharmacy-list', [PharmacyListController::class, 'index'])->name('front.pharmacy-list');
Route::get('/pharmacy-listdetail/{slug}', [PharmacyDetailController::class, 'show'])->name('front.pharmacy-detail');

Route::get('/faqs-list', [FrontController::class, 'faqsList']);
Route::get('/faqs-detail', [FrontController::class, 'faqsDetail']);
Route::get('/videos-list', [FrontController::class, 'videosList']);
Route::get('/videos-detail', [FrontController::class, 'videosDetail']);
Route::get('/wellness-list', [FrontController::class, 'wellnessList']);
Route::get('/wellness-detail', [FrontController::class, 'wellnessDetail']);

// Auth Routes
Route::prefix('auth')->name('front.')->middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showAuthPage'])->name('auth');
    Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('send.otp');
    Route::post('/login-with-email', [AuthController::class, 'loginWithEmail'])->name('login.email');
    Route::post('/verify-email-login-otp', [AuthController::class, 'verifyEmailLoginOtp'])
    ->name('verify.email.login.otp');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('resend.otp');
});

Route::prefix('auth')->name('front.')->middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::prefix('auth')->name('front.')->group(function () {
    Route::get('/get-sub-insurances/{insurence_id}', [AuthController::class, 'getSubInsurances'])->name('get.sub.insurances');
});



Route::get('/login', function() {
    return redirect()->route('front.auth');
})->name('login');

    Route::get('/cart', [CartController::class, 'index'])->name('front.cart');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('front.cart.add');
    Route::post('/cart/update', [CartController::class, 'updateQuantity'])->name('front.cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('front.cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('front.cart.clear');
    Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('front.cart.count');
    Route::get('/cart/summary', [CartController::class, 'getCartSummary'])->name('front.cart.summary');

    Route::post('/apply-coupon', 'App\Http\Controllers\front\CartController@applyCoupon')->name('front.cart.apply-coupon');
    Route::post('/remove-coupon', 'App\Http\Controllers\front\CartController@removeCoupon')->name('front.cart.remove-coupon');
    Route::post('/validate-coupon', 'App\Http\Controllers\front\CartController@validateCoupon')->name('front.cart.validate-coupon');


// Protected Routes

Route::middleware(['auth'])->prefix('patient')->name('front.')->group(function () {
    Route::get('/chat', [App\Http\Controllers\front\ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/init', [App\Http\Controllers\front\ChatController::class, 'init'])->name('chat.init');
    Route::get('/chat/conversations', [App\Http\Controllers\front\ChatController::class, 'getConversations'])->name('chat.conversations');
    Route::get('/chat/messages/{uid}', [App\Http\Controllers\front\ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/send', [App\Http\Controllers\front\ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/mark-read', [App\Http\Controllers\front\ChatController::class, 'markAsRead'])->name('chat.mark-read');
    Route::post('/chat/unread-count', [App\Http\Controllers\front\ChatController::class, 'getUnreadCount'])->name('chat.unread-count');
    Route::post('/chat/check', [App\Http\Controllers\front\ChatController::class, 'checkNewMessages'])->name('chat.check');
    Route::post('/chat/notify', [App\Http\Controllers\front\ChatController::class, 'notifyMessage'])->name('chat.notify');
});

Route::middleware(['auth'])->group(function () {

    
    // Profile Routes
    Route::get('/useraccount-profile', [ProfileController::class, 'index'])->name('front.profile');
    Route::post('/useraccount-profile/upload-image', [ProfileController::class, 'uploadImage'])->name('front.profile.upload.image');
    Route::post('/useraccount-profile/update', [ProfileController::class, 'update'])->name('front.profile.update');
     Route::get('/useraccount-points', [FrontController::class, 'showPointHistory'])->name('front.points');
    Route::get('/point-history/export', [FrontController::class, 'exportPoints'])->name('front.points.export');
    Route::get('/point-history/export-pdf', [FrontController::class, 'exportPointsPdf'])
    ->name('front.points.export.pdf');
    Route::post("front/appointments/change_status",  [FrontController::class, 'change_status']);
    Route::post('/useraccount-profile/send-otp', [ProfileController::class, 'sendOtp'])->name('front.profile.send.otp');
    Route::post('/useraccount-profile/verify-otp', [ProfileController::class, 'verifyOtp'])->name('front.profile.verify.otp');
    Route::post('/useraccount-profile/resend-otp', [ProfileController::class, 'resendOtp'])->name('front.profile.resend.otp');
    Route::get('/get-sub-insurances-profile/{insurence_id}', [ProfileController::class, 'getSubInsurances'])->name('front.profile.sub.insurances');

     Route::get('/useraccount-orders', [App\Http\Controllers\front\OrderController::class, 'index'])->name('front.orders');
    Route::get('/useraccount/order-details/{id}', [App\Http\Controllers\front\OrderController::class, 'show'])->name('front.order.details');
    Route::post('/useraccount/orders/{id}/cancel', [App\Http\Controllers\front\OrderController::class, 'cancel'])->name('front.order.cancel');
    Route::post('/useraccount/orders/{id}/reorder', [App\Http\Controllers\front\OrderController::class, 'reorder'])->name('front.order.reorder');
    Route::get('/useraccount/orders/{id}/track', [App\Http\Controllers\front\OrderController::class, 'track'])->name('front.order.track');
    Route::get('/useraccount/orders/{id}/invoice', [App\Http\Controllers\front\OrderController::class, 'downloadInvoice'])->name('front.order.invoice');
    
    // Cart Routes

     Route::get('/addresses', [AddressController::class, 'index'])->name('front.addresses.index');
    Route::post('/addresses', [AddressController::class, 'store'])->name('front.addresses.store');
    Route::put('/addresses/{id}', [AddressController::class, 'update'])->name('front.addresses.update');
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy'])->name('front.addresses.destroy');
    Route::post('/addresses/{id}/default', [AddressController::class, 'setDefault'])->name('front.addresses.default');
    
    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('front.checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('front.checkout.process');
    Route::get('/payment/success', [CheckoutController::class, 'paymentSuccess'])->name('front.payment.success');
    Route::get('/payment/cancel', [CheckoutController::class, 'paymentCancel'])->name('front.payment.cancel');
    Route::get('/order/success/{order}', [CheckoutController::class, 'success'])->name('front.order.success');

   Route::get('/video-call/{channel}', function ($channel) {

    return view('video.call', compact('channel'));
});



Route::get('/agora/token/{channel}/{uid}', function ($channel, $uid) {

    $appID = config('services.agora.app_id');
    $appCertificate = config('services.agora.app_certificate');

    $role = \App\Libraries\Agora\RtcTokenBuilder::RoleAttendee;

    $expireTimeInSeconds = 3600;
    $privilegeExpiredTs = now()->timestamp + $expireTimeInSeconds;

    $token = \App\Libraries\Agora\RtcTokenBuilder::buildTokenWithUid(
        $appID,
        $appCertificate,
        $channel,
        $uid,
        $role,
        $privilegeExpiredTs
    );

    return response()->json([
        'token' => $token,
        'uid' => $uid,
        'app_id' => $appID
    ]);
});
   Route::get('/print-prescription/{id}', function ($id) {

    

     $appointment = \App\Models\DoctorPatientAppointment::findOrFail($id);

    $prescription = \App\Models\Prescription::where('appointment_id', $id)
        ->with(['details.medicine','details.direction','details.frequency','details.duration','details.dosage'])
        ->firstOrFail();
        

    // RETURN HTML VIEW (NOT PDF)
    return view('prescriptions.print', compact('prescription', 'appointment'));


})->name('print.prescription');
    
    // Other Routes
    Route::get('/useraccount-reports', [FrontController::class, 'showReports'])->name('front.reports');
    Route::get('/useraccount-feedback', [FrontController::class, 'showRatings'])->name('front.ratings');
    Route::get('/useraccount-bookings', [FrontController::class, 'showBookings'])->name('front.bookings');
    Route::get('/export', [FrontController::class, 'export'])->name('front.export');
    Route::get('export-pdf', [FrontController::class, 'exportPdf'])->name('front.export.pdf');
    Route::post('/useraccount-bookings/cancel/{id}', [FrontController::class, 'cancelBooking'])->name('front.booking.cancel');
    Route::get('/useraccount-patients', [FrontController::class, 'showSavedpatients'])->name('front.patients');
    Route::get('/useraccount-notification', [FrontController::class, 'showNotifications'])->name('front.notifications');
    Route::get('/useraccount-settings', [FrontController::class, 'showSettings'])->name('front.settings');
    Route::post('/useraccount-settings/update', [FrontController::class, 'updateSettings'])->name('front.settings.update');
    Route::post('/useraccount-patients', [FrontController::class, 'putPatients'])->name('front.patients.store');
    Route::get('/useraccount-patients/get_sub_insurence', [FrontController::class, 'fetchSubInsurence'])->name('front.sub_insurance');
    Route::delete('/useraccount-patients/{id}', [FrontController::class, 'deleteMember'])->name('front.patients.delete');
    Route::get('/useraccount-appointment-details/{id}', [FrontController::class, 'showBookingDetails'])->name('front.appointment-details');
    Route::delete("/useraccount-deleteDocs/{id}", [FrontController::class, 'deleteDocs'] )->name('front.docs.delete');
    Route::post('/start-call', [FrontController::class, 'startCall']);
    Route::post('/end-call-status', [FrontController::class, 'endCallStatus']);
    

   // Invoice Routes
    
    Route::get('/useraccount-invoices', [App\Http\Controllers\front\InvoiceController::class, 'index'])->name('front.invoices.index');
    Route::get('/useraccount-invoices/{id}', [App\Http\Controllers\front\InvoiceController::class, 'show'])->name('front.invoices.show');
    Route::get('/useraccount-invoices/{id}/download', [App\Http\Controllers\front\InvoiceController::class, 'download'])->name('front.invoices.download');

    Route::post('/profile/verify-phone-otp',
    [ProfileController::class, 'verifyPhoneOtp'])
    ->name('front.profile.verify.phone.otp');
    
    Route::get('/doctor-list', function() {
        return view('front.doctor-list');
    })->name('front.doctor-list');
    
    Route::get('/doctor-details', function() {
        return view('front.doctor-details');
    })->name('front.doctor-details');
    
    Route::get('/book-an-appointment', function() {
        return view('front.book-an-appointment');
    })->name('front.book-an-appointment');
    
    // Route::get('/checkout', function() {
    //     return view('front.checkout');
    // })->name('front.checkout');
    
    Route::get('/success-message', function() {
        return view('front.success-message');
    })->name('front.success-message');
});

Route::get('/doctors-list', [DoctorController::class, 'index'])->name('doctor_list');

Route::get('/doctor-profile/{id}', [DoctorController::class, 'doctor_profile'])->name('doctor_profile');
Route::get('/book-dr-appointment/{doctor_id}', [DoctorController::class, 'book_appointment'])->name('book_appointment'); //for web

Route::post("/appointments/check_doctor_availability", [DoctorController::class, 'check_doctor_availability'])->name('front.appointments.check_doctor_availability');
Route::post("/appointments/save-feedback", [DoctorController::class, 'saveFeedback'])->name('front.appointments.saveFeedback');
Route::post("/appointments/update-feedback", [DoctorController::class, 'updateFeedback'])->name('front.appointments.updateFeedback');
Route::post("/appointments/delete-feedback", [DoctorController::class, 'deleteFeedback'])->name('front.appointments.delete-feedback');

Route::post('/approve-document', [DoctorController::class, 'approveDocument'])
    ->name('approve.document');
Route::get('front/get-members', [DoctorController::class, 'getPatientMembers'])->name('web.get-members');


Route::post('front/booking-overview', [DoctorController::class, 'overview_booking'])->name('overview_booking'); //for web
Route::get('front/booking-overview', [DoctorController::class, 'overview_booking'])->name('overview_booking'); //for web

Route::get('front/guest-booking-overview', [DoctorController::class, 'guest_overview_booking'])->name('guest_overview_booking'); //for web


Route::post('/store-location', function (\Illuminate\Http\Request $request) {
    session([
        'current_latitude' => $request->latitude,
        'current_longitude' => $request->longitude,
    ]);

    return response()->json(['status' => 'success']);
})->name('store.location');

Route::get('front/booking-process', [DoctorController::class, 'redirectToStripe'])->name('booking-process'); //for web
Route::get('front/booking-confirm', [DoctorController::class, 'book_appointment_save'])->name('front.booking-confirm'); //for web

Route::post("front/patient/rescheduleAppointment",[DoctorController::class, 'rescheduleAppointment'])->name('front.patient-rescheduleAppointment');

Route::get('/appointment-payment/{token}', [App\Http\Controllers\front\AppointmentPaymentController::class, 'showPaymentPage'])->name('front.appointment-payment');
Route::post('/appointment-payment/process', [App\Http\Controllers\front\AppointmentPaymentController::class, 'processPayment'])->name('front.appointment-payment.process');
Route::get('/appointment-payment/success/{token}', [App\Http\Controllers\front\AppointmentPaymentController::class, 'paymentSuccess'])->name('front.appointment-payment.success');
Route::get('/appointment-payment/cancel/{token}', [App\Http\Controllers\front\AppointmentPaymentController::class, 'paymentCancel'])->name('front.appointment-payment.cancel');
