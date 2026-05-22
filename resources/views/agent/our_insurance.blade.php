@include('agent.layouts.header')
<div class="position-relative mb-5">
    <div class="d-lg-flex">
        
    @include('agent.layouts.left_nav_profile')
        <!-- end chat-leftsidebar -->

        <div class="w-100 user-chat mt-4 mt-sm-0 ms-lg-3">
            <div class="card">
                <div class="p-4 pt-0">
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <h5 class="font-size-16 me-3 mb-0">Our Insurance</h5>
                        <a href="{{route('agent.addinsurance')}}" style="margin-left: auto;" class="btn btn-outline-primary waves-effect waves-light">Add Insurance</a>
                    </div>
                    <hr class="my-3">
                    
                    <div class="table-responsive mt-3 pb-0">
                        <table class="table table-sm table-nowrap table-centered mb-0">
                            <tbody>
                            @foreach ($insurances as $insurance)
                                   @php
                                        $inaurancename = DB::table('insurence_policies')
                                        ->where('insurence_policies.id', $insurance->insurance_id)
                                        ->first();

                                        $subInsuranceIds = explode(',', $insurance->sub_insurance_id);
                                        $subinsurances = DB::table('sub_insurence_policies')
                                        ->whereIn('id', $subInsuranceIds)
                                        ->get();
                                        
                                    @endphp 
                                
                                <tr>
                                    <td>
                                    <b class="fw-bold">{{$inaurancename->title}}</b>  </br> 
                                        <span class="text-muted my-1 d-block">- Sub Insurances</span>
                                    <ul>
                                       @foreach ($subinsurances as $subinsurance)
                                        <li>{{$subinsurance->title}}</li>
                                        @endforeach
                                    </ul>
                                    </td>
                                    <td>
                                        <span>
                                            <div class="d-flex gap-2 justify-content-end">
                                                <!-- <a href="{{route('agent.editinsurance',['id'=>$insurance->id])}}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit" class="btn btn-sm btn-icon btn-primary text-white text-success">
                                                    <i class="mdi mdi-pencil font-size-18"></i>
                                                </a> -->
                                                <a href="{{route('agent.deleteinsurance',['id'=>$insurance->id])}}" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" class="btn btn-sm btn-icon btn-secondary text-white text-danger">
                                                    <i class="mdi mdi-delete font-size-18"></i>
                                                </a>
                                            </div>
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                                <!-- end tr -->
                            </tbody>
                            <!-- end tbody -->
                        </table>
                    </div>
                </div>
            </div>
            <!-- end user chat -->
        </div>
        <!-- End d-lg-flex  -->
    </div>
</div>
@include('agent.layouts.footer')
<script src="{{ asset('') }}hospital/assets/js/ckeditor.js"></script>
<script src="{{ asset('') }}hospital/assets/js/form-editor.init.js"></script>
<script>
    function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').css('background-image', 'url('+e.target.result +')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#imageUpload").change(function() {
    readURL(this);
});

</script>
<script>
    $(document).ready(function() {
        // Check if there's a success message in the session
        @if(session('success'))
            toastr["success"]("{{ session('success') }}");
        @endif

        // Check if there are errors (optional)
        @if(session('error'))
            toastr["error"]("{{ session('error') }}");
        @endif
    });
</script>
