<?php

use Utils\Session;
use Utils\TempValues;

require_once(VIEWS_PATH . "back-nav-no-logout.php");
?>

<script>document.title = "Sign Up / Pet Hero" </script>
<div class="main">
    <div class="container-modified overflow-hidden">
        <div class="centered-element">
            <div class="row justify-content-center user-type" style="margin-bottom: 24px">
                <h2><?php echo $userType ?></h2>
            </div>
            <div class="card-box" style="padding: 32px 64px 32px 64px; width: fit-content;">
                <div class="row justify-content-center mt-3">
                    <div class="col-12">
                        <h2 class="form-title" style="margin-bottom: 18px">Sign up</h2>
                    </div>
                </div>
                <form method="POST" action="<?php echo FRONT_ROOT . $userType ?>/SignUp" id="signup-form" class="register-form" id="register-form">
                    <div class="row justify-content-center mt-3">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="first-name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input required type="text" name="firstname" id="firstname" placeholder="Your First Name" value="<?php echo TempValues::GetValue("firstname") ?>" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class=" form-group">
                                <label for="last-name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input required type="text" name="lastname" id="lastname" placeholder="Your Last Name" value="<?php echo TempValues::GetValue("lastname") ?>" />
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
                                <span id="phone-error" class="error text-danger" style="display: none">Phone number is not valid</span>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-3">
                        <div class="col-6">
                            <div class="form-group" id="password-form-group">
                                <label for="password"><i class="zmdi zmdi-lock"></i></label>
                                <input required type="password" name="password" onkeyup="isGood(this.value)" id="password" placeholder="Password" />
                                <small class="help-block" id="password-text"></small>
                            </div>
                            <span id="password-error" class="error text-danger" style="display: none">Error display</span>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="confirmPassword"><i class="zmdi zmdi-lock-outline"></i></label>
                                <input required type="password" name="confirmPassword" id="confirmPassword" placeholder="Repeat your password" />
                            </div>
                        </div>
                    </div>
                    <span style="font-size: 10px">* Hover over the password to see the requirements</span>
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
                        <a href="<?php echo FRONT_ROOT . $userType ?>/LoginView" class="signup-image-link">I am already
                            member</a>
                    </div>
                    <script>
                        $(document).ready(function() {
                            $('#signup-form').submit(function(e) {
                                const password = $('#password');
                                const confirmPassword = $('#confirmPassword');
                                const phone = $('#phone');

                                if (!phone.val().match(/^\s*(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\s*$/)) {
                                    e.preventDefault();
                                    phone.focus();
                                    $('#phone-error').show();
                                    return;
                                } else {
                                    $('#phone-error').hide();
                                }

                                if (password.val() !== confirmPassword.val()) {
                                    e.preventDefault();
                                    password.focus();
                                    $('#password-error').html('Passwords are not equal');
                                    $('#password-error').show();
                                    return;
                                } else {
                                    $('#password-error').hide();
                                }

                                if (!isGood(password.val())) {
                                    e.preventDefault();
                                    password.focus();
                                    $('#password-error').html('Password is not safe enough.');
                                    $('#password-error').show();
                                    return;
                                } else {
                                    $('#password-error').hide();
                                }
                            });
                        })

                        function isGood(password) {
                            const passwordFormGroup = $('#password-form-group');
                            let password_strength = $('#password-text');
                            password_strength.removeClass();

                            const requirements = [
                                `\n[❌] At least one uppercase letter`,
                                `\n[❌] At least one special case letter`,
                                `\n[❌] At least two digits`,
                                `\n[❌] At least eight characters`
                            ];

                            if (password.length == 0) {
                                password_strength.html("");
                                password_strength.css("width", "0%");
                                password_strength.css("background-color", "transparent");
                                passwordFormGroup.prop('title', 'Your password must have: ' + requirements.join(''));
                                return;
                            }

                            password_strength.addClass('help-block');

                            //Regular Expressions.
                            let regex = new Array();
                            regex.push("(?=.*[A-Z])"); // Ensure string has one uppercase letter.
                            regex.push("(?=.*[!@#$&*])"); // Ensure string has one special case letter.
                            regex.push("(?=.*[0-9].*[0-9])"); // Ensure string has two digits.
                            regex.push(".{8}"); // Ensure string is of length 8.
                            let passed = 0;

                            //Validate for each Regular Expression.
                            for (let i = 0; i < regex.length; i++) {
                                if (new RegExp(`${regex[i]}`).test(password)) {
                                    passed++;
                                    requirements[i] = requirements[i].replace('❌', '✅');
                                }
                            }

                            passwordFormGroup.prop('title', 'Your password must have: ' + requirements.join(''));
                            //Display status.
                            switch (passed) {
                                case 0:
                                case 1:
                                case 2:
                                    password_strength.html("Weak");
                                    password_strength.addClass('progress-bar progress-bar-anim');
                                    password_strength.css('width', '40%');
                                    password_strength.css('background-color', 'red');
                                    break;
                                case 3:
                                    password_strength.html("Medium");
                                    password_strength.addClass('progress-bar progress-bar-anim');
                                    password_strength.css('width', '60%');
                                    password_strength.css('background-color', 'orange');
                                    break;
                                case 4:
                                    password_strength.html("Strong");
                                    password_strength.addClass('progress-bar progress-bar-anim');
                                    password_strength.css('width', '100%');
                                    password_strength.css('background-color', 'green');
                                    break;
                            }

                            return passed == regex.length;
                        }
                    </script>
                </form>
            </div>
        </div>
    </div>
</div>