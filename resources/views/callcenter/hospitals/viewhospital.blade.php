@include('callcenter.layouts.header')
<div class="position-relative mb-5">
    <div class="w-100 user-chat mt-4 mt-sm-0 ms-lg-3">
        <div class="card">
            <div class="text-center bg-light rounded px-4 py-3">
                <div class="chat-user-status mt-4">
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
                                <td class="text-muted">Name of the Hospital :</td>
                                <th class="fw-bold">{{$hospital->name_en ?? null}}</th>
                            </tr>
                            <!-- end tr -->

                            <tr>
                                <td class="text-muted">Country :</td>
                                <th class="fw-bold">{{$hospital->country->name ?? null}}</th>
                            </tr>
                            <!-- end tr -->
                            <tr>
                                <td class="text-muted">Cities:</td>
                                <th class="fw-bold">{{$hospital->emirate->name_en ?? null}}</th>
                            </tr>
                            <!-- end tr -->

                            <!--<tr>-->
                            <!--    <td class="text-muted">City :</td>-->
                            <!--    <th class="fw-bold">Abu Dhabi</th>-->
                            <!--</tr>-->
                            <tr>
                                <td class="text-muted">Area :</td>
                                <th class="fw-bold">{{$hospital->area->name_en ?? null}}</th>
                            </tr>
                            
                            <tr>
                                <td class="text-muted">Address Of Organization :</td>
                                <th class="fw-bold">{{$hospital->address ?? null}}</th>
                            </tr>
                            <tr>
                                <td class="text-muted">Hospital Main Number :</td>
                                <th class="fw-bold">+{{$hospital->user->dial_code ?? null }}{{$hospital->user->phone ?? null }}</th>
                            </tr>
                            <!-- end tr -->
                            <tr>
                                <td class="text-muted">Email Address :</td>
                                <th class="fw-bold">{{$hospital->user->email ?? null}}</th>
                            </tr>
                            <!-- end tr -->
                            <tr>
                                <td class="text-muted">Website :</td>
                                <th class="fw-bold">{{$hospital->website ?? null}}</th>
                            </tr>
                            <!-- end tr -->
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

                                            @foreach ($hospitalInsurancePolicies as $key => $hospitalInsurancePolicy)
                                            <tr>
                                                <td>
                                                    <b class="fw-bold">{{ $hospitalInsurancePolicy['insurance']->title }}</b>
                                                </td>
                                                <td>
                                                    <ul>
                                                        @foreach ($hospitalInsurancePolicy['subinsurances'] as $subinsurance)
                                                        <li>{{ $subinsurance->title }}</li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                            </tr>
                                            @endforeach
                                            <!-- end tr -->

                                            <!-- end tr -->
                                            <!-- <tr>
                                                <td>
                                                    <b class="fw-bold">AL Buhaira National Insurance Company</b>
                                                </td>
                                                <td>
                                                    <ul>
                                                        <li>Al Buhaira Network</li>
                                                        <li>MEDIUM-LIMITED-INS020</li>
                                                        <li>HIGH-COMPREHENSIVE PLUS-INS020</li>
                                                        <li>HIGH-COMPREHENSIVE-INS020</li>
                                                        <li>MEDIUM-STANDARDS-INS020</li>
                                                        <li>MEDIUM-RESTRICTED-INS020</li>
                                                    </ul>
                                                </td>
                                            </tr> -->
                                            <!-- end tr -->
                                        </tbody>
                                        <!-- end tbody -->
                                    </table>
                                </td>
                            </tr>
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

</div>
@include('callcenter.layouts.footer')
