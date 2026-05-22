@extends('front.template.layout')

@section('title', 'Notification Settings')

@section('styles')
<style>
    .settings-card {
        background: #fff;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
    .toggle-group {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 0;
        border-bottom: 1px solid #eee;
        gap: 10px;
    }
    .toggle-group:last-child {
        border-bottom: none;
    }
    .toggle-info h5 {
        margin: 0;
        font-size: 16px;
        color: #333;
    }
    .toggle-info p {
        margin: 5px 0 0;
        font-size: 14px;
        color: #777;
    }
    /* Switch Styles */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        min-width: 50px;
        height: 26px;
    }
    .switch input { 
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: #1baeff;
    }
    input:focus + .slider {
        box-shadow: 0 0 1px #1baeff;
    }
    input:checked + .slider:before {
        transform: translateX(24px);
    }
</style>
@endsection

@section('content')
<div class="checkout-page user-account-page pt-100 mb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                @include('front.layouts.user-sidebar')
            </div>

            <div class="col-lg-8">
                <div class="settings-card">
                    <div class="checkout-form-title">
                        <h4>Notification Settings</h4>
                    </div>

                    @if(session('status') == '1')
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form action="{{ route('front.settings.update') }}" method="POST">
                        @csrf
                        
                        <div class="toggle-group">
                            <div class="toggle-info">
                                <h5>Appointment Reminders</h5>
                                <p>Get notified about your upcoming appointments 30 minutes before.</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="enable_reminder_notification" {{ $user->enable_reminder_notification ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group d-none">
                            <div class="toggle-info">
                                <h5>Public Notifications</h5>
                                <p>Receive news and broadcast notifications from our platform.</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="enable_public_notification" {{ $user->enable_public_notification ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <div class="toggle-info">
                                <h5>Lab & Medical Reports</h5>
                                <p>Get notified when your lab results or X-ray reports are uploaded.</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="enable_lab_result_notification" {{ $user->enable_lab_result_notification ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <div class="toggle-info">
                                <h5>Payment Confirmations</h5>
                                <p>Receive notifications for successful payments and order completions.</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="enable_payment_notification" {{ $user->enable_payment_notification ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="toggle-group">
                            <div class="toggle-info">
                                <h5>Prescription Available</h5>
                                <p>Get notified when a doctor uploads a digital prescription for you.</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="enable_prescription_notification" {{ $user->enable_prescription_notification ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="primary-btn1 w-100">
                                <span>Save Settings</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
