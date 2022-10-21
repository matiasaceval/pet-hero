<div class="main">
    <div class="container">
        <div class="signup-content">
            <div class="signup-form">
                <h2 class="form-title">Sign up</h2>
                <form method="POST" action="<?php echo FRONT_ROOT ?>Keeper/SignUp" class="register-form" id="register-form">
                    <div class="form-group">
                        <label for="first-name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                        <input type="text" name="firstname" id="first-name" placeholder="Your First Name" />
                    </div>
                    <div class="form-group">
                        <label for="last-name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                        <input type="text" name="lastname" id="last-name" placeholder="Your Last Name" />
                    </div>
                    <div class="form-group">
                        <label for="email"><i class="zmdi zmdi-email"></i></label>
                        <input type="email" name="email" id="email" placeholder="Your Email" />
                    </div>
                    <div class="form-group">
                        <label for="phone"><i class="zmdi zmdi-phone"></i></label>
                        <input type="phone" name="phone" id="phone" placeholder="Your Phone" />
                    </div>
                    <div class="form-group">
                        <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                        <input type="password" name="password" id="pass" placeholder="Password" />
                    </div>
                    <div class="form-group">
                        <label for="re-pass"><i class="zmdi zmdi-lock-outline"></i></label>
                        <input type="password" name="confirmPassword" id="re_pass" placeholder="Repeat your password" />
                    </div>
                    <div class="form-group form-button">
                        <input type="submit" id="signup" class="form-submit" value="Register" />
                    </div>
                </form>
            </div>
        </div>
        <div class="signup-image">
            <figure id="pet-figure" class="signup-figure"></figure>
            <a href="<?php echo FRONT_ROOT ?>Keeper/LoginView" class="signup-image-link">I am already member</a>
        </div>
    </div>
</div>