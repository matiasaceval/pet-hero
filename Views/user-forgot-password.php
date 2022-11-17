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
                <form method="POST" action="<?php echo FRONT_ROOT . $userType ?>/ForgotPassword">
                    <div class="row mt-3 justify-content-center">
                        <div class="col-md-auto">
                            <p><span style="font-size:18px; text-align: center">Introduce your email</span></p>
                        </div>
                    </div>
                    <div class="row mt-3 justify-content-center">
                        <div class="input-group mb-3 input-underline">
                            <input required type="email" name="email" class="input-no-underline input-box" style="text-align:center" placeholder="Email" />
                        </div>
                    </div>
                    <?php 
                        if(Session::VerifySession("error")){
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
                            <button type="submit" class="btn btn-secondary btn-block btn-lg">Send</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>