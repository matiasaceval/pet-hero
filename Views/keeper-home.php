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
                    <span class="description">You have currently <?php echo $count . " reservation" . ($count == 1 ? "" : "s") ?> ongoing.</span>
                </div>
            </div>

            <div class="row gy-5">
                <div class="col-3">
                    <!-- Pending books -->
                    <a href="<?php echo FRONT_ROOT ?>Keeper/ReservationsInProgress">
                        <div class=" card-box card-box-border">
                            <?php $pending = $this->reservationDAO->GetByKeeperIdAndStates($keeper->getId(), array(ReservationState::PENDING, ReservationState::PAID)); ?>
                            <?php if (count($pending) > 0) { ?><div title="You have pending reservations!" class="circle" style="position: absolute; transform: translateX(280%) translateY(-100%);"><?php echo count($pending) ?></div><?php } ?>
                            <span class="title" style="text-align:left; position: absolute;">Bookings<br>In Progress</span><br>
                            <span class="description" style="text-align:left; position: absolute; transform: translateY(62%);">List all reservations<br>but past ones</span>
                        </div>
                    </a>
                </div>
                <div class="col-3">
                    <!-- Bookings -->
                    <a href="<?php echo FRONT_ROOT ?>Keeper/Reservations">
                        <div class="card-box card-box-border">
                            <span class="title">Bookings</span><br>
                            <span class="description">List all reservations including past ones</span>
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
                <div class="col-3">
                    <!-- Update Profile -->
                    <a href="<?php echo FRONT_ROOT ?>Keeper/Reviews">
                        <div class="card-box card-box-border">
                            <span class="title">Profile</span><br>
                            <span class="description">Update your fee and stay date range here!</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>