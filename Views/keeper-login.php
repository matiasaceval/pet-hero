<?php
use Utils\TempValues;
use Utils\Session;

TempValues::InitValues(["back-page" => FRONT_ROOT]);
require_once(VIEWS_PATH . "back-nav-no-logout.php");
?>
<div class="main">
    <div class="container">
        <div class="signin-image">
            <img src="<?php echo FRONT_ROOT . VIEWS_PATH ?>img/keeper.png">
            <a href="<?php echo FRONT_ROOT ?>Keeper/SignUpView" class=" signin-image-link">I do not have an account</a>
        </div>
        <div class="signin-content">
            <div class="signin-form">
                <h2 class="form-title">Login</h2>
                <form method="POST" action="<?php echo FRONT_ROOT ?>Keeper/Login" class="register-form" id="register-form">
                    <div class="form-group">
                        <label for="email"><i class="zmdi zmdi-email"></i></label>
                        <input required type="email" name="email" id="email" placeholder="Your Email" value="<?php echo TempValues::GetValue("email") ?>" />
                    </div>
                    <div class="form-group">
                        <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                        <input required type="password" name="password" id="pass" placeholder="Password" />
                    </div>
                    <div class="form-group form-button">
                        <input required type="submit" id="signin" class="form-submit" value="Login" />
                    </div>
                    <span class="error">
                        <?php
                        $error = Session::Get("error");
                        if ($error) {
                            echo $error;
                            Session::Unset("error");
                        }
                        ?>
                    </span>
                </form>
            </div>
        </div>

    </div>
</div>