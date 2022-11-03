<?php

use Models\ReservationState;

require_once(VIEWS_PATH . "home-nav.php");
?>
<div class="container overflow-hidden">
    <div class="centered-element">
        <div class="card-box card-box-shadow">
            <div class="row card-box-margin-bottom">
                <div class="col-7">
                    <span class="title">Hi <?php echo $keeper->getFirstname(); ?>!</span><br>
                    <?php $count = count($reservationsOngoing) ?>
                    <span class="description">You have currently <?php echo $count . " reservation" . ($count == 1 ? "" : "s") ?> and <?php echo $availableDays ?> days available<?php if ($availableDays === 0) echo "!!! you're a BEAST ON FIRE 😜🔥🔥🔥" ?></span>
                </div>
            </div>

            <div class="row gy-5">
                <div class="col-3">
                    <!-- Pending books -->
                    <a href="">
                        <div class=" card-box card-box-border">
                            <?php $pending = $this->reservationDAO->GetByKeeperIdAndState($keeper->getId(), ReservationState::PENDING); ?>
                            <?php if(count($pending) > 0) {?><div class="circle" style="position: absolute; transform: translateX(270%) translateY(-40%);"><?php echo count($pending) ?></div><?php } ?>
                            <span class="title" style="text-align:left; position: absolute;">Pending books</span><br>
                            <span class="description" style="text-align:left; position: absolute; transform: translateY(89%);">List all pending reservation</span>
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
                            <span class="description">List all reservations including past ones.</span>
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