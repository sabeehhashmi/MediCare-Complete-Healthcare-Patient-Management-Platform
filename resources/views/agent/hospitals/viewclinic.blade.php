
@include('agent.layouts.header')
<div class="w-100 user-chat mb-5">
    <div class="card">
        <div class="text-center bg-light rounded px-4 py-3">
            <div class="chat-user-status mt-0">
                <img src="{{$hospital->user->user_img_url}}" class="avatar-md rounded-circle" alt="" />
                <!-- <div class="">
                                                        <div class="status"></div>
                                                    </div> -->
            </div>
            <h5 class="font-size-16 mb-1 mt-3"><a href="#" class="text-reset">{{$hospital->name_en}}</a></h5>
        </div>

        <div class="p-4 pt-0">
            <div class="table-responsive mt-3 pb-3">
                <table class="table align-middle table-sm table-wrap table-borderless table-centered mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted">Name of the Clinic :</td>
                            <th class="fw-bold">{{$hospital->name_en}}</th>
                        </tr>
                        <!-- end tr -->

                        <tr>
                            <td class="text-muted">Country :</td>
                            <th class="fw-bold">{{$hospital->country->name}}</th>
                        </tr>
                        <!-- end tr -->
                        <tr>
                            <td class="text-muted">Cities:</td>
                            <th class="fw-bold">{{$hospital->emirate->name_en}}</th>
                        </tr>
                        <!-- end tr -->

                        <!--<tr>-->
                        <!--    <td class="text-muted">City :</td>-->
                        <!--    <th class="fw-bold">Abu Dhabi</th>-->
                        <!--</tr>-->
                        <tr>
                            <td class="text-muted">Area :</td>
                            <th class="fw-bold">{{$hospital->area->name_en}}</th>
                        </tr>
                        <tr>
                            <td class="text-muted">Address Of Organization :</td>
                            <th class="fw-bold">{{$hospital->address}}</th>
                        </tr>
                        <tr>
                            <td class="text-muted">Clinic Main Number :</td>
                            <th class="fw-bold">+{{$hospital->user->dial_code}}{{$hospital->user->phone}}</th>
                        </tr>
                        <!-- end tr -->
                        <tr>
                            <td class="text-muted">Email Address :</td>
                            <th class="fw-bold">{{$hospital->user->email}}</th>
                        </tr>
                        <!-- end tr -->
                        <tr>
                            <td class="text-muted">Website :</td>
                            <th class="fw-bold">{{$hospital->website}}</th>
                        </tr>


                        @if(isset($insurences))
                            <tr>
                                <td colspan="2">
                                    <table class="table table-sm table-nowrap table-striped table-bordered table-centered my-3">
                                        <thead class="">
                                            <tr>
                                                <th>Insurance Type</th>
                                                <th>Sub Insurances</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($insurences as $ins=>$v)
                                            <tr>
                                                <td>
                                                    <b class="fw-bold">{{$v['insurence_name']}}</b>
                                                </td>
                                                <td>
                                                    @if(isset($v['sub_insurences']) && !empty($v['sub_insurences']))
                                                    <ul>
                                                        @foreach($v['sub_insurences'] as $vsub)
                                                        <li>{{$vsub}}</li>
                                                        @endforeach
                                                    </ul>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                            <!-- end tr -->
                                            
                                            <!-- end tr -->
                                        </tbody>
                                        <!-- end tbody -->
                                    </table>
                                </td>
                            </tr>
                            @endif

                        <!-- end tr -->
                    </tbody>
                    <!-- end tbody -->
                </table>
            </div>
        </div>
    </div>



    <div class="card mt-4">
        <div class="card-body">
            <h5 class="font-size-24 mb-3">Profile</h5>
            <div class="mt-3">
                <p class="text-muted">
                    @php
                        echo $hospital->profile_description
                    @endphp
                </p>
            </div>
        </div>
    </div>


</div>
@include('agent.layouts.footer')
