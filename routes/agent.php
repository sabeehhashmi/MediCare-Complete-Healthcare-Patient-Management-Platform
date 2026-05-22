<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\agent\AgentsController;
use App\Http\Controllers\agent\HospitalController;
use App\Http\Controllers\agent\LoginController;
use App\Http\Controllers\agent\HospitalDashboardController;
use App\Http\Controllers\agent\DoctorController;
use App\Http\Controllers\agent\DepartmentController;
use App\Http\Controllers\agent\ClinicController;
use App\Http\Controllers\agent\UsersController;
use App\Http\Controllers\agent\PatientsController;
use App\Http\Controllers\agent\HospitalInsuranceController;
use App\Http\Controllers\agent\HospitalAppointmenstsController;
use App\Http\Controllers\agent\InsurencePolicyController;
use App\Http\Controllers\agent\ChatController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function() {
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/register', [LoginController::class, 'Register'])->name('resgister');
    Route::post('/save_hospital', [LoginController::class, 'save_hospital'])->name('save_hospital');
    Route::post('/check_login', [LoginController::class, 'check_login'])->name('check_login');
    Route::post('agentlogin/verify-login-otp',[LoginController::class, 'verifyLoginOtp'])->name('verify.login.otp');
    Route::get('/terms-conditions', [LoginController::class, 'terms_conditions'])->name('terms_conditions');
});
Route::middleware(['agent'])->group(function() {

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/init', [ChatController::class, 'init'])->name('chat.init');
    Route::get('/chat/conversations', [ChatController::class, 'getConversations'])->name('chat.conversations');
    Route::get('/chat/messages/{uid}', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/mark-read', [ChatController::class, 'markAsRead'])->name('chat.mark-read');
    Route::post('/chat/unread-count', [ChatController::class, 'getUnreadCount'])->name('chat.unread-count');
    Route::post('/chat/check', [ChatController::class, 'checkNewMessages'])->name('chat.check');
    Route::post('/chat/notify', [ChatController::class, 'notifyMessage'])->name('chat.notify');

    Route::get('/get-sub-insurance/{id}', [InsurencePolicyController::class, 'getSubInsurence']);

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
    Route::get('/appointments/export', [HospitalAppointmenstsController::class, 'export'])->name('appointments.export');
    Route::get('/appointments/export', [HospitalAppointmenstsController::class, 'export'])->name('appointments.export');

    Route::get('/get-emirates/{countryId}', [LoginController::class, 'getEmirates'])->name('get-emirates');
    Route::get('/get-areas/{emirateId}', [LoginController::class, 'getAreas'])->name('get-areas');
    Route::get('/edit_profile', [UsersController::class, 'edit_profile'])->name('edit_profile');
    Route::get('/get_profile', [UsersController::class, 'get_profile'])->name('get_profile');
    Route::post('/save_profile', [UsersController::class, 'save'])->name('save_profile');
    Route::post('/save_profile_image', [UsersController::class, 'update_profile_image'])->name('save_profile_image');
    Route::get("reviews",  [UsersController::class, 'HospitalDoctorsReviews'])->name('reviews');
    // Route::post('/remove_hospital_image', [UsersController::class, 'delete_profile_image'])->name('remove_profile_image');
    Route::get('/ourinsurance', [HospitalInsuranceController::class, 'index'])->name('ourinsurance');

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
    
    Route::post("prescription/store", [HospitalAppointmenstsController::class, 'Prescriptionstore'])->name('prescription.store');
    Route::post('prescription/generate-pdf', [HospitalAppointmenstsController::class, 'generatePdf'])
    ->name('prescription.generate_pdf');
    Route::post('prescription/print-pdf', [HospitalAppointmenstsController::class, 'printPdf'])
    ->name('prescription.print-pdf');
    Route::post(
        'clinical-summary/store',[HospitalAppointmenstsController::class, 'Summarystore']
    )->name('summary.store');
    
    
    Route::post(
        'clinical_assessment_and_documentation/store',[HospitalAppointmenstsController::class, 'clinicalAssessmentStore']
    )->name('clinical_assessment_and_documentation.store');

    Route::post(
        'referral/store',
        [HospitalAppointmenstsController::class, 'storeReferral']
    )->name('referral.store');
    
    Route::get(
        'by/department',
        [HospitalAppointmenstsController::class, 'doctorsByDepartment']
    )->name('by.department');

    Route::get('/departments-by-referral',
    [HospitalAppointmenstsController::class, 'departmentsByReferral'])
    ->name('departments.by.referral');

    Route::get('appointment_history/{id}',
    [HospitalAppointmenstsController::class, 'booking_history'])->name('appointment_history');
    
    Route::get('/notifications', [HospitalDashboardController::class, 'notifications'])->name('notifications');
    Route::get('/dashboard', [HospitalDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors');
    Route::post('/load-data-doctors', [DoctorController::class, 'load_data'])->name('load-data-doctors');
    Route::get('/doctordetail/{id}', [DoctorController::class, 'detail'])->name('doctordetail');
    Route::get('/createDoctor', [DoctorController::class, 'create'])->name('createDoctor');
    Route::get('/editDoctor/{id}', [DoctorController::class, 'create'])->name('editDoctor');
    Route::post('/saveDoctor', [DoctorController::class, 'save'])->name('saveDoctor');
    Route::delete('/deleteDoctor/{id}', [DoctorController::class, 'destory'])->name('deleteDoctor');

    Route::post('/patient_appointment_cancel', [DoctorController::class, 'patientAppointmentCancel'])->name('patient_appointment_cancel');
    Route::post('/patient_appointment_urgent', [DoctorController::class, 'patientAppointmentUrgent'])->name('patient_appointment_urgent');
    Route::post('/patient_appointment_confirm', [DoctorController::class, 'patientAppointmentConfirmed'])->name('patient_appointment_confirm');
    Route::post('/patient_appointment_completed', [DoctorController::class, 'patientAppointmentCompleted'])->name('patient_appointment_completed');
    Route::post('/saveAppointmentFollowup', [DoctorController::class, 'saveAppointmentFollowup'])->name('saveAppointmentFollowup');
    Route::get('get-members/{id}', [PatientsController::class, 'getMembers'])->name('get-members');
    Route::get('get-hospital-departments/{hospital_id}', [DepartmentController::class, 'getHospitalDepartments'])->name('get-hospital-department');
    Route::get('get-hospital-doctors/{hospital_id}', [DoctorController::class, 'getHospitalDoctors'])->name('get-hospital-doctors');
    Route::get('get-department-doctors/{department_id}/{hospital_id?}', [DoctorController::class, 'getDepartmentDoctors'])->name('get-department-doctors');
    Route::get('get-clinic-doctors/{department_id}', [DoctorController::class, 'getClinicDoctors'])->name('get-clinic-doctors');

    // Route::get('/editdoctor/{id}', [DoctorController::class, 'create'])->name('editdoctor');
    Route::get('/appointments', [DoctorController::class, 'appointments'])->name('appointments');
    // Route::get('/dr-appointments/{id}', [DoctorController::class, 'appointmentsDR'])->name('dr-appointments');
    Route::get('/hospitalAppointments', [HospitalAppointmenstsController::class, 'index'])->name('hospitalAppointments');
    Route::post("/hospitalAppointmentsLoaddata", [HospitalAppointmenstsController::class, 'appointmentLoadData'])->name('agentAppointmentLoadData');
    Route::get("/create_appointment", [HospitalAppointmenstsController::class, 'create_appointment'])->name('create_appointment');
    Route::get("/create_dr_appointment/{doctor_id}", [HospitalAppointmenstsController::class, 'create_dr_appointment'])->name('create_dr_appointment');
    Route::get("/hospitalEditAppointment/{id}", [HospitalAppointmenstsController::class, 'create_appointment'])->name('edit_appointment');
    Route::post("/hospitalSaveAppointment", [HospitalAppointmenstsController::class, 'saveAppointment'])->name('saveAppointment');
    Route::post("/doctorSaveAppointment", [HospitalAppointmenstsController::class, 'saveDrAppointment'])->name('saveDrAppointment');
    Route::post("/hospitalRescheduleAppointment", [HospitalAppointmenstsController::class, 'rescheduleAppointment'])->name('rescheduleAppointment');
    Route::post("/uploadAppointmentDocs", [HospitalAppointmenstsController::class, 'uploadAppointmentDocs'])->name('uploadAppointmentDocs');
    Route::delete("/deleteDocs/{id}", [HospitalAppointmenstsController::class, 'deleteDocs'])->name('docs.delete');
    Route::delete("/hospitalDeleteAppointment/{id}", [HospitalAppointmenstsController::class, 'delete_appointment'])->name('delete_appointment');
    Route::post("/check_doctor_availability",[DoctorController::class,'check_doctor_availability']);
    Route::post("/check_doctor_unavailability",[DoctorController::class,'check_doctor_unavailability']);
    Route::delete("hospitals/delete/{id}", [HospitalController::class, 'destory'])->name('hospitals.delete');
    Route::post('/hospital/delete-img', [HospitalController::class, 'removeImage'])->name('hospital.delete-img');

    Route::get('/hospitals/hospital-details/{id}', [HospitalController::class, 'hospitalDetails'])->name('hospitals.hospitalDetails');

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
// hospital[HospitalController::class, 'availability']
    Route::get('/hospitals', [HospitalController::class, 'index'])->name('hospitals');
    Route::get('/hospitals/create', [HospitalController::class, 'create'])->name('hospitals.create');
    Route::get('/hospitals/edit/{id}', [HospitalController::class, 'create'])->name('hospitals.edit');
    Route::post('/hospitals/save', [HospitalController::class, 'save'])->name('hospitals.save');
    Route::post('/hospitals/load-data', [HospitalController::class, 'load_data'])->name('hospitals.load');
    Route::get('/hospitals/show/{id}', [HospitalController::class, 'show'])->name('hospitals.show');
    Route::get("hospitals/delete/{id}", [HospitalController::class, 'destory'])->name('hospitals.delete');

    Route::get('/hospitals/departments/{id}', [HospitalController::class, 'departments'])->name('hospitals.departments');
    Route::get('/hospitals/createDepartment/{hospital_id}', [HospitalController::class, 'createDepartment'])->name('hospitals.createDepartment');
    Route::post('/hospitals/saveDepartment', [HospitalController::class, 'saveDepartment'])->name('hospitals.saveDepartment');
    Route::get('/hospitals/editDepartment/{hospital_id}/{id}', [HospitalController::class, 'createDepartment'])->name('hospitals.editDepartment');
    Route::delete('/hospitals/deleteDepartment/{id}', [HospitalController::class, 'deleteDepartment'])->name('hospitals.deleteDepartment');

    Route::get('/hospitals/insurances/{id}', [HospitalController::class, 'insurances'])->name('hospitals.insurances');
    Route::get('/hospitals/createInsurance/{hospital_id}', [HospitalController::class, 'createInsurance'])->name('hospitals.createInsurance');
    Route::post('/hospitals/saveInsurance', [HospitalController::class, 'saveInsurance'])->name('hospitals.saveInsurance');
    Route::get('/hospitals/editInsurance/{hospital_id}/{id}', [HospitalController::class, 'createInsurance'])->name('hospitals.editInsurance');
    Route::delete('hospitals/deleteInsurance/{id}', [HospitalController::class, 'deleteInsurance'])->name('hospitals.deleteInsurance');

    Route::get('/hospitals/locations/{id}', [HospitalController::class, 'locations'])->name('hospitals.locations');
    Route::get('/hospitals/createLocation/{hospital_id}', [HospitalController::class, 'createLocation'])->name('hospitals.createLocation');
    Route::post('/hospitals/saveLocation', [HospitalController::class, 'saveLocation'])->name('hospitals.saveLocation');
    Route::get('/hospitals/editLocation/{hospital_id}/{id}', [HospitalController::class, 'createLocation'])->name('hospitals.editLocation');
    Route::delete('/hospitals/deleteLocation/{id}', [HospitalController::class, 'deleteLocation'])->name('hospitals.deleteLocation');

    Route::get('/hospitals/appointments/{id}', [HospitalController::class, 'appointments'])->name('hospitals.appointments');
    Route::post('/hospitals/appointment-loaddata', [HospitalController::class, 'appointmentLoadData'])->name('hospitals.appointmentLoadData');
    Route::get('/hospitals/create-appointment/{hospital_id}', [HospitalController::class, 'create_appointment'])->name('hospitals.create_appointment');
    Route::get('/hospitals/edit-appointment/{hospital_id}/{id}', [HospitalController::class, 'create_appointment'])->name('hospitals.edit_appointment');
    Route::post('/hospitals/saveAppointment', [HospitalController::class, 'saveAppointment'])->name('hospitals.saveAppointment');
    Route::delete('hospitals/delete-appointment/{id}', [HospitalController::class, 'delete_appointment'])->name('hospitals.delete_appointment');

    Route::get('/hospitals/doctors/{id}', [HospitalController::class, 'doctors'])->name('hospitals.doctors');
    //Route::get('/get-department-doctors/{department_id}', 'DoctorController@getDepartmentDoctors')->name('admin.get-department-doctors');
   // Route::get('/get-hospital-doctors/{hospital_id}', 'DoctorController@getHospitalDoctors')->name('admin.get-hospital-doctors');

   Route::get('/agents', [AgentsController::class, 'index'])->name('agents');
   Route::get('/agents/create', [AgentsController::class, 'create'])->name('agents.create');
   Route::get('/agents/edit/{id}', [AgentsController::class, 'create'])->name('agents.edit');
   Route::post('/agents/save', [AgentsController::class, 'save'])->name('agents.save');
   Route::post('/agents/load-data', [AgentsController::class, 'load_data'])->name('agents.load');
   Route::get('/agents/show/{id}', [AgentsController::class, 'show'])->name('agents.show');
   Route::get('/agents/appointments/{id}', [AgentsController::class, 'appointments'])->name('agents.appointments');
   Route::post('/agents/patient_appointment_save', [AgentsController::class, 'patientAppointmentSave'])->name('agents.patientAppointmentSave');
   Route::post('/agents/appointment-loaddata', [AgentsController::class, 'appointmentLoadData'])->name('agents.appointmentLoadData');
   Route::get('/agents/view-appointment/{id?}', [AgentsController::class, 'viewAppointment'])->name('agents.viewAppointment');
   Route::get("agents/delete/{id}", [AgentsController::class, 'destory'])->name('agents.delete');


//clinic ClinicController
Route::get("/clinics", [ClinicController::class, 'index'])->name('clinics.index');
Route::get("/clinics/create", [ClinicController::class, 'create'])->name('clinics.create');
Route::get("/clinics/edit/{id}", [ClinicController::class, 'create'])->name('clinics.edit');
Route::post("/clinics/save", [ClinicController::class, 'save'])->name('clinics.save');
Route::post("/clinics/load-data", [ClinicController::class, 'load_data'])->name('clinics.load');
Route::get("/clinics/show/{id}", [ClinicController::class, 'show'])->name('clinics.show');
Route::delete("clinics/delete/{id}", [ClinicController::class, 'destory'])->name('clinics.delete');
Route::get('/clinics/clinic-details/{id}', [ClinicController::class, 'clinicDetails'])->name('clinics.clinicDetails');



Route::get("/clinics/insurances/{id}", [ClinicController::class, 'insurances'])->name('clinics.insurances');
Route::get("/clinics/createInsurance/{hospital_id}", [ClinicController::class, 'createInsurance'])->name('clinics.createInsurance');
Route::post("/clinics/saveInsurance", [ClinicController::class, 'saveInsurance'])->name('clinics.saveInsurance');
Route::get("/clinics/editInsurance/{hospital_id}/{id}", [ClinicController::class, 'createInsurance'])->name('clinics.editInsurance');
Route::get('get-subInsurence/{id}', [ClinicController::class, 'getSubInsurence'])->name('admin.get-subInsurence');

Route::delete("/clinics/deleteInsurance/{id}", [ClinicController::class, 'deleteInsurance'])->name('clinics.deleteInsurance');

Route::get("/clinics/locations/{id}", [ClinicController::class, 'locations'])->name('clinics.locations');
Route::get("/clinics/createLocation/{hospital_id}", [ClinicController::class, 'createLocation'])->name('clinics.createLocation');
Route::post("/clinics/saveLocation", [ClinicController::class, 'saveLocation'])->name('clinics.saveLocation');
Route::get("/clinics/editLocation/{hospital_id}/{id}", [ClinicController::class, 'createLocation'])->name('clinics.editLocation');
Route::delete("/clinics/deleteLocation/{id}", [ClinicController::class, 'deleteLocation'])->name('clinics.deleteLocation');

Route::get("/clinics/appointments/{id}", [ClinicController::class, 'appointments'])->name('clinics.appointments');
Route::post("/clinics/appointment-loaddata", [ClinicController::class, 'appointmentLoadData'])->name('clinics.appointmentLoadData');
Route::get("/clinics/create-appointment/{hospital_id}", [ClinicController::class, 'create_appointment'])->name('clinics.create_appointment');
Route::get("/clinics/edit-appointment/{hospital_id}/{id}", [ClinicController::class, 'create_appointment'])->name('clinics.edit_appointment');
Route::post("/clinics/saveAppointment", [ClinicController::class, 'saveAppointment'])->name('clinics.saveAppointment');
Route::delete("/clinics/delete-appointment/{id}", [ClinicController::class, 'delete_appointment'])->name('clinics.delete_appointment');
Route::get("/clinics/doctors/{id}", [ClinicController::class, 'doctors'])->name('clinics.doctors');




   Route::get('/logout', [LoginController::class, 'logout'])->name('logout');






});
