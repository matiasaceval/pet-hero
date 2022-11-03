<?php
require_once(VIEWS_PATH . "home-nav.php");
?>
<div class="container overflow-hidden">
    <div class="centered-element">
        <div class="card-box card-box-shadow">
            <div class="row card-box-margin-bottom">
                <div class="col-7">
                    <span class="title">Hi <?php echo $keeper->getFirstname(); ?>!</span><br>
                    <?php $count = count($reservationsOngoing) ?>
                    <span class="description">You have currently <?php echo $count." reservation".($count == 1 ? "" : "s") ?> and <?php echo $availableDays ?> days available<?php if ($availableDays === 0) echo "!!! you're a BEAST ON FIRE 😜🔥🔥🔥" ?></span>
                </div>
            </div>

            <div class="row gy-5">
                <div class="col-3">
                    <!-- Pending books -->
                    <a href="">
                        <div class="card-box card-box-border">
                            <span class="title">Pending books</span><br>
                            <span class="description">List all pending books</span>
                        </div>
                    </a>
                </div>
                <div class="col-3">
                    <!-- In progress books -->
                    <a href="">
                        <div class="card-box card-box-border">
                            <span class="title">Ongoing books</span><br>
                            <span class="description">List all ongoing reservations</span>
                        </div>
                    </a>
                </div>
                <div class="col-3">
                    <!-- Bookings -->
                    <a href="">
                        <div class="card-box card-box-border">
                            <span class="title">Bookings</span><br>
                            <span class="description">List all reservations made including past ones.</span>
                        </div>
                    </a>
                </div>
                <div class="col-3">
                    <!-- Reviews -->
                    <a href="<?php echo FRONT_ROOT ?>Keeper/Reviews">
                        <div class="card-box card-box-border">
                            <span class="title">Reviews</span><br>
                            <span class="description">List the reviews that have been made to me</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>