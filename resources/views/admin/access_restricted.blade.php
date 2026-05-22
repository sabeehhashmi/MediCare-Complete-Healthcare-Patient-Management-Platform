@extends("admin.template.layout")

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <!-- Ajax Sourced Server-side -->
  <div class="">
    
    <div class="authentication-bg min-vh-80">
        <div class="bg-overlay bg-light"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex flex-column min-vh-80">
                        <div class="my-auto py-5">
                            <div class="text-center mb-4 pb-1">
                                <a href="{{route('admin.dashboard')}}" class="d-block auth-logo">
                                    <img src="{{ URL::asset('admin-assets/') }}/assets/images/Mednero.svg" alt=""  class="auth-logo-dark">
                                    <img src="{{ URL::asset('admin-assets/') }}/assets/images/Mednero.svg" alt=""  class="auth-logo-light">
                                </a>
                            </div>
                            <div class="row align-items-center justify-content-center"><!-- end col -->
                                <div class="col-md-5">
                                    <div class="mt-4">
                                        <img src="{{ URL::asset('admin-assets/') }}/assets/images/maintenance.png" class="img-fluid" alt="">
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->

                            
                            <div class="text-center text-muted my-5">
                                <h4>Access Restricted</h4>
                                <p>You dont have permission to access this page.</p>
                            </div>

                            
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
  </div>
  <!--/ Ajax Sourced Server-side -->






</div>
@stop
@section('script')
<script>
  jQuery(document).ready(function(){

      App.initTreeView();

  })
</script>
@stop