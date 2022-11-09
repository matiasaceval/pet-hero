<?php

use Models\ReservationState;

require_once(VIEWS_PATH . "back-nav.php");

?>
<div class="container overflow-hidden">
    <div class="centered-wrapper">
        <?php
        if (empty($reservations)) { ?>
            <div class="centered-element">
                <div class="row justify-content-center">
                    <div class="col-md-auto">
                        <h2>your booking history <?php if ($states) echo "for the states specified " ?>is empty!</h2>
                    </div>
                </div>
                <div class="row mt-1 justify-content-center">
                    <div class="col-md-auto">
                        <h3>go check on our keepers list and start booking!</h3>
                    </div>
                </div>
                <div class="row mt-5 justify-content-center">
                    <div class="col-md-auto">
                        <a href="<?php echo ($states) ? FRONT_ROOT . "Owner/Reservations" : FRONT_ROOT ?>">
                            <button class="btn btn-primary"><?php echo ($states) ? "Clear filter" : "Go back" ?></button>
                        </a>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="row justify-content-center" style="flex-direction: column">
                <form id="ow-res-filt" method="get" action="<?php echo FRONT_ROOT ?>Owner/Reservations">
                    <div class="row mt-5 justify-content-center">
                        <?php
                        foreach (ReservationState::GetStates() as $state) { ?>
                            <input type='checkbox' <?php if (in_array($state, $states)) echo "checked" ?> value="<?php echo $state ?>" name='states[]'>
                            <p class="state-filter"><?php echo $state ?></p>
                            </input>
                        <?php } ?>
                    </div>
                    <div class=" row mt-4 justify-content-center">
                        <?php $allStatesSelected = count($states) == count(ReservationState::GetStates()) ?>
                        <div class="col-md-auto" style="padding: 4px">
                            <button class="btn btn-primary" type="submit" id="filter-btn">Filter</button>
                        </div>
                        <?php if ($states && !$allStatesSelected) { ?>
                            <div class="col-md-auto" style="padding: 4px">
                                <a href="<?php echo FRONT_ROOT . "Owner/Reservations" ?>">
                                    <button class="btn btn-primary" type="button" title="Clear filter"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </form>
            </div>
        <?php } ?>
    </div>

    <?php foreach ($reservations as $reservation) {
        $pet = $reservation->getPet();
        $owner = $pet->getOwner();
    ?>
        <div class="row mt-4 justify-content-center">
            <div class="kpr-card-box">
                <div class="row justify-content-center">
                    <div class="col-md-auto">
                        <div class="input-underline">
                            <p><span style="font-weight:bold; font-size: 24px"><?php echo $reservation->getState() ?></span></p>
                        </div>
                    </div>
                    <?php if (!in_array($reservation->getState(), array(ReservationState::PENDING, ReservationState::REJECTED))) { ?>
                        <div class="col justify-content-end" style="position:absolute; display:flex; padding-right: 24px">
                            <a href="<?php echo FRONT_ROOT ?>Owner/GenerateReservationBill?id=<?php echo $reservation->getId() ?>" target="_blank" onclick="window.open('#','_blank');window.open(this.href,'_self');">
                                <button class="btn btn-secondary" style="font-size: 16px;">Invoice <i style="font-size: 18px; padding-left: 8px" class="fa fa-download" aria-hidden="true"></i></button>
                            </a>
                        </div>
                    <?php } ?>
                </div>
                <div class="row mt-4 justify-content-center" style="padding: 0px 28px 0px 28px; width: fit-content; min-width: 44em">
                    <!-- Photo -->
                    <div class="col-md-auto">
                        <div class="row justify-content-center" style="margin-top: 4px; padding-right: 14px; padding-bottom: 14px;">
                            <img id="pet-image" class="cover" src="<?php echo FRONT_ROOT . UPLOADS_PATH . $pet->getImage() ?>" width="200px" height="200px">
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="col-md-auto">
                        <div class="row" style="width: 12em">
                            <div class="col-md-auto wrap-text wrap-text-max-width">
                                <p>Name</p>
                                <p title="<?php echo ucwords($pet->getName()) ?>"><span class="pet-data"><?php echo $pet->getName() ?></span></p>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-auto wrap-text wrap-text-max-width">
                                <p>Species</p>
                                <p title="<?php echo ucwords($pet->getSpecies()) ?>"><span class="pet-data"><?php echo $pet->getSpecies() ?></span></p>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-auto wrap-text wrap-text-max-width">
                                <p>Animal</p>
                                <p title="<?php echo ucwords($pet->getSpecies()) ?>"><span class="pet-data"><?php echo $pet->getSpecies() ?></span></p>
                                <p title="<?php echo ucwords($pet->getBreed()) ?>"><span class="pet-data"><?php echo $pet->getBreed() ?></span></p>
                            </div>
                        </div>
                    </div>

                    <!-- More info -->
                    <div class="col-md-auto wrap-text wrap-text-max-width">
                        <div class="row" style="width: 10em">
                            <div class="col-md-auto wrap-text wrap-text-max-width">
                                <p>Age</p>
                                <p title="<?php echo ucwords($pet->getAge()) ?>"><span class="pet-data"><?php echo $pet->getAge() ?></span></p>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-auto wrap-text wrap-text-max-width">
                                <?php $sex = $pet->getSex() == 'F' ? "Female" : "Male"; ?>
                                <p>Sex</p>
                                <p title="<?php echo $sex ?>"><span class="pet-data"><?php echo $sex ?></span></p>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-auto wrap-text wrap-text-max-width">
                                <p>Vaccines</p>
                                <a href="<?php echo $pet->getVaccine() ?>">
                                    <p><span class="pet-data">Click to see</span></p>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Book data -->
                    <div class="col-md-auto">
                        <div class="row justify-content-center">
                            <h2>Since/Until</h2>
                        </div>
                        <div class="row justify-content-center">
                            <!-- Since -->
                            <p><?php echo $reservation->getSince() ?></p>
                        </div>
                        <div class="row justify-content-center">
                            <!-- Until -->
                            <div class="input-underline">
                                <p><?php echo $reservation->getUntil() ?></p>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <!-- Price -->
                            <p>$<?php echo $reservation->getPrice() ?></p>
                        </div>
                    </div>
                </div>
                <div class="row mt-2 justify-content-between" style="padding-left: 16px;">
                    <div class="col-md-auto">
                        <?php $keeper = $reservation->getKeeper(); ?>
                        <div class="row mt-3">
                            <div class="col-md-auto wrap-text" style="max-width: 30em">
                                <p>Keeper: <span class="pet-data"><?php echo $keeper->getFullname(); ?></span></p>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-md-auto wrap-text" style="max-width: 30em">
                                <a href="mailto:<?php echo $keeper->getEmail(); ?>">
                                    <p>Email: <span class="pet-data"><?php echo $keeper->getEmail() ?></span></p>
                                </a>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-md-auto wrap-text" style="max-width: 30em">
                                <p>Phone: <span class="pet-data"><?php echo $keeper->getPhone() ?></span></p>
                            </div>
                        </div>
                    </div>
                    <?php
                    $state = $reservation->getState();
                    if ($state == ReservationState::ACCEPTED) { ?>
                        <div class="col-md-auto align-self-end">
                            <div class="row justify-content-center">
                                <div class="col-md-auto align-self-center">
                                    <form id="payment-form" enctype='multipart/form-data' action="<?php echo FRONT_ROOT ?>Owner/UploadPayment" method="POST">
                                        <div class="row mt-1 justify-content-center">
                                            <input hidden name="id" value="<?php echo $reservation->getId() ?>" />
                                            <input required id="selectedFile" name="image" type="file" accept="image/png, image/jpeg" style="display:none" onchange="(function (){
                                            var selectedFile = document.getElementById('selectedFile').files[0];
                                            console.log(selectedFile.name);
                                            $('#msg-success').text('Uploaded: ' + selectedFile.name);
                                            $('#payment-submit').css('display', 'block');
                                        })()" />
                                            <button class="btn btn-secondary" style="font-size: 22px; min-width: 150px" onclick="document.getElementById('selectedFile').click();">Upload Payment</button>
                                        </div>
                                        <div class="row mt-1 justify-content-center">
                                            <button id="payment-submit" class="btn btn-secondary" type="submit" style="font-size: 22px; min-width: 150px; display:none">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } else if ($state == ReservationState::FINISHED) { ?>
                        <div class="col-md-auto align-self-end">
                            <div class="row justify-content-center">
                                <div class="col-md-auto align-self-center">
                                    <div class="row mt-1 justify-content-center">
                                        <a href="<?php echo FRONT_ROOT ?>Review/Review?id=<?php echo $reservation->getId() ?>">
                                            <button class="btn btn-secondary" style="font-size: 22px; min-width: 150px;">Review</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>