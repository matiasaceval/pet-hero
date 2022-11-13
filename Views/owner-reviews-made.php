<?php

use Utils\ReviewsAverage;
use Utils\Session;

require_once(VIEWS_PATH . "back-nav.php");
?>

<script>document.title = "Reviews / Pet Hero" </script>
<div class="container overflow-hidden">
    <div class="centered-element">
        <div class="centered-wrapper">
            <?php if (count($reviews) == 0) { ?>
                <div class="centered-element">
                    <div class="row justify-content-center">
                        <div class="col-md-auto">
                            <h2>you didn't made any review yet!</h2>
                        </div>
                    </div>
                    <div class="row mt-1 justify-content-center">
                        <div class="col-md-auto">
                            <h3>try our keeper system and let other people know how it went!</h3>
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
            <?php } else { ?>
                <div class="kr-card-box">
                    <div class="row">
                        <div class="col-12">
                            <h1 class="text-center"><span style="color: #222">Your Reviews!</span></h1>
                        </div>
                    </div>
                    <div class="row mt-5"></div>
                    <?php
                    usort($reviews, function ($a, $b) {
                        $aDate = DateTime::createFromFormat("m-d-Y", $a->getDate());
                        $bDate = DateTime::createFromFormat("m-d-Y", $b->getDate());
                        return $bDate <=> $aDate;
                    });
                    foreach ($reviews as $key => $review) {
                        $reservation = $review->getReservation();
                        $keeper = $reservation->getKeeper();
                        $pet = $reservation->getPet();
                        $owner = $pet->getOwner();

                        $sessionOwner = Session::Get("owner");
                        $author = false;
                        if ($sessionOwner && $sessionOwner->GetId() == $owner->GetId()) $author = true;

                        if ($key != 0) echo '<hr style="background-color: rgba(34, 34, 34, 0.3)">';
                    ?>
                        <a href="<?php echo FRONT_ROOT ?>Reservation/Reservations#reservation-<?php echo $reservation->getId() ?>">
                            <div class="row" style="padding-left: 16px">
                                    <h2 class="text-center"><span class="input-underline" style="font-size: 20px; padding: 0px"><?php echo ucwords($keeper->getFullname()) ?></span></h2>
                                </div>
                                <div class="row mt-2 justify-content-between kr-comment-box" id="review-<?php echo $review->getId() ?>" style="padding-top: 0">
                                    <div class="col-md-auto">
                                        <div class="row">
                                            <div class="col-md-auto">
                                            <?php
                                            $rating = round($review->getRating());
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $rating) {
                                                    echo '<span class="light-text-color fa fa-star checked"></span>';
                                                } else {
                                                    echo '<span class="light-text-color fa fa-star"></span>';
                                                }
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-auto">
                                            
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-auto">
                                                <p><?php echo $review->getComment() ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <div class="col-md-auto">
                                    <?php
                                    $date = DateTime::createFromFormat("m-d-Y", $review->getDate());
                                    $formattedDate = date("M. d, Y", $date->getTimestamp());
                                    ?>
                                    <p><span style="opacity: 0.6; font-size: 16px"><?php echo $formattedDate ?></span></p>
                                </div>
                            </div>
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>