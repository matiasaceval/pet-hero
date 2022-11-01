<?php

use Utils\TempValues;
use Utils\Session;

require_once(VIEWS_PATH . "back-nav-no-logout.php");

?>
<div class="main">
    <div class="container-modified overflow-hidden">
        <div class="centered-element">
            <div class="row justify-content-center user-type" style="margin-bottom: 24px">
                <h2>Owner</h2>
            </div>
            <div class="card-box" style="padding: 32px 64px 32px 64px; width: fit-content;">
                <div class="row justify-content-center mt-3">
                    <div class="col-12">
                        <h2 class="form-title" style="margin-bottom: 18px">Sign up</h2>
                    </div>
                </div>
                <form method="POST" action="<?php echo FRONT_ROOT ?>Owner/SignUp" class="register-form" id="register-form">
                    <div class="row justify-content-center mt-3">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="first-name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input required type="text" name="firstname" id="first-name" placeholder="Your First Name" value="<?php echo TempValues::GetValue("firstname") ?>" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class=" form-group">
                                <label for="last-name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input required type="text" name="lastname" id="last-name" placeholder="Your Last Name" value="<?php echo TempValues::GetValue("lastname") ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-3">
                        <div class="col-6">
                            <div class=" form-group">
                                <label for="email"><i class="zmdi zmdi-email"></i></label>
                                <input required type="email" name="email" id="email" placeholder="Your Email" value="<?php echo TempValues::GetValue("email") ?>" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class=" form-group">
                                <label for="phone"><i class="zmdi zmdi-phone"></i></label>
                                <input required type="phone" name="phone" id="phone" placeholder="Your Phone" value="<?php echo TempValues::GetValue("phone") ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-3">
                        <div class="col-6">
                            <div class=" form-group">
                                <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                                <input required type="password" name="password" id="pass" placeholder="Password" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="re-pass"><i class="zmdi zmdi-lock-outline"></i></label>
                                <input required type="password" name="confirmPassword" id="re_pass" placeholder="Repeat your password" />
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-5">
                        <span class="error-centered">
                            <?php
                            $error = Session::Get("error");
                            if ($error) {
                                echo $error;
                                Session::Unset("error");
                            }
                            ?>
                        </span>
                    </div>
                    <div class="row justify-content-center">
                        <div class="form-group form-button">
                            <input required type="submit" id="signup" class="form-submit" value="Register" />
                        </div>
                    </div>
                    <div class="row justify-content-center mt-3">
                        <a href="<?php echo FRONT_ROOT ?>Owner/LoginView" class="signup-image-link">I am already member</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>