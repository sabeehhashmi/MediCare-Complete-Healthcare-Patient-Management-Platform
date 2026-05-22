<?php
$CurrentUrl = url()->current();
?>
        <div class="chat-leftsidebar card mb-5">
            <div class="card-body" style="flex: 0;">
                <div class="text-center bg-light rounded px-4 py-3">
                    <div class="chat-user-status mt-4">
                        <div class="avatar-upload position-relative">
                            <div class="avatar-edit">
                                <input type="file" id="imageUpload" accept=".png, .jpg, .jpeg">
                                <label for="imageUpload"></label>
                            </div>
                            <div class="avatar-preview">
                                <div id="imagePreview" class="avatar-md rounded-circle mx-auto">
                                    <img src="{{$hospital->user->user_img_url ?? null}}" class="avatar-md rounded-circle" alt="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <h5 class="font-size-16 mb-1 mt-3"><a href="#" class="text-reset">{{ Auth::user()->name }} Hospital </a></h5>
                    <p class="text-muted mb-0">{{$hospital->address ?? null}}</p>
                </div>
            </div>

            <?php $patterns = array('hospital\/get_profile');
                  $patterns_flattened = implode('|', $patterns);
            ?>
            <div class="mail-list">
                <a href="{{ url('hospital/get_profile') }}" class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}} border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-user-circle font-size-20 align-middle me-3"></i>
                        <div class="flex-grow-1">
                            <h5 class="font-size-14 mb-0">Our Profile</h5>
                        </div>
                    </div>
                </a>
                <?php $patterns = array('hospital\/edit_profile');
                  $patterns_flattened = implode('|', $patterns);
                  ?>
                <a href="{{ url('hospital/edit_profile') }}" class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}} border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-star-outline font-size-20 align-middle me-3"></i>
                        <div class="flex-grow-1">
                            <h5 class="font-size-14 mb-0">Edit Profile</h5>
                        </div>
                        <div class="flex-shrink-0"></div>
                    </div>
                </a>
                <?php $patterns = array('hospital\/ourinsurance');
                  $patterns_flattened = implode('|', $patterns);
                  ?>
                <a href="{{ url('hospital/ourinsurance') }}" class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}} border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-newspaper-variant-outline font-size-20 align-middle me-3"></i>
                        <div class="flex-grow-1">
                            <h5 class="font-size-14 mb-0">Our Insurance</h5>
                        </div>
                        <div class="flex-shrink-0"></div>
                    </div>
                </a>

                <!-- <a href="" class="border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-pin font-size-20 align-middle me-3"></i>
                        <div class="flex-grow-1">
                            <h5 class="font-size-14 mb-0">Our Location</h5>
                        </div>
                        <div class="flex-shrink-0"></div>
                    </div>
                </a> -->
                <?php $patterns = array('hospital\/change_password');
                  $patterns_flattened = implode('|', $patterns);
                  ?>
                <a href="{{ url('hospital/change_password') }}" class="{{preg_match('/'.$patterns_flattened.'/', $CurrentUrl) ? 'active' : null}} border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-key-outline font-size-20 align-middle me-3"></i>
                        <div class="flex-grow-1">
                            <h5 class="font-size-14 mb-0">Change Password</h5>
                        </div>
                        <div class="flex-shrink-0"></div>
                    </div>
                </a>
            </div>
        </div>