<?php
namespace App\Http\Controllers\api\v1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post("test",function(){
    echo "here";
});

// Route::prefix('v1')->name('v1')->group(function () {
//     Route::post("get_insurencey_policy",[UsersController::class,'get_insurencey_policy']);
//     Route::post("get_sub_insurencey_policy",[UsersController::class,'get_sub_insurencey_policy']);
//     Route::post("signup",[UsersController::class,'signup']);
//     Route::post("resend_signup_otp",[UsersController::class,'resend_signup_otp']);
//     Route::post("verify_signup_otp",[UsersController::class,'verify_signup_otp']);
//     Route::post("sign_in_with_phone",[UsersController::class,'sign_in_with_phone']);
//     // Route::post("verify_signup_otp_web",[UsersController::class,'verify_signup_otp_web']); //for web
//     // Route::post("sign_in_with_phone_web",[UsersController::class,'sign_in_with_phone_web']); //for web
//     // Route::post("resend_phone_otp",[UsersController::class,'resend_phone_otp']); //for web
//     // Route::post("verify_sign_in_with_phone_otp_web",[UsersController::class,'verify_sign_in_with_phone_otp_web']); //for web
//     Route::post("verify_sign_in_with_phone_otp",[UsersController::class,'verify_sign_in_with_phone_otp']);
//     Route::post("add_members",[UsersController::class,'add_members']);
//     Route::post("get_my_members",[UsersController::class,'get_my_members']);
//     Route::post("delete_member",[UsersController::class,'delete_member']);
//     Route::post("get_member_details",[UsersController::class,'get_member_details']);
//     Route::post("update_member",[UsersController::class,'update_member']);



//     Route::post("get_doctor_lists",[DoctorsController::class,'get_doctor_lists']);
//     Route::post("get_doctor_profiles",[DoctorsController::class,'get_doctor_profiles']);
//     Route::post("book_appointment",[DoctorsController::class,'book_appointment']);
//     Route::post("check_doctor_availability",[DoctorsController::class,'check_doctor_availability']);
//     Route::post("get_booking_lists",[DoctorsController::class,'get_booking_lists']);
//     Route::post("booking_details_lists",[DoctorsController::class,'booking_details_lists']);
//     Route::post("cancel_appointment",[DoctorsController::class,'cancel_appointment']);
//     Route::post("reschedule_appointment",[DoctorsController::class,'reschedule_appointment']);
//     Route::post("booking_count_list",[DoctorsController::class,'booking_count_list']);
//     Route::post("hospital_doctor_feedback",[DoctorsController::class,'hospital_doctor_feedback']);
//     Route::post("mydrworld_service_feedback",[DoctorsController::class,'mydrworld_service_feedback']);
//     Route::post("get_doctor_specialty",[UsersController::class,'get_doctor_specialty']);
//     Route::post("get_medical_condition",[UsersController::class,'get_medical_condition']);
//     Route::post("get_doctor_language",[UsersController::class,'get_doctor_language']);
//     Route::post("get_doctor_gender",[UsersController::class,'get_doctor_gender']);
//     Route::post("get_doctor_country",[UsersController::class,'get_doctor_country']);
//     Route::post("get_doctor_emirates",[UsersController::class,'get_doctor_emirates']);  
//     Route::post("get_doctor_area",[UsersController::class,'get_doctor_area']);
//     Route::post("get_doctor_name",[UsersController::class,'get_doctor_name']);
//     Route::post("get_hospital_name",[UsersController::class,'get_hospital_name']);
//     Route::post("get_my_members_booking_counts",[DoctorsController::class,'get_my_members_booking_counts']);

//     Route::post("get_filter_data",[DoctorsController::class,'get_filter_data']);
//     Route::post("get_country_list",[DoctorsController::class,'get_country_list']);
//     Route::post("get_speciality_list",[DoctorsController::class,'get_speciality_list']);
//     Route::post("get_medical_condition_list",[DoctorsController::class,'get_medical_condition_list']);
//     Route::post("get_language_list",[DoctorsController::class,'get_language_list']);
//     Route::post("get_emirates_list",[DoctorsController::class,'get_emirates_list']);
//     Route::post("get_area_list",[DoctorsController::class,'get_area_list']);
//     Route::post("get_hospital_list",[DoctorsController::class,'get_hospital_list']);
//     Route::post("get_doctors",[DoctorsController::class,'get_doctors']);
//     Route::post("get_doctors_v2",[DoctorsController::class,'get_doctors_v2']);
//     Route::post("get_country_of_origin_list",[DoctorsController::class,'get_country_of_origin_list']);
//     Route::post("get_hospital_profile",[DoctorsController::class,'get_hospital_profile']);

//     Route::post('/get_page', [CMS::class,'get_page']);
//     Route::post('/get_faq', [CMS::class,'get_faq']);
//     Route::post('/submit_contact_us', [CMS::class,'submit_contact_us']);
//     Route::post('/contact_settings', [CMS::class,'contact_settings']);

//     Route::post('/my_profile', [MyProfileController::class,'my_profile'])->name('my_profile');
//     Route::post('/update_user_profile', [MyProfileController::class,'update_user_profile']);
//     Route::post('/verify_update_user_profile_otp', [MyProfileController::class,'verify_update_user_profile_otp']);
//     Route::post('update_face_login_id', [MyProfileController::class,'updateFaceLoginId']);
// });


// Route::namespace('App\Http\Controllers\Api\v1')->prefix("v1/auth")->name("api.v2.auth")->group(function () {
//     Route::post('resend_code', [AuthController::class,'resend_code'])->name('resend_code');
//     Route::post('confirm_code',[AuthController::class,'confirm_code'])->name('confirm_code');
//     Route::post('email_login', [AuthController::class,'email_login'])->name('email_login');
//     // Route::post('email_login_web', [AuthController::class,'email_login_web'])->name('email_login_web'); //for web
//     Route::post('mobile_login', [AuthController::class,'mobile_login'])->name('mobile_login');
//     Route::post('social_login',[AuthController::class,'social_login'])->name('social_login');
//     Route::post('verify_signup_otp_social_login', [AuthController::class,'verify_signup_otp_social_login']);
    
//     Route::post('resend_phone_code', [AuthController::class,'resend_phone_code'])->name('resend_phone_code');
//     Route::post('confirm_email_code', [AuthController::class,'confirm_email_code'])->name('confirm_phone_code');
//     Route::post('confirm_email_code_web', [AuthController::class,'confirm_email_code_web'])->name('confirm_phone_code_web');
//     Route::post('user_id_login', [AuthController::class,'user_id_login'])->name('user_id_login');
//     Route::post('delete_user', [AuthController::class,'delete_account'])->name('delete_user');
//     Route::post('get_user_by_token', [AuthController::class,'get_user_by_token'])->name('get_user_by_token');
//     Route::post('/forgot_password', [AuthController::class,'forgot_password']);
//     Route::post('/reset_password_otp_verify', [AuthController::class,'reset_password_otp_verify'])->name('user.reset_password_otp_verify');
//     Route::post('/reset_password', [AuthController::class,'reset_password'])->name('user.reset_password');
//     Route::post('/resend_forgot_password_otp',[AuthController::class,'resend_forgot_password_otp'])->name('user.resend_forgot_password_otp');
//     Route::post('logout', [AuthController::class,'logout'])->name('logout');
  
  
//     Route::post('get_mobile_otp', 'ChangeMobileController@get_mobile_otp')->name('get_mobile_otp');
//     Route::post('resend_mobile_otp',  'ChangeMobileController@resend_mobile_otp')->name('resend_mobile_otp');
//     Route::post('change_mobile', 'ChangeMobileController@change_mobile')->name('change_mobile');
  
//   });

