<?php

use Utils\Session;
use Utils\TempValues;

require_once(VIEWS_PATH . "back-nav-no-logout.php");
?>

<script>
    document.title = "Login / Pet Hero"
</script>
<div class="main">
    <div class="container">
        <div class="signin-image">
            <img src="<?php echo FRONT_ROOT . VIEWS_PATH ?>img/<?php echo strtolower($userType) ?>.png">
            <a href="<?php echo FRONT_ROOT . $userType ?>/SignUpView" class=" signin-image-link">I do not have an account</a>
        </div>
        <div class="signin-content">
            <div class="signin-form">
                <h2 class="form-title">Login</h2>
                <form method="POST" action="<?php echo FRONT_ROOT . $userType ?>/Login" class="register-form" id="register-form">
                    <div class="form-group">
                        <label for="email"><i class="zmdi zmdi-email"></i></label>
                        <input required type="email" name="email" id="email" placeholder="Your Email" value="<?php echo TempValues::GetValue("email") ?>" />
                    </div>
                    <div class="form-group">
                        <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                        <input required type="password" name="password" id="pass" placeholder="Password" />
                    </div>
                    <div>
                        <a href="<?php echo FRONT_ROOT . $userType ?>/ForgotPasswordView">
                            <span class="signin-image-link">Forgot password</span>
                        </a>
                    </div>
                    <div class=" form-group form-button">
                        <input required type="submit" id="signin" class="form-submit" value="Login" />
                    </div>
                    <?php
                    $error = Session::Get("error");
                    $success = Session::Get("success");
                    if ($error) {
                        echo "<span class='error'>$error</span>";
                        Session::Unset("error");
                    } else if ($success) {
                        echo "<span class='alert-success'>$success</span>";
                        Session::Unset("success");
                    }
                    ?>
                </form>
            </div>
        </div>

    </div>
</div>