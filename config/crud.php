<?php
return [
    'operations' => [
        'c'     => 'Create',
        'r'     => 'Read',
        'u'     => 'Update',
        'd'     => 'Delete',
    ],
    'site_modules' => [
        //dashboard
        'dashboard'           => ['name' => 'Dashboard', 'operations' => ['r']],
        'appoitments'            => ['name' => 'Appoitments', 'operations' => ['r', 'u', 'c']],
        'hospitals'            => ['name' => 'Hositals', 'operations' => ['c','r', 'u', 'd']],
        'clinics'            => ['name' => 'Clinics', 'operations' => ['c','r', 'u', 'd']],
        'doctors'            => ['name' => 'Doctors', 'operations' => ['c','r', 'u', 'd']],
        'call_centers'            => ['name' => 'Call Centers', 'operations' => ['c','r', 'u', 'd']],
        'agents'            => ['name' => 'Agents', 'operations' => ['c','r', 'u', 'd']],
        'patients'            => ['name' => 'Patients', 'operations' => ['c','r', 'u', 'd']],
        //admin user
        // 'admin_users'         => ['name' => 'Admin Users', 'operations' => ['c', 'r', 'u', 'd']],
        'qualifications'          => ['name' => 'Qualifications', 'operations' => ['c', 'r', 'u', 'd']],
        'departments'          => ['name' => 'Departments', 'operations' => ['c', 'r', 'u', 'd']],
        'special_intrests'          => ['name' => 'Special Intrests', 'operations' => ['c', 'r', 'u', 'd']],
        'languages'          => ['name' => 'Languages', 'operations' => ['c', 'r', 'u', 'd']],
        'insurence_policy'          => ['name' => 'Insurence Policy', 'operations' => ['c', 'r', 'u', 'd']],
        'sub_insurence_policy'          => ['name' => 'Insurence Policy', 'operations' => ['c', 'r', 'u', 'd']],
        'specialties'          => ['name' => 'Specialties', 'operations' => ['c', 'r', 'u', 'd']],
        'countries'          => ['name' => 'Countries', 'operations' => ['c', 'r', 'u', 'd']],
        'country_of_origin'          => ['name' => 'Country of origin', 'operations' => ['c', 'r', 'u', 'd']],
        'emirates'          => ['name' => 'Emirates', 'operations' => ['c', 'r', 'u', 'd']],
        'area'          => ['name' => 'Area', 'operations' => ['c', 'r', 'u', 'd']],
        'banners'          => ['name' => 'Banners', 'operations' => ['c', 'r', 'u', 'd']],
        //'licencetype'          => ['name' => 'Licence Types', 'operations' => ['c', 'r', 'u', 'd']],
        
        
        //'medical_condition'          => ['name' => 'Medical Condition', 'operations' => ['c', 'r', 'u', 'd']],
        
       //    'services'          => ['name' => 'Services', 'operations' => ['c', 'r', 'u', 'd']],
        
        

       'reviews'          => ['name' => 'Reviews', 'operations' => [ 'r', 'u']],

        'user_roles'          => ['name' => 'User Roles', 'operations' => ['c', 'r', 'u', 'd']],
        'admin_users'          => ['name' => 'Admin Users', 'operations' => ['c', 'r', 'u', 'd']],
        

        //pages 
        // 'cms_hospital'                 => ['name' => 'CMS Hospital', 'operations' => ['c', 'r', 'u', 'd']],
        // 'cms_app_website'                 => ['name' => 'CMS App/Website', 'operations' => ['c', 'r', 'u', 'd']],
        // 'cms_clinic'                 => ['name' => 'CMS Clinic', 'operations' => ['c', 'r', 'u', 'd']],
        // 'cms_doctors'                 => ['name' => 'CMS Doctors', 'operations' => ['c', 'r', 'u', 'd']],
        // 'faq'                 => ['name' => 'FAQ For Patients', 'operations' => ['c', 'r', 'u', 'd']],
        // 'faq_for_doctors'                 => ['name' => 'FAQ For Doctors', 'operations' => ['c', 'r', 'u', 'd']],
        // 'faq_for_hospital'                 => ['name' => 'FAQ For Clinic/Hospital', 'operations' => ['c', 'r', 'u', 'd']],
        // 'contact_detail_settings' => ['name' => 'Contact Detail Settings', 'operations' => ['c', 'r', 'u', 'd']],
        // 'user_instructions'                 => ['name' => 'User Instructions for Patients', 'operations' => ['c', 'r', 'u', 'd']],
        // 'user_instructions_doctors'                 => ['name' => 'User Instructions for Doctors', 'operations' => ['c', 'r', 'u', 'd']],
        // 'user_instructions_clinic'                 => ['name' => 'User Instructions for Clinic', 'operations' => ['c', 'r', 'u', 'd']],
        // 'user_instructions_hospitals'                 => ['name' => 'User Instructions for Hospitals', 'operations' => ['c', 'r', 'u', 'd']],

        'settings'            => ['name' => 'CMS', 'operations' => ['c', 'r', 'u', 'd']],
        'homepage_management'    => ['name' => 'Homepage Management', 'operations' => ['u']],
        'contact_us_entries'    => ['name' => 'Contact Us Entries', 'operations' => ['r']],
        'bulkupload'            => ['name' => 'Bulk Upload', 'operations' => ['c', 'r']],
        
        

        //reports 
        

        //notifications
        // 'app_notifications' => ['name' => 'App Notifications', 'operations' => ['c', 'r', 'u', 'd']],
        // 'admin_notifications' => ['name' => 'Admin Notifications', 'operations' => ['c', 'r', 'u', 'd']],
    ]
];
