<?php

require_once(VIEWS_PATH . "back-nav.php");

?>

<div class="container overflow-hidden">
    <?php
    if (empty($reservations)) { ?>
        <div class="centered-wrapper">
            <div class="centered-element">
                <div class="row justify-content-center">
                    <div class="col-md-auto">
                        <h2>it seems that you don't have any reservation pending!</h2>
                    </div>
                </div>
                <div class="row mt-1 justify-content-center">
                    <div class="col-md-auto">
                        <h3>go check on your ongoing reservations!</h3>
                    </div>
                </div>
                <div class="row mt-1 justify-content-center">
                    <div class="col-md-auto">
                        <h4><span style="font-size: 20px">TIP: </span> try adding more days to your stay to be more attractive</h3>
                    </div>
                </div>
                <div class="row mt-5 justify-content-center">
                    <div class="col-md-auto">
                        <a href="<?php echo FRONT_ROOT ?>">
                            <button class="btn btn-primary">Go back</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    foreach ($reservations as $reservation) {
        $pet = $reservation->getPet();
        $owner = $pet->getOwner();
    ?>
        <div class="row mt-4 justify-content-center">
            <div class="kpr-card-box" style="padding-top: 48px; padding-bottom: 48px">
                <div class="row justify-content-center" style="width: fit-content; min-width: 44em">
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
                        <?php $owner = $reservation->getPet()->getOwner(); ?>
                        <div class="row mt-1">
                            <div class="col-md-auto wrap-text" style="max-width: 20em">
                                <p>Owner: <span class="pet-data"><?php echo $owner->getFullname(); ?></span></p>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-auto wrap-text" style="max-width: 50em">
                                <a href="mailto:<?php echo $owner->getEmail(); ?>">
                                    <p>Email: <span class="pet-data"><?php echo $owner->getEmail() ?></span></p>
                                </a>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-auto wrap-text" style="max-width: 20em">
                                <p>Phone: <span class="pet-data"><?php echo $owner->getPhone() ?></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-auto align-self-end">
                        <div class="row justify-content-center">
                            <div class="col-md-auto">
                                <a href="<?php echo FRONT_ROOT ?>Keeper/ConfirmReservation?id=<?php echo $reservation->getId() ?>">
                                    <button class="btn btn-secondary" style="font-size: 22px; min-width: 150px">Accept</button>
                                </a>
                            </div>
                        </div>
                        <div class="row mt-2 justify-content-center">
                            <div class="col-md-auto">
                                <a href="<?php echo FRONT_ROOT ?>Keeper/RejectReservation?id=<?php echo $reservation->getId() ?>">
                                    <button class=" btn btn-secondary" style="font-size: 22px; min-width: 150px">Reject</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>