<?php

use Models\ReservationState;

require_once(VIEWS_PATH . "home-nav.php");
?>
<script>document.title = "Home / Pet Hero" </script>
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
                    <a href="<?php echo FRONT_ROOT ?>Pet/ListPets">
                        <div class="card-box card-box-border">
                            <span class="title">Pets</span><br>
                            <span class="description">List my pets and add new ones</span>
                        </div>
                    </a>
                </div>
                <div class="col-3">
                    <!-- Keepers -->
                    <a href="<?php echo FRONT_ROOT ?>Owner/KeepersListView">
                        <div class="card-box card-box-border">
                            <span class="title">Keepers</span><br>
                            <span class="description">List all keepers availables to book</span>
                        </div>
                    </a>
                </div>
                <div class="col-3">
                    <!-- Bookings -->
                    <?php 
                        $pendingR = $this->reservationDAO->GetByOwnerIdAndState($owner->getId(), ReservationState::ACCEPTED); 
                        $pending = count($pendingR) > 0;
                    ?>
                    <a href="<?php echo FRONT_ROOT ?>Reservation/Reservations">
                        <div class=" card-box card-box-border">
                            <?php if ($pending) { ?><div title="You have reservations accepted!" class="circle" style="position: absolute; transform: translateX(280%) translateY(-100%);"><?php echo count($pendingR) ?></div><?php } ?>
                            <span class="title" style="text-align:left; position: absolute;">Bookings</span><br>
                            <span class="description" style="text-align:left; position: absolute; transform: translateY(22%);">List all bookings made</span>
                        </div>
                    </a>
                </div>
                <div class="col-3">
                    <!-- Reviews -->
                    <a href="<?php echo FRONT_ROOT ?>Owner/ReviewsMade">
                        <div class="card-box card-box-border">
                            <span class="title">Reviews</span><br>
                            <span class="description">List all reviews made</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>