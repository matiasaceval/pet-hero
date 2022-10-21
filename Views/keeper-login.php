<div class="main">
    <div class="container">
        <div class="signin-image">
            <figure id="pet-figure" class="signin-figure"></figure>
            <a href="<?php echo FRONT_ROOT ?>Keeper/SignUpView" class=" signin-image-link">I do not have an account</a>
        </div>
        <div class="signin-content">
            <div class="signin-form">
                <h2 class="form-title">Login</h2>
                <form method="POST" action="<?php echo FRONT_ROOT ?>Keeper/Login" class="register-form" id="register-form">
                    <div class="form-group">
                        <label for="email"><i class="zmdi zmdi-email"></i></label>
                        <input required type="email" name="email" id="email" placeholder="Your Email" />
                    </div>
                    <div class="form-group">
                        <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                        <input required type="password" name="password" id="pass" placeholder="Password" />
                    </div>
                    <div class="form-group form-button">
                        <input required type="submit" id="signin" class="form-submit" value="Login" />
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>