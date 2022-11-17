<?php

use Utils\Session;
use Utils\TempValues;

require_once(VIEWS_PATH . "back-nav-no-logout.php");
?>
<link rel="stylesheet" href="<?php echo CSS_PATH ?>style.css">
<script>
    document.title = "Forgot Password / Pet Hero"
</script>
<div class="container overflow-hidden">
    <div class="centered-element">
        <div class="fp-card-box" style="padding: 48px; padding-bottom: 64px">
            <div class="row justify-content-center">
                <h1 style="text-align:center; margin: 32px">Recover your password</h1>
            </div>
            <div id="email">
                <form method="POST" action="<?php echo FRONT_ROOT . $userType ?>/ResetPassword">
                    <div class="row justify-content-center mt-3">
                        <div class="form-group" id="password-form-group">
                            <label for="password"><i class="zmdi zmdi-lock"></i></label>
                            <input required type="password" name="password" onkeyup="isGood(this.value)" id="password" placeholder="Password" />
                            <small class="help-block" id="password-text"></small>
                        </div>
                        <span id="password-error" class="error text-danger" style="display: none">Error display</span>
                    </div>
                    <div class="row justify-content-center mt-3">
                        <div class="form-group">
                            <label for="confirmPassword"><i class="zmdi zmdi-lock-outline"></i></label>
                            <input required type="password" name="confirmPassword" id="confirmPassword" placeholder="Repeat your password" />
                        </div>
                    </div>
                    <div class="row justify-content-center mt-3">
                        <span style="font-size: 10px !important; text-align:center">* Hover over the password to see the requirements</span>
                    </div>
                    <?php
                    if (Session::VerifySession("error")) {
                        echo "<div class='row justify-content-center'>
                                    <div class='col-md-auto'>
                                        <p><span style='font-size:16px !important; text-align: center; color: red !important'>" . Session::Get("error") . "</span></p>
                                    </div>
                                </div>";
                        Session::Unset("error");
                    }
                    ?>
                    <div class="row mt-4 justify-content-center">
                        <div class="col-md-auto">
                            <button type="submit" class="btn btn-secondary btn-block btn-lg">Change my password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
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