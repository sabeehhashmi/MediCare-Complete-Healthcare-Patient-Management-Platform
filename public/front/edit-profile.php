<?php include('inc/header-login.php') ?>


<div class="inner-hero-section style--five">
    <div class="her-building-img">
        <img src="assets/images/elements/hero-building.png" class="img-fluid w-100" alt="">
    </div>
</div>

<div class="mt-minus-100 pb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-4 mb-4">
                <div class="user-card">
                    <div class="avatar-upload">
                        <!-- <div class="obj-el"><img src="assets/images/elements/team-obj.png" alt="image"></div> -->
                        <div class="avatar-edit">
                            <input type="file" id="imageUpload" accept=".png, .jpg, .jpeg" />
                            <label for="imageUpload"></label>
                        </div>
                        <div class="avatar-preview">
                            <div id="imagePreview" style="background-image: url(assets/images/user/1.png);"></div>
                        </div>
                    </div>
                    <h3 class="user-card__name mt-4">Albert Owens <img src="assets/images/premium.webp" class="img-fluid" style="height: 34px;" alt=""></h3>
                    <span class="user-card__id">ID : 19535909</span>
                </div>
                <div class="user-action-card">
                <ul class="user-action-list">
                        <li>
                            <a href="dashboard.php"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
                        </li>
                        <li class="active">
                            <a href="edit-profile.php"><i class="far fa-edit mr-2"></i> Edit Profile</a>
                        </li>
                        <li>
                            <a href="change-password.php"><i class="fas fa-key mr-2"></i> Change Password</a>
                        </li>
                        <li>
                            <a href="index.php"><i class="fas fa-sign-out-alt mr-2"></i> Log Out</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="user-card mb-30 p-0 overflow-hidden">
                    <div class="user-card text-start">
                        <h3>Edit Profile</h3>
                        <form action="">
                            <div class="row justify-content-center mt-4">
                                <div class="col-lg-6 mb-4">
                                    <div class="form-group mb-0 text-start">
                                        <label>First Name <sup>*</sup></label>
                                        <input type="text" name="" id="" placeholder="Enter First Name" value="Albert">
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="form-group mb-0 text-start">
                                        <label>Last Name <sup>*</sup></label>
                                        <input type="text" name="" id="" placeholder="Enter Last Name" value="Ownes">
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="form-group mb-0 text-start">
                                        <label>Mobile Number <sup>*</sup></label>
                                        <input type="number" name="" id="" placeholder="Enter Mobile Number" value="111111111">
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="form-group mb-0 text-start">
                                        <label>Email Id <sup>*</sup></label>
                                        <input type="email" name="" id="" placeholder="Enter Email Id" value="loremipsum@example.com">
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-3">
                                    <div class="form-group text-start mb-0">
                                        <button type="submit" class="cmn-btn w-100">Update</button>
                                    </div>                                
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include('inc/footer.php') ?>
