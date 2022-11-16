<?php

use Utils\ReviewsAverage;
use Utils\Session;

require_once(VIEWS_PATH . "back-nav.php");
?>

<script>document.title = "Verify Payment / Pet Hero" </script>
<div class="container overflow-hidden">
    <div class="centered-element">
        <div class="kvp-card-box">
            <div class="row">
                <div class="col-12">
                    <?php $pet = $reservation->GetPet(); ?>
                    <?php $petInfo = ucwords($pet->getSpecies()) . ", " . ucwords($pet->getBreed()) . " (" . ($pet->getSex() == 'F' ? "Female" : "Male") . ", " . $pet->getAge() . " y/o)" ?>
                    <h2 class="text-center">$<?php echo $reservation->GetPrice() ?></h2>
                    <h2 class="text-center"><?php echo ucwords($pet->GetOwner()->getFullname()) ?></h2>
                    <h3 class="text-center">Pet: <?php echo $petInfo ?></h3>
                </div>
            </div>
            <div class="row mt-4 justify-content-center">
                <div class="col-md-auto">
                    <img id="payment" style="max-width: 650px; max-height: 450px" src="<?php echo FRONT_ROOT . UPLOADS_PATH . $reservation->getPayment() . "?" . time() ?>" />
                    <p id="caption" class="img-caption">If you find difficulties trying to read the image, you can <span style="font-family: monospace;">"Right Click > Open image in a new tab"</span> to zoom in with freedom.</p>
                    <script>
                        $(document).ready(function() {
                            $('#caption').width($('#payment').width());
                        })
                    </script>
                </div>
            </div>
            <div class="row mt-4 justify-content-center">
                <div class="col-md-auto">
                    <a href="<?php echo FRONT_ROOT ?>Keeper/AcceptPayment?id=<?php echo $reservation->getId() ?>">
                        <button style="font-size: 24px" class="btn btn-secondary">Accept</button>
                    </a>
                </div>
                <div class="col-md-auto">
                    <a href="<?php echo FRONT_ROOT ?>Keeper/RejectReservation?id=<?php echo $reservation->getId() ?>">
                        <button style="font-size: 24px" class="btn btn-error">Reject</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>