<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\hospital\LoginController;
use App\Http\Controllers\hospital\HospitalDashboardController;
use App\Http\Controllers\hospital\DoctorController;
use App\Http\Controllers\hospital\DepartmentController;
use App\Http\Controllers\hospital\UsersController;
use App\Http\Controllers\hospital\PatientsController;
use App\Http\Controllers\hospital\HospitalInsuranceController;
use App\Http\Controllers\hospital\HospitalAppointmenstsController;
use App\Http\Controllers\hospital\ChatController;
use Illuminate\Support\Facades\Route;



Route::middleware(['guest'])->group(function() {
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/register', [LoginController::class, 'Register'])->name('resgister');
    Route::post('/save_hospital', [LoginController::class, 'save_hospital'])->name('save_hospital');
    Route::get('/verify-email/{token}', [LoginController::class, 'verifyEmail'])->name('verify.email');
    Route::post('/check_login', [LoginController::class, 'check_login'])->name('check_login');
    Route::post('hospitallogin/verify-login-otp',[LoginController::class, 'verifyLoginOtp'])->name('verify.login.otp');
    Route::get('get-emirates/{countryId}', [LoginController::class, 'getEmirates'])->name('admin.get-emirates');
    Route::get('get-areas/{emirateId}', [LoginController::class, 'getAreas'])->name('admin.get-areas');
    Route::get('/terms-conditions', [LoginController::class, 'terms_conditions'])->name('terms_conditions');
});
Route::middleware(['hospital'])->group(function() {


    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/init', [ChatController::class, 'init'])->name('chat.init');
    Route::get('/chat/conversations', [ChatController::class, 'getConversations'])->name('chat.conversations');
    Route::get('/chat/messages/{uid}', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/mark-read', [ChatController::class, 'markAsRead'])->name('chat.mark-read');
    Route::post('/chat/unread-count', [ChatController::class, 'getUnreadCount'])->name('chat.unread-count');
    Route::post('/chat/check', [ChatController::class, 'checkNewMessages'])->name('chat.check');
    Route::post('/chat/notify', [ChatController::class, 'notifyMessage'])->name('chat.notify');

    Route::get("patients",[PatientsController::class, 'index'] )->name('patients.index');
    Route::get("patients/create", [PatientsController::class, 'create'] )->name('patients.create');
    Route::get("patients/edit/{id}", [PatientsController::class, 'create'])->name('patients.edit');
    Route::post("patients/save", [PatientsController::class, 'save'])->name('patients.save');
    Route::post("patients/saveMember", [PatientsController::class, 'saveMember'])->name('patients.saveMember');
    Route::post("patients/load-data", [PatientsController::class, 'load_data'])->name('patients.load');
    Route::get("patients/show/{id}", [PatientsController::class, 'show'])->name('patients.show');
    Route::delete('patients/delete/{id}', [PatientsController::class, 'delete'])->name('patients.delete');
    Route::post("patients/change_status", [PatientsController::class, 'change_status']);
    Route::get("patients/members/{id}", [PatientsController::class, 'members'])->name('patients.members');
    Route::get("patients/createMember/{patient_id}", [PatientsController::class, 'createMember'])->name('patients.createMember');
    Route::get("patients/editMember/{patient_id}/{id}", [PatientsController::class, 'createMember'])->name('patients.editMember');
    Route::delete('patients/deleteMember/{id}', [PatientsController::class, 'deleteMember'])->name('patients.deleteMember');
    Route::get("patients/appointments/{id}", [PatientsController::class, 'appointments'])->name('patients.appointments');
    Route::post("patients/appointment-loaddata", [PatientsController::class, 'appointmentLoadData'])->name('patients.appointmentLoadData');
    Route::get("patients/create-appointment/{patient_id}", [PatientsController::class, 'create_appointment'])->name('patients.create_appointment');
    Route::get("patients/edit-appointment/{patient_id}/{id}", [PatientsController::class, 'create_appointment'])->name('patients.edit_appointment');
    Route::post("patients/saveAppointment", [PatientsController::class, 'saveAppointment'])->name('patients.saveAppointment');
    Route::delete("patients/delete-appointment/{id}", [PatientsController::class, 'delete_appointment'])->name('patients.delete_appointment');
    Route::get('get-members/{id}', [PatientsController::class, 'getMembers'])->name('admin.get-members');
    Route::get('patients/export', [PatientsController::class, 'export'])->name('patients.export');
    // export
    Route::post("prescription/store", [HospitalAppointmenstsController::class, 'Prescriptionstore'])->name('prescription.store');
    
    Route::get('/appointments/export', [HospitalAppointmenstsController::class, 'export'])->name('appointments.export');
    // Route::post('prescription/generate-pdf', [HospitalAppointmenstsController::class, 'generatePdf'])
    // ->name('prescription.generate_pdf');
    
    // Route::post('prescription/print-pdf', [HospitalAppointmenstsController::class, 'printPdf'])
    // ->name('prescription.print-pdf');

    Route::get('prescription/generate-pdf', [HospitalAppointmenstsController::class, 'generatePdf'])->name('prescription.generate_pdf');
    Route::get('prescription/print-pdf', [HospitalAppointmenstsController::class, 'printPdf'])->name('prescription.print-pdf');

    Route::get('/edit_profile', [UsersController::class, 'edit_profile'])->name('edit_profile');
    Route::get('/get_profile', [UsersController::class, 'get_profile'])->name('get_profile');
    Route::post('/save_profile', [UsersController::class, 'save'])->name('save_profile');
    Route::post('/save_profile_image', [UsersController::class, 'update_profile_image'])->name('save_profile_image');
    Route::post('delete-account', [UsersController::class, 'deleteAccount'])->name('delete.account');
    // Route::post('/remove_hospital_image', [UsersController::class, 'delete_profile_image'])->name('remove_profile_image');
    Route::get('/ourinsurance', [HospitalInsuranceController::class, 'index'])->name('ourinsurance');

    Route::get("reviews",  [UsersController::class, 'HospitalDoctorsReviews'])->name('reviews');

    Route::get('/addinsurance', [HospitalInsuranceController::class, 'create'])->name('addinsurance');
    Route::post('/saveinsurance', [HospitalInsuranceController::class, 'store'])->name('saveinsurance');
    Route::get('/editinsurance/{id}', [HospitalInsuranceController::class, 'edit'])->name('editinsurance');
    Route::get('/deleteinsurance/{id}', [HospitalInsuranceController::class, 'destroy'])->name('deleteinsurance');
    
    Route::match(['GET', 'POST'], '/change_password', [UsersController::class, 'change_password'])->name('change_password');
    
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments');
    Route::get('/createDepartment', [DepartmentController::class, 'create'])->name('createDepartment');
    Route::post('/saveDepartment', [DepartmentController::class, 'store'])->name('saveDepartment');
    Route::get('/editDepartment/{id}', [DepartmentController::class, 'create'])->name('editDepartment');
    Route::get('/deleteDepartment/{id}', [DepartmentController::class, 'destroy'])->name('deleteDepartment');
    Route::get('/reports', [HospitalAppointmenstsController::class, 'reports'])->name('reports');
    Route::get('/totalappointments', [HospitalAppointmenstsController::class, 'index'])->name('totalappointments');
    Route::get('/appointmentdetail/{id}', [HospitalAppointmenstsController::class, 'booking_details'])->name('appointmentdetail');
    Route::get('/appointment_history/{id}', [HospitalAppointmenstsController::class, 'booking_history'])->name('appointment_history');
    Route::get('/notifications', [HospitalDashboardController::class, 'notifications'])->name('notifications');
    Route::get('/dashboard', [HospitalDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors');
    Route::get('/load-data-doctors', [DoctorController::class, 'load_data'])->name('load-data-doctors');
    Route::get('/doctordetail/{id}', [DoctorController::class, 'detail'])->name('doctordetail');
    Route::get('/createDoctor', [DoctorController::class, 'create'])->name('createDoctor');
    Route::get('/editDoctor/{id}', [DoctorController::class, 'create'])->name('editDoctor');
    Route::post('/saveDoctor', [DoctorController::class, 'save'])->name('saveDoctor');
    Route::get('/deleteDoctor/{id}', [DoctorController::class, 'destory'])->name('deleteDoctor');
    Route::post('/patient_appointment_cancel', [DoctorController::class, 'patientAppointmentCancel'])->name('patient_appointment_cancel');
    Route::post('/patient_appointment_confirm', [DoctorController::class, 'patientAppointmentConfirmed'])->name('patient_appointment_confirm');
    Route::post('/patient_appointment_completed', [DoctorController::class, 'patientAppointmentCompleted'])->name('patient_appointment_completed');
    Route::post('/saveAppointmentFollowup', [DoctorController::class, 'saveAppointmentFollowup'])->name('saveAppointmentFollowup');
    Route::get('get-members/{id}', [PatientsController::class, 'getMembers'])->name('get-members');
    Route::post("/uploadAppointmentDocs", [HospitalAppointmenstsController::class, 'uploadAppointmentDocs'])->name('uploadAppointmentDocs');
    Route::delete("/deleteDocs/{id}", [HospitalAppointmenstsController::class, 'deleteDocs'])->name('docs.delete');
    
    Route::get('get-hospital-departments/{hospital_id}', [DepartmentController::class, 'getHospitalDepartments'])->name('get-hospital-department');
    Route::get('get-hospital-doctors/{hospital_id}', [DoctorController::class, 'getHospitalDoctors'])->name('get-hospital-doctors');
    Route::get('get-department-doctors/{department_id}', [DoctorController::class, 'getDepartmentDoctors'])->name('get-department-doctors');

    // Route::get('/editdoctor/{id}', [DoctorController::class, 'create'])->name('editdoctor');
    Route::get('/appointments/{id}', [DoctorController::class, 'appointments'])->name('appointments');
    // Route::get('/dr-appointments/{id}', [DoctorController::class, 'appointmentsDR'])->name('dr-appointments');
    Route::get('/hospitalAppointments', [HospitalAppointmenstsController::class, 'index'])->name('hospitalAppointments');
    Route::post("/hospitalAppointmentsLoaddata", [HospitalAppointmenstsController::class, 'appointmentLoadData'])->name('hospitalAppointmentLoadData');
    
     Route::get("/approval_appointments",[HospitalAppointmenstsController::class, 'approval_index'])->name('appointments.approval_index');
    Route::post("/appointments/loadaprrovaldata", [HospitalAppointmenstsController::class, 'loadApprovalData'] )->name('appointments.loadApprovalData');
    Route::post("appointments/change_status",  [HospitalAppointmenstsController::class, 'change_status']  )->name('approval_appointments.change_status');

    Route::get("/create_appointment", [HospitalAppointmenstsController::class, 'create_appointment'])->name('create_appointment');
    Route::get("/create_dr_appointment/{doctor_id}", [HospitalAppointmenstsController::class, 'create_dr_appointment'])->name('create_dr_appointment');
    Route::get("/hospitalEditAppointment/{id}", [HospitalAppointmenstsController::class, 'create_appointment'])->name('edit_appointment');
    Route::post("/hospitalSaveAppointment", [HospitalAppointmenstsController::class, 'saveAppointment'])->name('saveAppointment');
    Route::post("/doctorSaveAppointment", [HospitalAppointmenstsController::class, 'saveDrAppointment'])->name('saveDrAppointment');
    Route::post("/hospitalRescheduleAppointment", [HospitalAppointmenstsController::class, 'rescheduleAppointment'])->name('rescheduleAppointment');
    Route::delete("/hospitalDeleteAppointment/{id}", [HospitalAppointmenstsController::class, 'delete_appointment'])->name('delete_appointment');
    Route::post("/check_doctor_availability",[DoctorController::class,'check_doctor_availability']);
    Route::post("/check_doctor_unavailability",[DoctorController::class,'check_doctor_unavailability']);

    Route::get('/availability/{id}', [DoctorController::class, 'availability'])->name('availability');
    Route::post('/availability_save', [DoctorController::class, 'availability_save'])->name('availability_save');
    Route::get('/temporaryunavailable/{id}', [DoctorController::class, 'temporaryUnavailable'])->name('temporaryunavailable');
    Route::post('/temporaryUnavailableSave', [DoctorController::class, 'temporaryUnavailableSave'])->name('temporaryUnavailableSave');
    Route::get('/holiday/{id}', [DoctorController::class, 'holiday'])->name('holiday');
    Route::post('/holiday_save', [DoctorController::class, 'holiday_save'])->name('holiday_save');
    Route::delete('/holiday_delete/{id}', [DoctorController::class, 'holiday_delete'])->name('holiday_delete');
    Route::get('/instantappointment/{id}', [DoctorController::class, 'instantAppointment'])->name('instantappointment');
    Route::post('/instantappointment_save', [DoctorController::class, 'instantAppointmentSave'])->name('instantappointment_save');
    Route::delete('/instantappointment_delete/{id}', [DoctorController::class, 'instantAppointmentDelete'])->name('instantAppointmentDelete');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    
});
