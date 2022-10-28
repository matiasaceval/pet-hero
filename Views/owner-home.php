<?php
    require_once(VIEWS_PATH . "home-nav.php");
?>
<div class="container overflow-hidden">
    <div class="centered-element">
        <div class="card-box card-box-shadow">
            <div class="row card-box-margin-bottom">
                <div class="col-7">
                    <span class="title">Hi <?php echo $owner->getFirstname(); ?>!</span><br>
                    <span class="description">Welcome back, we've missed you!</span>
                </div>
                <div class="col-5">
                    <img src="<?php echo VIEWS_PATH ?>img/background.png" width="300px" class="img-fluid">
                </div>
            </div>

            <div class="row gy-5">
                <div class="col-3">
                    <!-- Pets -->
                    <a href="<?php echo FRONT_ROOT ?>Owner/Pets">
                        <div class="card-box card-box-border">
                            <span class="title">Pets</span><br>
                            <span class="description">List my pets and add new ones.</span>
                        </div>
                    </a>
                </div>
                <div class="col-3">
                    <!-- Keepers -->
                    <a href="<?php echo FRONT_ROOT ?>Owner/KeepersListView">
                        <div class="card-box card-box-border">
                            <span class="title">Keepers</span><br>
                            <span class="description">List all keepers availables to book.</span>
                        </div>
                    </a>
                </div>
                <div class="col-3">
                    <!-- Bookings -->
                    <a href="">
                        <div class="card-box card-box-border">
                            <span class="title">Bookings</span><br>
                            <span class="description">List all bookings made.</span>
                        </div>
                    </a>
                </div>
                <div class="col-3">
                    <!-- Reviews -->
                    <a href="">
                        <div class="card-box card-box-border">
                            <span class="title">Reviews</span><br>
                            <span class="description">List all reviews made.</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>