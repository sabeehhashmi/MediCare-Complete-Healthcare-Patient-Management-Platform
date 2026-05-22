@php
use App\Models\ContactUsSetting;

if (!isset($contactUsSettings)) {
    $contactUsSettings = ContactUsSetting::first()->toArray();
}

    if (!function_exists('contactUsData')) {
        function contactUsData($key, $settingsData) {
            $defVal = "#!";

            // If settings data is not array or object then return default value
            if (!is_array($settingsData) && !is_object($settingsData)) {
                return $defVal;
            }

            // If key is empty then return default value
            if (empty($key)) {
                return $defVal;
            }

            // If key is not found in settings data then return default value
            if (!isset($settingsData[$key])) {
                return $defVal;
            }

            // Return the value of key from settings data
            return $settingsData[$key];
        }
    }
@endphp

<div class="row">
    <div class="col-xl-12 py-3">
    <!-- <div class="col-xl-12 bg-prime-light py-3"> -->
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center mt-2">
                    <p class="mb-0">Mednero provides  easy and hassle-free access to information about healthcare providers in UAE. We help you schedule appointments with doctors 24/7. Reach us anytime, whether you are looking for doctors, hospitals, or health-related questions.</p>
                </div>
                <div class="col-12 mb-3">
                    <div class="footer-social-links mb-0">
                        <ul>
                            <li>
                                <a href="{{contactUsData("facebook", $contactUsSettings)}}" target="_blank">
                                    <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{contactUsData("twitter", $contactUsSettings)}}" target="_blank">
                                    <i class="fab fa-twitter" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{contactUsData("instagram", $contactUsSettings)}}" target="_blank">
                                    <i class="fab fa-instagram" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{contactUsData("linkedin", $contactUsSettings)}}" target="_blank">
                                    <i class="fab fa-linkedin" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-12">
                    <div class="footer-menu">
                        <ul class="p-0 text-center mb-0">
                            <li>
                                <a href="{{url('website/terms-conditions')}}">Terms and Conditions</a>
                            </li>
                            <li>
                                <a href="{{url('website/privacy-policy')}}">Privacy Policy</a>
                            </li>
                            <li>
                                <a href="{{url('website/faq-for-patient')}}">FAQ For Patients</a>
                            </li>
                            <li>
                                <a href="{{url('website/faq-for-doctors')}}">FAQ For Doctors</a>
                            </li>
                            <li>
                                <a href="{{url('website/faq-for-clinic-hospital')}}">FAQ For Clinic/Hospital</a>
                            </li>

                            <li>
                                <a href="#!" data-bs-toggle="modal" data-bs-target="#userinteractionmodal">User Instructions</a>
                            </li>
                            <li>
                                <a href="{{url('/website/benefits-for-doctors-and-patients')}}">Benefits for Doctors and Patients</a>
                            </li>
                            <li>
                                <a href="{{url('/website/delete-account')}}">Delete Account</a>
                            </li>
                            <!-- <li>
                                <a href="user-instructions.php">User Instructions for Patients</a>
                            </li>
                            <li>
                                <a href="user-instructions-doctors-panel.php">User Instructions for Clinic Panel</a>
                            </li>
                            <li>
                                <a href="user-instructions-clinic-panel.php">User Instructions for Clinic Panel</a>
                            </li>
                            <li>
                                <a href="user-instructions-hospital-panel.php">User Instructions for Clinic Panel</a>
                            </li>
                            <li>
                                <a href="benefits-doctors-and-communities.php">Benefits for Doctors and Patients</a>
                            </li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
