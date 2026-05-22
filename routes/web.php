<?php

use App\Http\Controllers\admin\LoginController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FindDoctorsController;
use App\Http\Controllers\api\v1\UsersController;
use App\Http\Controllers\admin\InsurencePolicyController;
use App\Http\Controllers\admin\AreasController;
use App\Http\Controllers\admin\ContactUsEntryController;
use App\Http\Controllers\admin\HomepageManagementController;
use App\Http\Controllers\admin\HospitalController;
use App\Models\Article;
use App\Models\User;

Route::get('/clear', function () {
    Artisan::call('optimize');
    Artisan::call('optimize:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    dd('cleared');
});




// website route
// Route::get('notifications', [HomeController::class,'notifications'])->name('notifications');
// Route::get('/google_login',[HomeController::class,'redirectToGoogle'])->name('google_login');
// Route::get('/google_success',[HomeController::class,'handleGoogleCallback'])->name('google_success');
Route::get('/', 'App\Http\Controllers\admin\LoginController@login')->name('web');
// Route::get('/website', [HomeController::class, 'index'])->name('home');
// Route::get('/website/doctors-list', [HomeController::class, 'doctor_list'])->name('doctor_list');
// Route::get('/website/search-doctors-list', [HomeController::class, 'load_doctors'])->name('load_doctors');
// Route::get('/website/doctor-profile/{id}', [HomeController::class, 'doctor_profile'])->name('doctor_profile');
// Route::get('/website/about-us', [HomeController::class, 'about_us'])->name('about_us');
Route::match(array('GET', 'POST'), '/website/contact-us', [ContactUsEntryController::class, 'indexAndCreate'])->name('contact_us');
// Route::get('/website/privacy-policy', [HomeController::class, 'privacy'])->name('privacy-policy');
// Route::get('/website/benefits-for-doctors-and-patients', [HomeController::class, 'benefits_for_doctors_and_patients'])->name('benefits_for_doctors_and_patients');
Route::get('/find-doctors', [FindDoctorsController::class, 'index'])->name('patientsfind_a_doctor');
Route::post('/filter-doctors', [FindDoctorsController::class, 'get_doctors'])->name('get_doctors');
Route::post('/get_hospital_list', [FindDoctorsController::class, 'get_hospital_list'])->name('get_hospital_list');
Route::post('/set_session_location', [FindDoctorsController::class, 'set_session_location'])->name('set_session_location');
Route::post('/change-password', [LoginController::class, 'resetPassword'])->name('change-password');
Route::post('/check-login', [LoginController::class, 'checkValidLogin'])->name('check-login');
// Route::get('/hospital_profile/{id}', [HomeController::class, 'get_hospital_profile'])->name('get_hospital_profile');
// Route::get('/website/faq-for-patient', [HomeController::class, 'faq_for_patient'])->name('faq-for-patient');
// Route::get('/website/faq-for-doctors', [HomeController::class, 'faq_for_doctor'])->name('faq-for-doctor');
// Route::get('/website/faq-for-clinic-hospital', [HomeController::class, 'faq_for_hospital'])->name('faq-for-hospital');
// Route::get('/website/delete-account', [HomeController::class, 'deleteAccount'])->name('delete-account');
// Route::post('/website/delete-account-submit', [HomeController::class, 'deleteAccountSubmit'])->name('delete-account-submit');
// Route::get('/website/terms-conditions', [HomeController::class, 'terms_condition'])->name('terms-conditions');
// // Route::get('/website/contact-us', [HomeController::class, 'contact_us'])->name('contact_us');
// Route::get('/website/patient-login', [HomeController::class, 'login'])->name('patient.login');
// Route::get('/website/patient-profile', [HomeController::class, 'profile'])->name('patient.profile');
// Route::post('/website/patient-profile-save', [HomeController::class, 'profileSave'])->name('patient.profileSave');
// Route::get('/website/patient-appointment', [HomeController::class, 'appointments'])->name('patient.appointments');
// Route::get('/appointments', [HomeController::class, 'appointments'])->name('patient.appointments.list');
// Route::get('/website/patient-appointment_detail/{id}', [HomeController::class, 'bookingDetail'])->name('patient.appointment_detail');
// Route::post("website/my-appointment-loaddata", "App\Http\Controllers\admin\PatientsController@appointmentLoadData")->name('patients.MyAppointmentLoadData');
// Route::get('/website/logout', [HomeController::class, 'logout'])->name('logout_web');
// Route::get('/website/patient-signup', [HomeController::class, 'signup'])->name('patient.signup');
Route::get('/get-sub-insurance/{id}', [InsurencePolicyController::class, 'getSubInsurence']);
Route::get('/get-area/{emirateId}', [AreasController::class, 'getAreas']);
Route::get('/get-hospitals', [HospitalController::class, 'getFilteredHospitals']);
Route::post("web/verify_signup_otp_web", [UsersController::class, 'verify_signup_otp_web']); //for web
Route::post("web/sign_in_with_phone_web", [UsersController::class, 'sign_in_with_phone_web']); //for web
Route::post("web/resend_phone_otp", [UsersController::class, 'resend_phone_otp']); //for web
Route::post("web/verify_sign_in_with_phone_otp_web", [UsersController::class, 'verify_sign_in_with_phone_otp_web']); //for web
Route::post('web/email_login_web', [UsersController::class, 'email_login_web'])->name('email_login_web'); //for web
Route::post("web/confirm_email_code_web", [UsersController::class, 'confirm_email_code_web'])->name("confirm_email_code_web"); //for web
// Route::match(['GET', 'POST'], 'website/change_password', [HomeController::class, 'change_password'])->name('web.change_password');
// Route::get('web/book-dr-appointment/{doctor_id}', [HomeController::class, 'book_appointment'])->name('book_appointment'); //for web
// Route::post('web/booking-overview', [HomeController::class, 'overview_booking'])->name('overview_booking'); //for web
// Route::get('web/guest-booking-overview', [HomeController::class, 'guest_overview_booking'])->name('guest_overview_booking'); //for web
// Route::post('web/booking-confirm', [HomeController::class, 'book_appointment_save'])->name('booking-confirm'); //for web
// Route::post("web/appointments/check_doctor_availability", [HomeController::class, 'check_doctor_availability'])->name('web.appointments.check_doctor_availability');
// Route::post("web/patient/rescheduleAppointment",[HomeController::class, 'rescheduleAppointment'])->name('web.patient-rescheduleAppointment');
// Route::post("web/patient/patient_appointment_cancel",[HomeController::class, 'patientAppointmentCancel'])->name('web.patient-patient_appointment_cancel');
Route::post('/forgot-password', 'App\Http\Controllers\admin\LoginController@forgotPassword')->name('forgot-password');
Route::post("reset-password", 'App\Http\Controllers\admin\LoginController@verify_and_reset_password');
Route::post('web/save-members', 'App\Http\Controllers\admin\PatientsController@saveMember')->name('web.save-members');
// Route::get('web/get-members', [HomeController::class, 'getPatientMembers'])->name('web.get-members');

// route for static pages
// Route::get('/website/patient-instructions', [HomeController::class, 'patient_instructions'])->name('patient-instructions');
// Route::get('/website/doctor-instructions', [HomeController::class, 'doctor_instructions'])->name('doctor-instructions');
// Route::get('/website/clinic-instructions', [HomeController::class, 'clinic_instructions'])->name('clinic-instructions');
// Route::get('/website/hospital-instructions', [HomeController::class, 'hospital_instructions'])->name('hospital-instructions');
// // Route::get('web/member_edit/{id}', [HomeController::class,'editMember'])->name('patient.member_edit');
// Route::post('web/load-members', [HomeController::class,'loadMembers'])->name('web.load-members');
// Route::get('website/patient-members', [HomeController::class,'patientMembers'])->name('web.my-members');
Route::delete('web/delete-member/{id}', 'App\Http\Controllers\admin\PatientsController@deleteMember')->name('web.deleteMember');

// ------------ Admin Routes -------------

Route::middleware('guest')->group(function () {
    Route::get('/admin', 'App\Http\Controllers\admin\LoginController@login')->name('admin.login');
});

Route::post('admin/check_login', 'App\Http\Controllers\admin\LoginController@check_login')->name('admin.check_login');

Route::namespace('App\Http\Controllers\admin')->prefix('admin')->middleware('admin')->name('admin.')->group(function () {
    
    Route::post('/chat/init', [App\Http\Controllers\admin\ChatController::class, 'init'])->name('chat.init');
    Route::get('/chat/conversations', [App\Http\Controllers\admin\ChatController::class, 'getConversations'])->name('chat.conversations');
    Route::get('/chat/users', [App\Http\Controllers\admin\ChatController::class, 'getUsers'])->name('chat.users');
    Route::get('/chat/messages/{receiverId}', [App\Http\Controllers\admin\ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/send', [App\Http\Controllers\admin\ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/upload', [App\Http\Controllers\admin\ChatController::class, 'uploadAttachment'])->name('chat.upload');
    Route::post('/chat/mark-read', [App\Http\Controllers\admin\ChatController::class, 'markAsRead'])->name('chat.mark-read');
    Route::post('/chat/unread-count', [App\Http\Controllers\admin\ChatController::class, 'getUnreadCount'])->name('chat.unread-count');
    Route::post('/chat/check-new', [App\Http\Controllers\admin\ChatController::class, 'checkNewMessages'])->name('chat.check');
    Route::post('/chat/notify', [App\Http\Controllers\admin\ChatController::class, 'notifyMessage'])->name('chat.notify');
    Route::get('/chat', [App\Http\Controllers\admin\ChatController::class, 'index'])->name('chat.index');

    // Add to your admin routes group
 
    // In your admin routes file
    Route::get('/chat-monitor', [App\Http\Controllers\admin\ChatMonitorController::class, 'index'])->name('chat.monitor');
    Route::get('/chat-monitor/users', [App\Http\Controllers\admin\ChatMonitorController::class, 'getUsers'])->name('chat.monitor.users');
    // Exporting routes..
    Route::get('/hospitals/export', 'HospitalController@export')->name('hospitals.export');
    Route::get('/appointments/export', 'AppointmentsController@export')->name('appointments.export');

    Route::get('/hospitals/export_excel', 'HospitalController@export_excel')->name('hospitals.export_excel');
    Route::post('/hospitals/upload-hospital-zip', 'HospitalController@uploadAndExtractZip')->name('hospitals.upload-hospital-zip');
    Route::post("/hopsitals/import_hospital", 'HospitalController@import')->name('hospitals.import');

    Route::get('access-restricted', 'AdminUserController@access_restricted')->name('restricted_page');
    Route::get('dashboard', 'DashboardController@dashboard')->name('dashboard');
    Route::get('notifications', 'DashboardController@notifications')->name('notifications');
    Route::get('logout', 'LoginController@logout')->name('logout');

    Route::match(array('GET', 'POST'), 'change_password', 'UsersController@change_password');
    Route::match(array('GET', 'POST'), 'change_user_password', 'UsersController@change_user_password');

    Route::resource("admin_users", "AdminUserController");
    Route::post("admin_users/change_status", "AdminUserController@change_status");
    Route::post("admin_users/verify", "AdminUserController@verify");
    Route::get("admin_users/update_permission/{id}", "AdminUserController@update_permission");
    Route::post("save_privilege", "AdminUserController@save_privilege");

    // user roles
    Route::get('user_roles/list', 'UserRoleController@index')->name('user_roles.list');
    Route::get('user_roles/create', 'UserRoleController@create')->name('user_roles.create');
    Route::get('user_roles/edit/{id}', 'UserRoleController@create')->name('user_roles.edit');
    Route::post('user_roles/submit', 'UserRoleController@submit')->name('user_roles.submit');
    Route::delete('user_roles/delete/{id}', 'UserRoleController@delete')->name('user_roles.delete');
    Route::post('user_roles/get_role_list', 'UserRoleController@getroleList')->name('getRoleList');
    Route::post('user_roles/status_change/{id}', 'UserRoleController@change_status')->name('user_roles.status_change');


    Route::get('cms_pages', 'PagesController@index')->name('cms_pages');
    Route::get('page/create', 'PagesController@create')->name('cms_pages.add');
    Route::get('page/edit/{id}', 'PagesController@edit')->name('cms_pages.edit');
    Route::post('page/save', 'PagesController@save')->name('cms_pages.save');
    Route::delete('page/delete/{id}', 'PagesController@delete')->name('cms_pages.delete');
    Route::get('contact_details', 'PagesController@contact_details')->name('contact_details');
    Route::post("contact_us_setting_store", "PagesController@contact_us_setting_store")->name('contact_us_setting_store');
    Route::get('settings', 'PagesController@settings')->name("settings");
    Route::post("setting_store", "PagesController@setting_store")->name('setting_store');

    // Contact us entries
    Route::get("contact-us-entries", [ContactUsEntryController::class, 'index'])->name('contact-us-entries.index');
    Route::get("contact-us-entries/show/{contact_us}", [ContactUsEntryController::class, 'show'])->name('contact-us-entries.show');
    Route::post("contact-us-entries/status/{contact_us}", [ContactUsEntryController::class, 'updateStatus'])
    ->name('contact-us-entries.updateStatus');


    //clinic_insctruction for patient
    Route::get("clinic_insctruction", "ClinicInstructionController@index");
    Route::match(array('GET', 'POST'), 'clinic_insctruction/create', 'ClinicInstructionController@create');
    Route::get("clinic_insctruction/edit/{id}", "ClinicInstructionController@edit");
    Route::post("clinic_insctruction/update", "ClinicInstructionController@update");
    Route::delete("clinic_insctruction/delete/{id}", "ClinicInstructionController@delete");

    //Doctore_insctruction for patient
    Route::get("doctor_insctruction", "DoctorInstructionController@index");
    Route::match(array('GET', 'POST'), 'doctor_insctruction/create', 'DoctorInstructionController@create');
    Route::get("doctor_insctruction/edit/{id}", "DoctorInstructionController@edit");
    Route::post("doctor_insctruction/update", "DoctorInstructionController@update");
    Route::delete("doctor_insctruction/delete/{id}", "DoctorInstructionController@delete");

    //Doctore_insctruction for patient
    Route::get("hospital_insctruction", "HospitalInstructionController@index");
    Route::match(array('GET', 'POST'), 'hospital_insctruction/create', 'HospitalInstructionController@create');
    Route::get("hospital_insctruction/edit/{id}", "HospitalInstructionController@edit");
    Route::post("hospital_insctruction/update", "HospitalInstructionController@update");
    Route::delete("hospital_insctruction/delete/{id}", "HospitalInstructionController@delete");

    //Doctore_insctruction for patient
    Route::get("user_insctruction", "UserInstructionController@index");
    Route::match(array('GET', 'POST'), 'user_insctruction/create', 'UserInstructionController@create');
    Route::get("user_insctruction/edit/{id}", "UserInstructionController@edit");
    Route::post("user_insctruction/update", "UserInstructionController@update");
    Route::delete("user_insctruction/delete/{id}", "UserInstructionController@delete");

    //FAQ for patient
    Route::get("faq", "FaqController@index")->named('faq');
    Route::match(array('GET', 'POST'), 'faq/create', 'FaqController@create')->named('faq.create');
    Route::get("faq/edit/{id}", "FaqController@edit")->named('faq.edit');
    Route::post("faq/update", "FaqController@update")->named('faq.update');
    Route::delete("faq/delete/{id}", "FaqController@delete")->named('faq.delete');

    //FAQ for patient
    Route::get("faq", "FaqController@index");
    Route::match(array('GET', 'POST'), 'faq/create', 'FaqController@create');
    Route::get("faq/edit/{id}", "FaqController@edit");
    Route::post("faq/update", "FaqController@update");
    Route::delete("faq/delete/{id}", "FaqController@delete");

    // FAQ for doctors
    Route::get("faq-for-doctor", "FaqDoctorController@index");
    Route::match(array('GET', 'POST'), 'faq-for-doctor/create', 'FaqDoctorController@create');
    Route::get("faq-for-doctor/edit/{id}", "FaqDoctorController@edit");
    Route::post("faq-for-doctor/update", "FaqDoctorController@update");
    Route::delete("faq-for-doctor/delete/{id}", "FaqDoctorController@delete");

    // FAQ for clinic/hospital
    Route::get("faq-for-hospital", "FaqHospitalController@index");
    Route::match(array('GET', 'POST'), 'faq-for-hospital/create', 'FaqHospitalController@create');
    Route::get("faq-for-hospital/edit/{id}", "FaqHospitalController@edit");
    Route::post("faq-for-hospital/update", "FaqHospitalController@update");
    Route::delete("faq-for-hospital/delete/{id}", "FaqHospitalController@delete");

    Route::get("help", "ContactQueryController@index");
    Route::match(array('GET', 'POST'), 'help/create', 'HelpController@create');
    Route::get("help/edit/{id}", "HelpController@edit");
    Route::post("help/update", "HelpController@update");
    Route::delete("help/delete/{id}", "HelpController@delete");

    // Qualifications
    Route::get('qualifications/list', 'QualificationController@index')->name('qualifications.list');
    Route::get('qualifications/create', 'QualificationController@create')->name('qualifications.create');
    Route::get('qualifications/edit/{id}', 'QualificationController@create')->name('qualifications.edit');
    Route::post('qualifications/submit', 'QualificationController@submit')->name('qualifications.submit');
    Route::delete('qualifications/delete/{id}', 'QualificationController@delete')->name('qualifications.delete');
    Route::post('qualifications/change_status', 'QualificationController@change_status')->name('qualifications.status_change');
    // Departments
    Route::get('departments/list', 'DepartmentController@index')->name('departments.list');
    Route::get('departments/create', 'DepartmentController@create')->name('departments.create');
    Route::get('departments/edit/{id}', 'DepartmentController@create')->name('departments.edit');
    Route::post('departments/submit', 'DepartmentController@submit')->name('departments.submit');
    Route::delete('departments/delete/{id}', 'DepartmentController@delete')->name('departments.delete');
    // Route::post('departments/change_status', 'DepartmentController@change_status')->name('qualifications.status_change');
    Route::get('get-hospital-departments/{hospital_id}', 'DepartmentController@getHospitalDepartments')->name('admin.get-hospital-department');

    // Licence Types
    Route::get('licencetype/list', 'LicenceTypeController@index')->name('licencetype.list');
    Route::get('licencetype/create', 'LicenceTypeController@create')->name('licencetype.create');
    Route::get('licencetype/edit/{id}', 'LicenceTypeController@create')->name('licencetype.edit');
    Route::post('licencetype/submit', 'LicenceTypeController@submit')->name('licencetype.submit');
    Route::delete('licencetype/delete/{id}', 'LicenceTypeController@delete')->name('licencetype.delete');
    Route::post('licencetype/change_status', 'LicenceTypeController@change_status')->name('licencetype.status_change');

    // Special Intrests
    Route::get('special_intrests/list', 'SpecialIntrestsController@index')->name('special_intrests.list');
    Route::get('special_intrests/create', 'SpecialIntrestsController@create')->name('special_intrests.create');
    Route::get('special_intrests/edit/{id}', 'SpecialIntrestsController@create')->name('special_intrests.edit');
    Route::post('special_intrests/submit', 'SpecialIntrestsController@submit')->name('special_intrests.submit');
    Route::delete('special_intrests/delete/{id}', 'SpecialIntrestsController@delete')->name('special_intrests.delete');
    Route::post('special_intrests/change_status', 'SpecialIntrestsController@change_status')->name('special_intrests.status_change');// Special Intrests


    // Special Intrests
    Route::get('refferal_doctors/list', 'RefferalDoctorsController@index')->name('refferal_doctors.list');
    Route::get('refferal_doctors/create', 'RefferalDoctorsController@create')->name('refferal_doctors.create');
    Route::get('refferal_doctors/edit/{id}', 'RefferalDoctorsController@create')->name('refferal_doctors.edit');
    Route::post('refferal_doctors/submit', 'RefferalDoctorsController@submit')->name('refferal_doctors.submit');
    Route::delete('refferal_doctors/delete/{id}', 'RefferalDoctorsController@delete')->name('refferal_doctors.delete');
    Route::post('refferal_doctors/change_status', 'RefferalDoctorsController@change_status')->name('refferal_doctors.status_change');// Special Intrests



    // Special Intrests
    Route::get('referrals/list', 'ReferralController@index')->name('referrals.list');
    Route::get('referrals/create', 'ReferralController@create')->name('referrals.create');
    Route::get('referrals/edit/{id}', 'ReferralController@create')->name('referrals.edit');
    Route::post('referrals/submit', 'ReferralController@submit')->name('referrals.submit');
    Route::delete('referrals/delete/{id}', 'ReferralController@delete')->name('referrals.delete');
    Route::post('referrals/change_status', 'ReferralController@change_status')->name('referrals.status_change');// Special Intrests
   
   
    Route::get('medicin_categories/list', 'MedicinCategoryController@index')->name('medicin_categories.list');
    Route::get('medicin_categories/create', 'MedicinCategoryController@create')->name('medicin_categories.create');
    Route::get('medicin_categories/edit/{id}', 'MedicinCategoryController@create')->name('medicin_categories.edit');
    Route::post('medicin_categories/submit', 'MedicinCategoryController@submit')->name('medicin_categories.submit');
    Route::delete('medicin_categories/delete/{id}', 'MedicinCategoryController@delete')->name('medicin_categories.delete');
    Route::post('medicin_categories/change_status', 'MedicinCategoryController@change_status')->name('medicin_categories.status_change');

    // Special Intrests
    // Route::get('medicines/list', 'MedicineController@index')->name('medicines.list');
    // Route::get('medicines/create', 'MedicineController@create')->name('medicines.create');
    // Route::get('medicines/edit/{id}', 'MedicineController@create')->name('medicines.edit');
    // Route::post('medicines/submit', 'MedicineController@submit')->name('medicines.submit');
    // Route::delete('medicines/delete/{id}', 'MedicineController@delete')->name('medicines.delete');
    // Route::post('medicines/change_status', 'MedicineController@change_status')->name('medicines.status_change');

    // Route::get('product-tags/list', 'ProductTagController@index')->name('admin.product_tags.list');
    // Route::get('product-tags/create', 'ProductTagController@create')->name('admin.product_tags.create');
    // Route::get('product-tags/edit/{id}', 'ProductTagController@create')->name('admin.product_tags.edit');
    // Route::post('product-tags/submit', 'ProductTagController@submit')->name('admin.product_tags.submit');
    // Route::delete('product-tags/delete/{id}', 'ProductTagController@delete')->name('admin.product_tags.delete');
    // Route::post('product-tags/change_status', 'ProductTagController@change_status')->name('admin.product_tags.status_change');

    Route::get('medicines/list', 'MedicineController@index')->name('medicines.list');
    Route::get('medicines/create', 'MedicineController@create')->name('medicines.create');
    Route::get('medicines/edit/{id}', 'MedicineController@create')->name('medicines.edit');
    Route::post('medicines/submit', 'MedicineController@submit')->name('medicines.submit');
    Route::delete('medicines/delete/{id}', 'MedicineController@delete')->name('medicines.delete');
    Route::post('medicines/change_status', 'MedicineController@change_status')->name('medicines.status_change');
    Route::post('medicines/toggle_featured', 'MedicineController@toggle_featured')->name('medicines.toggle_featured');

     Route::group(['prefix' => 'coupons'], function() {
        Route::get('/list', 'CouponController@index')->name('coupons.list');
        Route::get('/create', 'CouponController@create')->name('coupons.create');
        Route::get('/edit/{id}', 'CouponController@create')->name('coupons.edit');
        Route::post('/submit', 'CouponController@submit')->name('coupons.submit');
        Route::delete('/delete/{id}', 'CouponController@delete')->name('coupons.delete');
        Route::post('/change_status', 'CouponController@change_status')->name('coupons.status_change');
        Route::get('/report', 'CouponController@report')->name('coupons.report');
        Route::post('/validate', 'CouponController@validateCoupon')->name('coupons.validate');
    });

    Route::get('orders', [App\Http\Controllers\admin\AdminOrderController::class, 'index'])->name('orders.list');
    Route::get('orders/view/{id}', [App\Http\Controllers\admin\AdminOrderController::class, 'view'])->name('orders.view');
    Route::post('orders/change-status', [App\Http\Controllers\admin\AdminOrderController::class, 'change_status'])->name('orders.change_status');
    Route::get('orders/print/{id}', [App\Http\Controllers\admin\AdminOrderController::class, 'printInvoice'])->name('orders.print');
    Route::get('orders/status-options', [App\Http\Controllers\admin\AdminOrderController::class, 'getStatusOptions'])->name('orders.status_options');
    Route::get('orders/export', [App\Http\Controllers\admin\AdminOrderController::class, 'export'])->name('orders.export');

    // Product Tag Routes
    Route::get('product-tags/list', 'ProductTagController@index')->name('product_tags.list');
    Route::get('product-tags/create', 'ProductTagController@create')->name('product_tags.create');
    Route::get('product-tags/edit/{id}', 'ProductTagController@create')->name('product_tags.edit');
    Route::post('product-tags/submit', 'ProductTagController@submit')->name('product_tags.submit');
    Route::delete('product-tags/delete/{id}', 'ProductTagController@delete')->name('product_tags.delete');
    Route::post('product-tags/change_status', 'ProductTagController@change_status')->name('product_tags.status_change');

    // Special Intrests
    Route::get('dosage/list', 'DosageController@index')->name('dosage.list');
    Route::get('dosage/create', 'DosageController@create')->name('dosage.create');
    Route::get('dosage/edit/{id}', 'DosageController@create')->name('dosage.edit');
    Route::post('dosage/submit', 'DosageController@submit')->name('dosage.submit');
    Route::delete('dosage/delete/{id}', 'DosageController@delete')->name('dosage.delete');
    Route::post('dosage/change_status', 'DosageController@change_status')->name('dosage.status_change');


    // Website Services
    Route::get('website_services/list', 'WebsiteServicesController@index')->name('website_services.list');
    Route::get('website_services/create', 'WebsiteServicesController@create')->name('website_services.create');
    Route::get('website_services/edit/{id}', 'WebsiteServicesController@create')->name('website_services.edit');
    Route::post('website_services/submit', 'WebsiteServicesController@submit')->name('website_services.submit');
    Route::delete('website_services/delete/{id}', 'WebsiteServicesController@delete')->name('website_services.delete');
    Route::post('website_services/change_status', 'WebsiteServicesController@change_status')->name('website_services.status_change');


    // Special Intrests
    Route::get('durations/list', 'DurationController@index')->name('durations.list');
    Route::get('durations/create', 'DurationController@create')->name('durations.create');
    Route::get('durations/edit/{id}', 'DurationController@create')->name('durations.edit');
    Route::post('durations/submit', 'DurationController@submit')->name('durations.submit');
    Route::delete('durations/delete/{id}', 'DurationController@delete')->name('durations.delete');
    Route::post('durations/change_status', 'DurationController@change_status')->name('durations.status_change');


    // Frequencies
    Route::get('frequencies/list', 'FrequenciesController@index')->name('frequencies.list');
    Route::get('frequencies/create', 'FrequenciesController@create')->name('frequencies.create');
    Route::get('frequencies/edit/{id}', 'FrequenciesController@create')->name('frequencies.edit');
    Route::post('frequencies/submit', 'FrequenciesController@submit')->name('frequencies.submit');
    Route::delete('frequencies/delete/{id}', 'FrequenciesController@delete')->name('frequencies.delete');
    Route::post('frequencies/change_status', 'FrequenciesController@change_status')->name('frequencies.status_change');


    // Directions
    Route::get('directions/list', 'DirectionsController@index')->name('directions.list');
    Route::get('directions/create', 'DirectionsController@create')->name('directions.create');
    Route::get('directions/edit/{id}', 'DirectionsController@create')->name('directions.edit');
    Route::post('directions/submit', 'DirectionsController@submit')->name('directions.submit');
    Route::delete('directions/delete/{id}', 'DirectionsController@delete')->name('directions.delete');
    Route::post('directions/change_status', 'DirectionsController@change_status')->name('directions.status_change');
    
    // Brands
    Route::get('brands/list', 'BrandsController@index')->name('brands.list');
    Route::get('brands/create', 'BrandsController@create')->name('brands.create');
    Route::get('brands/edit/{id}', 'BrandsController@create')->name('brands.edit');
    Route::post('brands/submit', 'BrandsController@submit')->name('brands.submit');
    Route::delete('brands/delete/{id}', 'BrandsController@delete')->name('brands.delete');
    Route::post('brands/change_status', 'BrandsController@change_status')->name('brands.status_change');
    
    // Languages
    Route::get('languages/list', 'LanguageController@index')->name('languages.list');
    Route::get('languages/create', 'LanguageController@create')->name('languages.create');
    Route::get('languages/edit/{id}', 'LanguageController@create')->name('languages.edit');
    Route::post('languages/submit', 'LanguageController@submit')->name('languages.submit');
    Route::delete('languages/delete/{id}', 'LanguageController@delete')->name('languages.delete');
    Route::post('languages/change_status', 'LanguageController@change_status')->name('languages.status_change');
    Route::get('/import-languages-file',  'LanguageController@importLanguagesFromFile')->name('import.languages.file');

    // Medical Condition
    Route::get('medical_condition/list', 'MedicalConditionController@index')->name('medical_condition.list');
    Route::get('medical_condition/create', 'MedicalConditionController@create')->name('medical_condition.create');
    Route::get('medical_condition/edit/{id}', 'MedicalConditionController@create')->name('medical_condition.edit');
    Route::post('medical_condition/submit', 'MedicalConditionController@submit')->name('medical_condition.submit');
    Route::delete('medical_condition/delete/{id}', 'MedicalConditionController@delete')->name('medical_condition.delete');
    Route::post('medical_condition/change_status', 'MedicalConditionController@change_status')->name('medical_condition.status_change');
    // Insurence Policy
    Route::get('insurance_policy/list', 'InsurencePolicyController@index')->name('insurence_policy.list');
    Route::get('insurance_policy/create', 'InsurencePolicyController@create')->name('insurence_policy.create');
    Route::get('insurance_policy/edit/{id}', 'InsurencePolicyController@create')->name('insurence_policy.edit');
    Route::post('insurance_policy/submit', 'InsurencePolicyController@submit')->name('insurence_policy.submit');
    Route::delete('insurance_policy/delete/{id}', 'InsurencePolicyController@delete')->name('insurence_policy.delete');
    Route::post('insurance_policy/change_status', 'InsurencePolicyController@change_status')->name('insurence_policy.status_change');
    Route::get('get-sub-insurance/{id}', 'InsurencePolicyController@getSubInsurence')->name('admin.get-subInsurence');
    // Sub Insurence Policy
    Route::get('sub_insurance_policy/list', 'SubInsurencePolicyController@index')->name('sub_insurence_policy.list');
    Route::get('sub_insurance_policy/create', 'SubInsurencePolicyController@create')->name('sub_insurence_policy.create');
    Route::get('sub_insurance_policy/edit/{id}', 'SubInsurencePolicyController@create')->name('sub_insurence_policy.edit');
    Route::post('sub_insurance_policy/submit', 'SubInsurencePolicyController@submit')->name('sub_insurence_policy.submit');
    Route::delete('sub_insurance_policy/delete/{id}', 'SubInsurencePolicyController@delete')->name('sub_insurence_policy.delete');
    Route::post('sub_insurance_policy/change_status', 'SubInsurencePolicyController@change_status')->name('sub_insurence_policy.status_change');

    // Services
    Route::get('services/list', 'ServicesController@index')->name('services.list');
    Route::get('services/create', 'ServicesController@create')->name('services.create');
    Route::get('services/edit/{id}', 'ServicesController@create')->name('services.edit');
    Route::post('services/submit', 'ServicesController@submit')->name('services.submit');
    Route::delete('services/delete/{id}', 'ServicesController@delete')->name('services.delete');
    Route::post('services/change_status', 'ServicesController@change_status')->name('services.status_change');

    // Specialties
    Route::resource("specialties", "SpecialtyController");
    Route::post("specialties/change_status", "SpecialtyController@change_status");

    // Countries
    Route::resource("countries", "CountryController");
    Route::resource("country-of-origin", "CountryOriginController");
    Route::post("countries/change_status", "CountryController@change_status");
    Route::post("country-of-origin/change_status", "CountryOriginController@change_status");
    Route::post("countries/store_origin", "CountryController@storeOrigin")->name('countries.storeigin');;
    // Emirates
    Route::resource("emirates", "EmiratesController");
    Route::post("emirates/change_status", "EmiratesController@change_status");
    Route::get('get-emirates/{countryId}', 'EmiratesController@getEmirates')->name('admin.get-emirates');

    // Areas
    Route::resource("areas", "AreasController");
    Route::post("areas/change_status", "AreasController@change_status");
    Route::get('get-areas/{emirateId}', 'AreasController@getAreas')->name('admin.get-areas');

    // Banners
    Route::resource("banners", "BannerController");
    Route::post("banners/change_status", "BannerController@change_status");
    
    // Banners
    Route::resource("wellness_tips", "WellnessTipController");
    Route::post("wellness_tips/change_status", "WellnessTipController@change_status");

    Route::resource("videos", "VideoController");
    Route::post("videos/change_status", "VideoController@change_status");

    // Hp partner logos
    Route::resource("hp-partner-logos", "HpPartnerLogosController");
    Route::post("hp-partner-logos/change_status", "HpPartnerLogosController@change_status");

    // Hp slides
    Route::resource("hp-slides", "HpSlidesController");
    Route::post("hp-slides/change_status", "HpSlidesController@change_status");

    // Homepage Management
    Route::get('homepage-management', [HomepageManagementController::class, 'edit'])->name('homepage-management');
    Route::post("homepage-management/update", [HomepageManagementController::class, 'update'])->name('homepage-management.update');


    // Reviews
    Route::get("reviews", "HospitalController@HospitalDoctorsReviews")->name('hospitals.reviews');
    Route::get("review/edit/{id}", "HospitalController@HospitalDoctorsEdit")->name('reviews.edit');
    Route::post("review_update/{id}", "HospitalController@review_update")->name('review_update');
    Route::post("change_review_status", "HospitalController@change_review_status")->name('change_review_status');

    // Hospitals
    Route::get("hospitals", "HospitalController@index")->name('hospitals.index');
    Route::get("hospitals/create", "HospitalController@create")->name('hospitals.create');
    Route::get("hospitals/edit/{id}", "HospitalController@create")->name('hospitals.edit');
    Route::post("hospitals/save", "HospitalController@save")->name('hospitals.save');
    Route::post("hospitals/load-data", "HospitalController@load_data")->name('hospitals.load');
    Route::get("hospitals/show/{id}", "HospitalController@show")->name('hospitals.show');
    Route::delete("hospitals/delete/{id}", "HospitalController@destory")->name('hospitals.delete');
    Route::post("hospitals/change_status", "HospitalController@change_status");
    Route::post("hospitals/approve_status", "HospitalController@approve_status");
    Route::get("hospitals/departments/{id}", "HospitalController@departments")->name('hospitals.departments');
    Route::get("hospitals/createDepartment/{hospital_id}", "HospitalController@createDepartment")->name('hospitals.createDepartment');
    Route::post("hospitals/saveDepartment", "HospitalController@saveDepartment")->name('hospitals.saveDepartment');
    Route::get("hospitals/editDepartment/{hospital_id}/{id}", "HospitalController@createDepartment")->name('hospitals.editDepartment');
    Route::delete("hospitals/deleteDepartment/{id}", "HospitalController@deleteDepartment")->name('hospitals.deleteDepartment');

    Route::get("hospitals/insurances/{id}", "HospitalController@insurances")->name('hospitals.insurances');
    Route::get("hospitals/createInsurance/{hospital_id}", "HospitalController@createInsurance")->name('hospitals.createInsurance');
    Route::post("hospitals/saveInsurance", "HospitalController@saveInsurance")->name('hospitals.saveInsurance');
    Route::get("hospitals/editInsurance/{hospital_id}/{id}", "HospitalController@createInsurance")->name('hospitals.editInsurance');
    Route::delete("hospitals/deleteInsurance/{id}", "HospitalController@deleteInsurance")->name('hospitals.deleteInsurance');

    Route::get("hospitals/locations/{id}", "HospitalController@locations")->name('hospitals.locations');
    Route::get("hospitals/createLocation/{hospital_id}", "HospitalController@createLocation")->name('hospitals.createLocation');
    Route::post("hospitals/saveLocation", "HospitalController@saveLocation")->name('hospitals.saveLocation');
    Route::get("hospitals/editLocation/{hospital_id}/{id}", "HospitalController@createLocation")->name('hospitals.editLocation');
    Route::delete("hospitals/deleteLocation/{id}", "HospitalController@deleteLocation")->name('hospitals.deleteLocation');

    Route::get("hospitals/appointments/{id}", "HospitalController@appointments")->name('hospitals.appointments');
    Route::post("hospitals/appointment-loaddata", "HospitalController@appointmentLoadData")->name('hospitals.appointmentLoadData');
    Route::get("hospitals/create-appointment/{hospital_id}", "HospitalController@create_appointment")->name('hospitals.create_appointment');
    Route::get("hospitals/edit-appointment/{hospital_id}/{id}", "HospitalController@create_appointment")->name('hospitals.edit_appointment');
    Route::post("hospitals/saveAppointment", "HospitalController@saveAppointment")->name('hospitals.saveAppointment');
    Route::delete("hospitals/delete-appointment/{id}", "HospitalController@delete_appointment")->name('hospitals.delete_appointment');
    Route::get("hospitals/doctors/{id}", "HospitalController@doctors")->name('hospitals.doctors');

    // Clinics
    Route::get("clinics", "ClinicController@index")->name('clinics.index');
    Route::get("clinics/create", "ClinicController@create")->name('clinics.create');
    Route::get("clinics/edit/{id}", "ClinicController@create")->name('clinics.edit');
    Route::delete("clinics/delete/{id}", "ClinicController@destory")->name('clinics.delete');
    Route::post("clinics/save", "ClinicController@save")->name('clinics.save');
    Route::post("clinics/load-data", "ClinicController@load_data")->name('clinics.load');
    Route::get("clinics/show/{id}", "ClinicController@show")->name('clinics.show');

    Route::get("clinics/insurances/{id}", "ClinicController@insurances")->name('clinics.insurances');
    Route::get("clinics/createInsurance/{hospital_id}", "ClinicController@createInsurance")->name('clinics.createInsurance');
    Route::post("clinics/saveInsurance", "ClinicController@saveInsurance")->name('clinics.saveInsurance');
    Route::get("clinics/editInsurance/{hospital_id}/{id}", "ClinicController@createInsurance")->name('clinics.editInsurance');
    Route::delete("clinics/deleteInsurance/{id}", "ClinicController@deleteInsurance")->name('clinics.deleteInsurance');

    Route::get("clinics/locations/{id}", "ClinicController@locations")->name('clinics.locations');
    Route::get("clinics/createLocation/{hospital_id}", "ClinicController@createLocation")->name('clinics.createLocation');
    Route::post("clinics/saveLocation", "ClinicController@saveLocation")->name('clinics.saveLocation');
    Route::get("clinics/editLocation/{hospital_id}/{id}", "ClinicController@createLocation")->name('clinics.editLocation');
    Route::delete("clinics/deleteLocation/{id}", "ClinicController@deleteLocation")->name('clinics.deleteLocation');
    Route::post("clinics/change_status", "ClinicController@change_status");
    Route::get("clinics/appointments/{id}", "ClinicController@appointments")->name('clinics.appointments');
    Route::post("clinics/appointment-loaddata", "ClinicController@appointmentLoadData")->name('clinics.appointmentLoadData');
    Route::get("clinics/create-appointment/{hospital_id}", "ClinicController@create_appointment")->name('clinics.create_appointment');
    Route::get("clinics/edit-appointment/{hospital_id}/{id}", "ClinicController@create_appointment")->name('clinics.edit_appointment');
    Route::post("clinics/saveAppointment", "ClinicController@saveAppointment")->name('clinics.saveAppointment');
    Route::delete("clinics/delete-appointment/{id}", "ClinicController@delete_appointment")->name('clinics.delete_appointment');
    Route::get("clinics/doctors/{id}", "ClinicController@doctors")->name('clinics.doctors');
    Route::get('/clinics/export', 'ClinicController@export')->name('clinics.export');

    Route::get("appointments", "AppointmentsController@index")->name('appointments.index');
    Route::post("appointments/loaddata", "AppointmentsController@loadData")->name('appointments.loadData');
    Route::get("appointments/view/{id}", "AppointmentsController@booking_details")->name('appointments.view');
    Route::get('appointment_history/{id}', 'AppointmentsController@booking_history')->name('appointment_history');
    Route::get('appointments/urgent', 'AppointmentsController@urgent_appointments')->name('appointments.urgent');
    Route::post('appointments/load-urgent-data', 'AppointmentsController@loadUrgentData')->name('appointments.loadUrgentData');

    Route::group(['prefix' => 'earnings', 'as' => 'earnings.'], function() {
    Route::get('/', 'EarningsController@index')->name('index');
    Route::post('load-data', 'EarningsController@loadData')->name('loadData');
    Route::post('approve', 'EarningsController@approve')->name('approve');
    Route::post('mark-paid', 'EarningsController@markPaid')->name('markPaid');
    Route::post('reject', 'EarningsController@reject')->name('reject');
    Route::get('get-details/{id}', 'EarningsController@getDetails')->name('getDetails');
    Route::get('export', 'EarningsController@export')->name('export');
});

// Withdrawal Requests
Route::prefix('earnings')->group(function() {
    Route::get('withdrawals', 'EarningsController@withdrawals')->name('earnings.withdrawals');
    Route::post('load-withdrawals', 'EarningsController@loadWithdrawals')->name('earnings.loadWithdrawals');
    Route::post('approve-withdrawal', 'EarningsController@approveWithdrawal')->name('earnings.approveWithdrawal');
    Route::post('mark-withdrawal-paid', 'EarningsController@markWithdrawalPaid')->name('earnings.markWithdrawalPaid');
    Route::post('reject-withdrawal', 'EarningsController@rejectWithdrawal')->name('earnings.rejectWithdrawal');
    Route::get('export-withdrawals', 'EarningsController@exportWithdrawals')->name('earnings.exportWithdrawals');
    Route::get('get-withdrawal-details/{id}', 'EarningsController@getWithdrawalDetails')->name('earnings.getWithdrawalDetails');
});
    // routes/web.php

Route::get('activity-logs',"ActivityLogController@index")
    ->name('activity.logs');

    Route::get('/activity-logs/export', "ActivityLogController@exportLogs")
    ->name('activity.logs.export');
     Route::get("approval_appointments", "AppointmentsController@approval_index")->name('appointments.approval_index');
    Route::post("appointments/loadaprrovaldata", "AppointmentsController@loadApprovalData")->name('appointments.loadApprovalData');
    Route::post("appointments/change_status", "AppointmentsController@change_status");
    
    Route::post("/uploadAppointmentDocs", 'AppointmentsController@uploadAppointmentDocs')->name('uploadAppointmentDocs');
    Route::delete("/deleteDocs/{id}", 'AppointmentsController@deleteDocs')->name('docs.delete');
    Route::post("prescription/store", "AppointmentsController@Prescriptionstore")->name('prescription.store');
    Route::post('prescription/generate-pdf', "AppointmentsController@generatePdf")
    ->name('prescription.generate_pdf');
    
    Route::post('prescription/print-pdf', "AppointmentsController@printPdf")
    ->name('prescription.print-pdf');


    Route::get("appointments/create", "AppointmentsController@create")->name('appointments.create');
    Route::get("appointments/edit/{id}", "AppointmentsController@create")->name('appointments.edit');
    Route::post("appointments/save", "AppointmentsController@save")->name('appointments.save');
    Route::delete("appointments/delete/{id}", "AppointmentsController@delete")->name('appointments.delete');
    Route::post('/appointments/appointment_completed', "AppointmentsController@patientAppointmentCompleted")->name('appointments.appointmentCompleted');
    Route::post('/appointments/appointment_confirmed', "AppointmentsController@patientAppointmentConfirmed")->name('appointments.appointmentConfirmed');
    Route::post('/appointments/appointment_cancel', "AppointmentsController@patientAppointmentCancel")->name('appointments.appointmentCancel');
    Route::post('/appointments/appointment_rescheduled', "AppointmentsController@rescheduleAppointment")->name('appointments.appointmentRescheduled');
    Route::post('/appointments/saveAppointmentFollowup', "AppointmentsController@saveAppointmentFollowup")->name('appointments.saveAppointmentFollowup');
    Route::post("/appointments/check_doctor_availability", 'AppointmentsController@check_doctor_availability')->name('appointments.check_doctor_availability');

    Route::get('get-department-doctors/{department_id}/{hospital_id?}', 'DoctorController@getDepartmentDoctors')->name('admin.get-department-doctors');
    Route::get('get-hospital-doctors/{hospital_id}', 'DoctorController@getHospitalDoctors')->name('admin.get-hospital-doctors');
    //doctors
    Route::get("doctors", "DoctorController@index")->name('doctors.index');
    Route::get("doctors/create", "DoctorController@create")->name('doctors.create');
    Route::get("doctors/edit/{id}", "DoctorController@create")->name('doctors.edit');
    Route::delete("doctors/delete/{id}", "DoctorController@destory")->name('doctors.delete');
    Route::post("doctors/save", "DoctorController@save")->name('doctors.save');
    Route::get('/bulk_upload', 'DoctorController@import_export')->name('bulk_upload');
    Route::get('/doctors/export_excel', 'DoctorController@export_excel')->name('doctors.export_excel');
    Route::get('/doctors/export', 'DoctorController@export')->name('doctors.export');
    Route::post("/doctors/import_hospital", 'DoctorController@import')->name('doctors.import');
    Route::post('/doctors/upload-hospital-zip', 'DoctorController@uploadAndExtractZip')->name('doctors.upload-doctor-zip');

    Route::post("doctors/temporary-load", "DoctorController@temporary_load")->name('temporary.load');
    Route::post("doctors/load-data", "DoctorController@load_data")->name('doctors.load');
    Route::get("doctors/show/{id}", "DoctorController@show")->name('doctors.show');
    Route::get("doctors/appointments/{id?}", "DoctorController@appointments")->name('doctors.appointments');
    Route::get("doctors/availability/{id}", "DoctorController@availability")->name('doctors.availability');
    Route::post('/doctors/availability_save', "DoctorController@availability_save")->name('doctors.availability_save');
    Route::get("doctors/temporary-unavailable/{id}", "DoctorController@temporaryUnavailable")->name('doctors.temporaryUnavailable');
    Route::get("doctors/holiday/{id}", "DoctorController@holiday")->name('doctors.holiday');
    Route::post('/doctors/holiday_save', "DoctorController@holiday_save")->name('doctors.holiday_save');
    Route::delete('/doctors/holiday_delete/{id}', "DoctorController@holiday_delete")->name('doctors.holiday_delete');
    Route::post("doctors/change_status", "DoctorController@change_status");
    Route::post('/doctors/temporaryunavailable_save', "DoctorController@temporaryUnavailableSave")->name('doctors.temporaryUnavailableSave');
    Route::delete('/doctors/temporaryunavailable_delete/{id}', "DoctorController@temporaryUnavailableDelete")->name('doctors.temporaryUnavailableDelete');
    Route::get("doctors/instant-appointment/{id}", "DoctorController@instantAppointment")->name('doctors.instantAppointment');
    Route::post('/doctors/instantappointment_save', "DoctorController@instantAppointmentSave")->name('doctors.instantAppointmentSave');
    Route::delete('/doctors/instantappointment_delete/{id}', "DoctorController@instantAppointmentDelete")->name('doctors.instantAppointmentDelete');
    Route::post('/doctors/patient_appointment_save', "DoctorController@patienttAppointmentSave")->name('doctors.patienttAppointmentSave');
    Route::post("doctors/appointment-loaddata", "DoctorController@appointmentLoadData")->name('doctors.appointmentLoadData');
    Route::get("doctors/view-appointment/{id?}", "DoctorController@viewAppointment")->name('doctors.viewAppointment');
    Route::post('/doctors/patient_appointment_completed', "DoctorController@patientAppointmentCompleted")->name('doctors.patienttAppointmentCompleted');
    Route::post('/doctors/patient_appointment_confirmed', "DoctorController@patientAppointmentConfirmed")->name('doctors.patienttAppointmentConfirmed');
    Route::post('/doctors/patient_appointment_cancel', "DoctorController@patientAppointmentCancel")->name('doctors.patienttAppointmentCancel');
    Route::post('/doctors/patient_appointment_rescheduled', "DoctorController@patientAppointmentRescheduled")->name('doctors.patienttAppointmentRescheduled');
    Route::post("/check_doctor_unavailability", 'DoctorController@check_doctor_unavailability');

    // Agents instant-
    Route::get("agents", "AgentsController@index")->name('agents.index');
    Route::get("agents/create", "AgentsController@create")->name('agents.create');
    Route::get("agents/edit/{id}", "AgentsController@create")->name('agents.edit');
    Route::delete("agents/delete/{id}", "AgentsController@destory")->name('agents.delete');
    Route::post("agents/save", "AgentsController@save")->name('agents.save');
    Route::post("agents/load-data", "AgentsController@load_data")->name('agents.load');
    Route::get("agents/show/{id}", "AgentsController@show")->name('agents.show');
    Route::get("agents/appointments/{id}", "AgentsController@appointments")->name('agents.appointments');
    Route::get("agents/hospital/{id}", "AgentsController@hospital")->name('agents.hospital');
    Route::get("agents/doctors/{id}", "AgentsController@doctor")->name('agents.doctors');
    Route::post("agents/doctor_load_data", "AgentsController@doctor_load_data")->name('agents.doctor_load');
    Route::get("agents/doctor_create/{doctor_id?}", "AgentsController@doctorCreate")->name('agents.doctor_create');
    Route::post("agents_doctors/save", "AgentsController@doctorSave")->name('agents.docotor_save');
    Route::post("agents/hospital_load_data", "AgentsController@hospital_load_data")->name('agents.hospital_load');
    Route::get("agents/hospital_create/{agent_id?}", "AgentsController@hospitalCreate")->name('agents.hospital_create');
    Route::post("agents_hospital/save", "AgentsController@hospitalSave")->name('agents.hospital_save');
    Route::post("agents/patient_appointment_save", "AgentsController@patientAppointmentSave")->name('agents.patientAppointmentSave');
    Route::post("agents/appointment-loaddata", "AgentsController@appointmentLoadData")->name('agents.appointmentLoadData');
    Route::get("agents/view-appointment/{id?}", "AgentsController@viewAppointment")->name('agents.viewAppointment');
    Route::post("agents/change_status", "AgentsController@change_status");
    // Call Center
    Route::get("callcenter", "CallCenterController@index")->name('callcenter.index');
    Route::get("callcenter/create/{hospital_id?}", "CallCenterController@create")->name('callcenter.create');
    Route::post("callcenter/save", "CallCenterController@save")->name('callcenter.save');
    Route::post("callcenter/load-data", "CallCenterController@load_data")->name('callcenter.load');
    Route::get("callcenter/edit/{id}", "CallCenterController@create")->name('callcenter.edit');
    Route::delete("callcenter/delete/{id}", "CallCenterController@destory")->name('callcenter.delete');
    Route::get("callcenter/hospital/{id}", "CallCenterController@hospital")->name('callcenter.hospital');
    Route::get("callcenter/doctors/{id}", "CallCenterController@doctor")->name('callcenter.doctors');
    Route::get("callcenter/agent/{id}", "CallCenterController@agent")->name('callcenter.agent');
    Route::get("callcenter/appointments/{id}", "CallCenterController@appointments")->name('callcenter.appointments');
    Route::post("callcenter/patient_appointment_save", "CallCenterController@patientAppointmentSave")->name('callcenter.patientAppointmentSave');
    Route::post("callcenter/appointment-loaddata", "CallCenterController@appointmentLoadData")->name('callcenter.appointmentLoadData');
    Route::post("callcenter/hospital-load-data", "CallCenterController@hospital_load_data")->name('callcenter.hospital_load');
    Route::get("callcenter/hospital_create", "CallCenterController@hospital_create")->name('callcenter.hospitals_create');
    Route::post("callcenter/doctors_load_data", "CallCenterController@doctors_load_data")->name('callcenter.doctors_load');
    Route::post("callcenter/change_status", "CallCenterController@change_status");

    // ;


    Route::get("patients", "PatientsController@index")->name('patients.index');
    Route::get("patients/create", "PatientsController@create")->name('patients.create');
    Route::post("patients/points-history", "PatientsController@load_point_history_data")->name('points-history.load');
    Route::get("points/{id}", "PatientsController@points_index")->name('points_index.index');
    Route::get("patients/edit/{id}", "PatientsController@create")->name('patients.edit');
    Route::post("patients/save", "PatientsController@save")->name('patients.save');
    Route::post("patients/saveMember", "PatientsController@saveMember")->name('patients.saveMember');
    Route::post("patients/load-data", "PatientsController@load_data")->name('patients.load');
    Route::get("patients/show/{id}", "PatientsController@show")->name('patients.show');
    Route::delete('patients/delete/{id}', 'PatientsController@delete')->name('patients.delete');
    Route::post("patients/change_status", "PatientsController@change_status");
    Route::get("patients/members/{id}", "PatientsController@members")->name('patients.members');
    Route::get("patients/createMember/{patient_id}", "PatientsController@createMember")->name('patients.createMember');
    Route::get("patients/editMember/{patient_id}/{id}", "PatientsController@createMember")->name('patients.editMember');
    Route::delete('patients/deleteMember/{id}', 'PatientsController@deleteMember')->name('patients.deleteMember');
    Route::get("patients/appointments/{id}", "PatientsController@appointments")->name('patients.appointments');
    Route::post("patients/appointment-loaddata", "PatientsController@appointmentLoadData")->name('patients.appointmentLoadData');
    Route::get("patients/create-appointment/{patient_id}", "PatientsController@create_appointment")->name('patients.create_appointment');
    Route::get("patients/edit-appointment/{patient_id}/{id}", "PatientsController@create_appointment")->name('patients.edit_appointment');
    Route::post("patients/saveAppointment", "PatientsController@saveAppointment")->name('patients.saveAppointment');
    Route::delete("patients/delete-appointment/{id}", "PatientsController@delete_appointment")->name('patients.delete_appointment');
    Route::get('get-members/{id}', 'PatientsController@getMembers')->name('admin.get-members');
    Route::get('patients/export', 'PatientsController@export')->name('patients.export');


    Route::get('reports', 'App\Http\Controllers\admin\ReportsController@index')->name('reports.index');
    Route::get('reports/patients', 'App\Http\Controllers\admin\ReportsController@patients')->name('reports.patients');
    Route::get('reports/patients/export', 'App\Http\Controllers\admin\ReportsController@exportPatients')->name('reports.patients.export');
    Route::get('reports/appointments', 'App\Http\Controllers\admin\ReportsController@appointments')->name('reports.appointments');
    Route::get('reports/appointments/export', 'App\Http\Controllers\admin\ReportsController@exportAppointments')->name('reports.appointments.export');
    Route::get('reports/doctors', 'App\Http\Controllers\admin\ReportsController@doctors')->name('reports.doctors');
    Route::get('reports/doctors/export', 'App\Http\Controllers\admin\ReportsController@exportDoctors')->name('reports.doctors.export');
    Route::get('reports/hospitals', 'App\Http\Controllers\admin\ReportsController@hospitals')->name('reports.hospitals');
    Route::get('reports/hospitals/export', 'App\Http\Controllers\admin\ReportsController@exportHospitals')->name('reports.hospitals.export');
    Route::get('reports/financial', 'App\Http\Controllers\admin\ReportsController@financial')->name('reports.financial');
    Route::get('reports/financial/export', 'App\Http\Controllers\admin\ReportsController@exportFinancial')->name('reports.financial.export');
    // Add this inside the admin middleware group
    Route::get('get-doctor-reviews/{doctor_id}', 'App\Http\Controllers\admin\ReportsController@getDoctorReviews')->name('get.doctor.reviews');
    // Patient Members & Appointments AJAX routes (add these with your existing routes)
    Route::get('get-patient-members/{patient_id}', 'App\Http\Controllers\admin\ReportsController@getPatientMembers')->name('get.patient.members');
    Route::post('get-patient-appointments', 'App\Http\Controllers\admin\ReportsController@getPatientAppointments')->name('get.patient.appointments');
    

    //
    //  Route::resource("doctors", "DoctorController");
    
    Route::get('pharmacy', 'PagesController@pharmacy')->name('pharmacy');
    Route::get('drug_dosage', 'PagesController@drug_dosage')->name('drug_dosage');
    Route::get('drug_brand', 'PagesController@drug_brand')->name('drug_brand');
    Route::get('bulk-notifications', 'BulkNotificationController@index')->name('bulk_notifications.index');
    Route::get('bulk-notifications/create', 'BulkNotificationController@create')->name('bulk_notifications.create');
    Route::post('bulk-notifications/store', 'BulkNotificationController@store')->name('bulk_notifications.store');
    Route::post('bulk-notifications/get-users', 'BulkNotificationController@getUsersByType')->name('bulk_notifications.get_users');

});
Route::get("generate_push", "App\Http\Controllers\admin\ContactQueryController@generate_push");


Route::middleware('guest')->group(function () {

    // ------------ Vendor Main Routes -------------
    Route::get('/vendor', 'App\Http\Controllers\vendor\LoginController@login')->name('vendor.login');
    Route::get('/forgot-password', 'App\Http\Controllers\vendor\LoginController@forgotpassword')->name('vendor.forgot');

    // --------------- Doctor Main Routes------
    Route::get('/doctorlogin', 'App\Http\Controllers\doctor\LoginController@login')->name('doctorlogin.login');
  //  Route::get('/forgot-password', 'App\Http\Controllers\doctor\LoginController@forgotpassword')->name('doctorlogin.forgot');
    Route::get('/doctor_register', 'App\Http\Controllers\doctor\LoginController@Register')->name('resgister');
    Route::get('doctor/verify-email/{token}', 'App\Http\Controllers\doctor\LoginController@verifyEmail')->name('doctor.verify.email');
});


Route::post('vendor/check_user', 'App\Http\Controllers\vendor\LoginController@check_user')->name('vendor.check_user');
Route::post('doctorlogin/check_user', 'App\Http\Controllers\doctor\LoginController@check_user')->name('doctorlogin.check_user');
Route::post('/save_doctor', 'App\Http\Controllers\doctor\LoginController@save_doctor')->name('save_hospital');
Route::post('/save_doctor', 'App\Http\Controllers\doctor\LoginController@save_doctor')->name('save_hospital');
Route::post('doctorlogin/check_login', 'App\Http\Controllers\doctor\LoginController@check_login')->name('doctorlogin.check_login');
Route::post('doctorlogin/verify-login-otp','App\Http\Controllers\doctor\LoginController@verifyLoginOtp')->name('verify.login.otp');
Route::post('vendor/check_login', 'App\Http\Controllers\vendor\LoginController@check_login')->name('vendor.check_login');

Route::namespace('App\Http\Controllers\vendor')->prefix('vendor')->middleware('vendor')->name('vendor.')->group(function () {

    Route::get('logout', 'LoginController@logout')->name('logout');

    // If vendor is verified
    Route::middleware('is_vendor_verified')->group(function () {

        Route::match(array('GET', 'POST'), 'change_password', 'UsersController@change_password');
        Route::get('dashboard', 'DashboardController@dashboard')->name('dashboard');
    });
});

Route::namespace('App\Http\Controllers\doctor')->prefix('doctor')->middleware('doctor')->name('doctor.')->group(function () {
  Route::get('/chat', [App\Http\Controllers\doctor\ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/init', [App\Http\Controllers\doctor\ChatController::class, 'init'])->name('chat.init');
    Route::get('/chat/conversations', [App\Http\Controllers\doctor\ChatController::class, 'getConversations'])->name('chat.conversations');
    Route::get('/chat/messages/{uid}', [App\Http\Controllers\doctor\ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/send', [App\Http\Controllers\doctor\ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/mark-read', [App\Http\Controllers\doctor\ChatController::class, 'markAsRead'])->name('chat.mark-read');
    Route::post('/chat/unread-count', [App\Http\Controllers\doctor\ChatController::class, 'getUnreadCount'])->name('chat.unread-count');
    Route::post('/chat/check', [App\Http\Controllers\doctor\ChatController::class, 'checkNewMessages'])->name('chat.check');
    Route::post('/chat/notify', [App\Http\Controllers\doctor\ChatController::class, 'notifyMessage'])->name('chat.notify');



    Route::get("patients", "PatientsController@index")->name('patients.index');
    Route::get("patients/create", "PatientsController@create")->name('patients.create');
    Route::get("patients/edit/{id}", "PatientsController@create")->name('patients.edit');
    Route::post("patients/save", "PatientsController@save")->name('patients.save');
    Route::post("patients/saveMember", "PatientsController@saveMember")->name('patients.saveMember');
    Route::post("patients/load-data", "PatientsController@load_data")->name('patients.load');
    Route::get("patients/show/{id}", "PatientsController@show")->name('patients.show');
    Route::delete('patients/delete/{id}', 'PatientsController@delete')->name('patients.delete');
    Route::post("patients/change_status", "PatientsController@change_status");
    Route::get("patients/members/{id}", "PatientsController@members")->name('patients.members');
    Route::get("patients/createMember/{patient_id}", "PatientsController@createMember")->name('patients.createMember');
    Route::get("patients/editMember/{patient_id}/{id}", "PatientsController@createMember")->name('patients.editMember');
    Route::delete('patients/deleteMember/{id}', 'PatientsController@deleteMember')->name('patients.deleteMember');
    Route::get("patients/appointments/{id}", "PatientsController@appointments")->name('patients.appointments');
    Route::post("patients/appointment-loaddata", "PatientsController@appointmentLoadData")->name('patients.appointmentLoadData');
    Route::get("patients/create-appointment/{patient_id}", "PatientsController@create_appointment")->name('patients.create_appointment');
    Route::get("patients/edit-appointment/{patient_id}/{id}", "PatientsController@create_appointment")->name('patients.edit_appointment');
    Route::post("patients/saveAppointment", "PatientsController@saveAppointment")->name('patients.saveAppointment');
    Route::delete("patients/delete-appointment/{id}", "PatientsController@delete_appointment")->name('patients.delete_appointment');
    Route::get('get-members/{id}', 'PatientsController@getMembers')->name('admin.get-members');
    Route::get('patients/export', 'PatientsController@export')->name('patients.export');
    
    
    Route::get('/appointments/export', 'DoctorAppointmenstsController@export')->name('appointments.export');

    Route::get('logout', 'LoginController@logout')->name('logout');
    Route::match(array('GET', 'POST'), 'change_password', 'UsersController@change_password');
    Route::match(array('GET', 'POST'), 'availability', 'UsersController@availability');
    Route::match(array('GET', 'POST'), 'holiday', 'UsersController@holiday');
    // Route::match(array('GET', 'POST'), 'instantAppointment', 'UsersController@instantAppointment');
    Route::post("availability_save", "UsersController@availability_save")->name('availability_save');
    Route::post("holiday_save", "UsersController@holiday_save")->name('holiday_save');
    Route::get('edit_profile', 'UsersController@edit_profile')->name('edit_profile');
    Route::get('get_profile', 'UsersController@get_profile')->name('get_profile');
    Route::post("save_profile", "UsersController@save")->name('save_profile');
    Route::post("delete-account", "UsersController@deleteAccount")->name('delete.account');
    Route::get('get-hospital-departments/{hospital_id}', 'UsersController@getHospitalDepartments')->name('get-hospital-department');
    Route::post('save_profile_image', 'UsersController@update_profile_image')->name('save_profile_image');
    Route::get('wait_for_verification', 'UsersController@wait_for_verification')->name('wait_for_verification');
    Route::get('dashboard', 'DashboardController@dashboard')->name('dashboard');
    Route::get('notifications', 'DashboardController@notifications')->name('notifications');
    Route::get('reports', 'DoctorAppointmenstsController@reports')->name('reports');
    Route::post("/uploadAppointmentDocs", 'DoctorAppointmenstsController@uploadAppointmentDocs')->name('uploadAppointmentDocs');
    Route::delete("/deleteDocs/{id}", 'DoctorAppointmenstsController@deleteDocs')->name('docs.delete');
    Route::get('totalappointments', 'DoctorAppointmenstsController@index')->name('totalappointments');
    Route::get('appointmentdetail/{id}', 'DoctorAppointmenstsController@booking_details')->name('appointmentdetail');
    Route::post('/appointment_cancel', 'DoctorController@appointmentCancel')->name('appointment_cancel');
    Route::post('/request-access', 'DoctorAppointmenstsController@requestAccess')->name('request_access');
    Route::post('/appointment_confirm', 'DoctorController@appointmentConfirmed')->name('appointment_confirm');
    Route::post('/apointment_completed', 'DoctorController@appointmentCompleted')->name('apointment_completed');
    Route::post('/saveAppointmentFollowup', 'DoctorController@saveAppointmentFollowup')->name('saveAppointmentFollowup');
    Route::post("/hospitalRescheduleAppointment", 'DoctorAppointmenstsController@rescheduleAppointment')->name('rescheduleAppointment');
    Route::post("/saveAppointment", 'DoctorAppointmenstsController@saveAppointment')->name('saveAppointment');


    Route::get('earnings', 'EarningsController@index')->name('earnings.index');
    Route::post('earnings/load-data', 'EarningsController@loadData')->name('earnings.loadData');
    Route::post('withdrawal/request', 'EarningsController@requestWithdrawal')->name('withdrawal.request');
    Route::get('withdrawal/history', 'EarningsController@withdrawalHistory')->name('withdrawal.history');
    Route::post('withdrawal/cancel', 'EarningsController@cancelWithdrawal')->name('withdrawal.cancel');
    Route::get('earnings/summary', 'EarningsController@getSummary')->name('earnings.summary');
    Route::post("prescription/store", "DoctorAppointmenstsController@Prescriptionstore")->name('prescription.store');
    Route::get('prescription/generate-pdf', "DoctorAppointmenstsController@generatePdf")
    ->name('prescription.generate_pdf');
    Route::get('prescription/print-pdf', "DoctorAppointmenstsController@printPdf")
    ->name('prescription.print-pdf');
    Route::post(
        'clinical-summary/store',
        'DoctorAppointmenstsController@Summarystore'
    )->name('summary.store');
    
    
    Route::post(
        'clinical_assessment_and_documentation/store',
        'DoctorAppointmenstsController@clinicalAssessmentStore'
    )->name('clinical_assessment_and_documentation.store');

    Route::post(
        'referral/store',
        'DoctorAppointmenstsController@storeReferral'
    )->name('referral.store');
    
    Route::get(
        'by/department',
        'DoctorAppointmenstsController@doctorsByDepartment'
    )->name('by.department');

    Route::get('/departments-by-referral', 'DoctorAppointmenstsController@departmentsByReferral')
    ->name('departments.by.referral');

    Route::get('appointment_history/{id}', 'DoctorAppointmenstsController@booking_history')->name('appointment_history');
    
    Route::post("/check_doctor_availability", 'DoctorController@check_doctor_availability');
    Route::get('/availability', 'DoctorController@availability')->name('availability');
    Route::post('/availability_save', 'DoctorController@availability_save')->name('availability_save');
    Route::get('/temporaryunavailable', 'DoctorController@temporaryUnavailable')->name('temporaryunavailable');
    Route::post('/temporaryUnavailableSave', 'DoctorController@temporaryUnavailableSave')->name('temporaryUnavailableSave');
    Route::post("/check_doctor_unavailability", 'DoctorController@check_doctor_unavailability');
    Route::get('/holiday', 'DoctorController@holiday')->name('holiday');
    Route::post('/holiday_save', 'DoctorController@holiday_save')->name('holiday_save');
    Route::delete('/holiday_delete/{id}', "DoctorController@holiday_delete")->name('holiday_delete');
    Route::get('/instantAppointment', 'DoctorController@instantAppointment')->name('instantappointment');
    Route::post('/instantappointment_save', 'DoctorController@instantAppointmentSave')->name('instantappointment_save');
    Route::delete('/instantappointment_delete/{id}', "DoctorController@instantAppointmentDelete")->name('instantAppointmentDelete');
    // If vendor is verified
    Route::middleware('is_doctor_verified')->group(function () {
    });
});

// ----------------------------------


Route::namespace('App\Http\Controllers')->name('guest.')->group(function () {
    Route::get('/', 'HomeController@index')->name('home_page');
    Route::get('register', 'HomeController@register')->name('register_page');
    Route::post('signup', 'HomeController@signup')->name('signup');
    Route::post('login', 'HomeController@login')->name('login');
});

Route::namespace('App\Http\Controllers')->prefix('customer')->name('customer.')->group(function () {
    Route::get('dashbaord', 'CoustomerController@index')->name('dashbaord');
});


Route::prefix('hospital')->name('hospital.')->group(function () {
    require_once base_path('routes/hospital.php');
});
Route::prefix('clinic')->name('clinic.')->group(function () {
    require_once base_path('routes/clinic.php');
});

Route::prefix('callcenter')->name('callcenter.')->group(function () {
    require_once base_path('routes/callcenter.php');
});

Route::prefix('agent')->name('agent.')->group(function () {
    require_once base_path('routes/agent.php');
});

