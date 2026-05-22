<?php

use Illuminate\Http\Request;
use App\Services\LoginService;

use function Livewire\Volt\{state, rules};

state([
    'email' => '',
    'password' => '',
    'remember' => false
]);

rules(fn () => [
    'email' => ['required', 'email'],
    'password' => ['required'],
]);

$login = function (Request $request) {
    $this->validate();
    
    try {
        if (auth()->attempt($this->only(['email', 'password']))) {
            if (auth()->check() && (auth()->user()->isHospital())) {
                app(LoginService::class)->check($request);
    
                return $this->redirectRoute('hospital.dashboard');
            }
        }
    } catch (\Exception $e) {
        auth()->logout();
        session()->flash('loginError', $e->getMessage());
        return redirect()->back();
    }
    auth()->logout();
    session()->flash('loginError', 'Invalid Credentials!');
    return redirect()->back();

}
?>


<div class="container">
    <div class="d-flex flex-column min-vh-100 px-3 pt-4">
        <div class="row justify-content-center my-auto">
            <div class="col-md-8 col-lg-6 col-xl-5">



                <div class="card">
                    <div class="card-body p-4">
                        <div class="mb-4 pb-2">
                            <a href="#" class="d-block auth-logo">
                                <img src="{{asset('/')}}admin-assets/assets/images/Mednero.svg" alt="{{config('global.site_name')}}" height="50" class="auth-logo-dark me-start">

                            </a>
                        </div>
                        <div class="text-center mt-2">
                            <h5>Welcome Back !</h5>
                            <p class="text-black">Sign in to continue .</p>
                        </div>
                        

                        <div class="p-2 mt-4">
                            <form class="form-login" method="post" wire:submit.prevent="login">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label" for="email">Email</label>
                                    <div class="position-relative input-custom-icon">
                                        <input type="text" class="form-control"wire:model="email" required placeholder="Enter Email" autocomplete="off" autofocus>
                                        <span class="bx bx-user"></span>
                                    </div>
                                    @error('email') 
                                        <p class="text-danger">{{ $message }}</p> 
                                    @enderror

                                </div>

                                <div class="mb-3">
                                    <div class="float-end">
                                        <!-- <a href="#" class="text-muted text-decoration-underline">Forgot password?</a> -->
                                    </div>
                                    <label class="form-label" for="password">Password</label>
                                    <div class="position-relative auth-pass-inputgroup input-custom-icon">
                                        <span class="bx bx-lock-alt"></span>
                                        <input type="password" class="form-control" wire:model="password" required placeholder="Enter password" autocomplete="new-password">
                                        <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon">
                                            <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                        </button>
                                    </div>
                                    @error('password') 
                                        <p class="text-danger">{{ $message }}</p> 
                                    @enderror
                                </div>

                                <div class="form-check py-1">
                                    <label class="form-check-label" for="auth-remember-check-1">
                                        <input type="checkbox" class="form-check-input" wire:model="remember" id="auth-remember-check-1" />
                                        Remember me
                                    </label>
                                </div>

                                <div class="mt-3">
                                    <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Log In</button>
                                </div>

                                @if (session('loginError'))
                                    <div class="alert alert-danger mt-2">
                                        {{ session('loginError') }}
                                    </div>
                                @endif
                            </form>
                        </div>

                    </div>
                </div>

            </div><!-- end col -->
        </div><!-- end row -->

        <div class="row">
            <div class="col-lg-12">
                <div class="text-center p-4">
                    <p class="text-white">© <script>
                            document.write(new Date().getFullYear())
                        </script> {{config('global.site_name')}}. </p>
                </div>
            </div>
        </div>

    </div>
</div><!-- end container -->