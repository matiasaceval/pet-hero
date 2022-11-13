<?php

use Utils\ReviewsAverage;
use Utils\Session;

require_once(VIEWS_PATH . "back-nav.php");
?>

<script>document.title = "Reviews / Pet Hero" </script>
<div class="container overflow-hidden">
    <div class="centered-element">
        <div class="centered-wrapper">
            <div class="kr-card-box">
                <div class="row">
                    <div class="col-12">
                        <h2 class="text-center"><?php echo ucwords($keeper->getFullname()) ?></h2>
                        <?php
                        $rating = round(ReviewsAverage::getReviewsAverage($reviews), 1);
                        if ($rating == -1) {
                        ?> <h1 class="text-center"><span style="color: #222;">Not reviewed</span></h1> <?php
                                                                                                    } else {
                                                                                                        ?>
                            <div class="row align-items-center justify-content-center">
                                <div class="col-md-auto" style="padding-right: 16px">
                                    <h2><span style="color: #222; font-size: 52px"><?php echo $rating ?></span><span style="font-size: 18px;">avg</span></h2>
                                </div>
                                <div class="col-md-auto" style="padding-left: 16px">
                                    <h4>
                                        <?php for ($i = 1; $i <= 5; $i++) echo '<span class="light-text-color fa fa-star ' . (($i <= $rating) ? 'checked' : '') . '"></span>'; ?>
                                    </h4>
                                    <h4>
                                        <span style="color: #222;"><?php echo count($reviews) ?> reviews</span>
                                    </h4>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php
                usort($reviews, function ($a, $b) {
                    $aDate = DateTime::createFromFormat("m-d-Y", $a->getDate());
                    $bDate = DateTime::createFromFormat("m-d-Y", $b->getDate());
                    return $bDate <=> $aDate;
                });
                foreach ($reviews as $key => $review) {
                    $pet = $review->getReservation()->getPet();
                    $owner = $pet->getOwner();

                    $sessionOwner = Session::Get("owner");
                    $author = false;
                    if ($sessionOwner && $sessionOwner->GetId() == $review->getReservation()->GetPet()->GetOwner()->GetId()) $author = true;

                    if ($key != 0) echo '<hr style="background-color: rgba(34, 34, 34, 0.3)">';
                ?>
                    <div class="<?php if ($author) echo "highlight-disappear" ?>">
                        <div class="row mt-4 justify-content-center">
                            <?php if ($author) { ?><p><span style="font-size: 16px; color:#574ae2 !important"><?php echo "Written by you!" ?></span></p><?php } ?>
                        </div>
                        <div class="row justify-content-between kr-comment-box" id="review-<?php echo $review->getId() ?>" style="padding-top: 0">
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
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>