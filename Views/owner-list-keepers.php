<?php

use Utils\TempValues;

TempValues::InitValues(["back-page" => FRONT_ROOT]);
require_once(VIEWS_PATH . "back-nav.php");
?>
<div class="container overflow-hidden">
    <div class="centered-wrapper">
        <?php
        if (empty($keepersFromToday)) { ?>
            <div class="centered-element">
                <div class="row justify-content-center">
                    <div class="col-md-auto">
                        <h2>whoops! it seems that there are no keepers availables.</h2>
                    </div>
                </div>
                <div class="row mt-1 justify-content-center">
                    <div class="col-md-auto">
                        <h3>we're so sorry and regret the inconvenience, please try our service later.</h3>
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
        <?php } ?>
    </div>

    <div class="row mt-4 justify-content-center">
        <?php
        foreach ($keepersFromToday as $key => $keeper) {
        ?>
            <div class="kl-card-box keeper-card-background kl">
                <div class="row" style="padding: 0px 15px 0 15px">
                    <div class="col-8">
                        <div class="row">
                            <!-- Name -->
                            <?php $name = ucwords($keeper->getFirstname() . " " . $keeper->getLastname()); ?>
                            <h1 title="<?php echo $name ?>"><?php echo $name ?></h1>
                        </div>
                        <div class="row mt-2">
                            <?php
                            $rating = round($keeper->getReviewsAverage(), 1);
                            if($rating == -1){
                                ?> <p><span>Not reviewed</span></p> <?php
                            } else {
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $rating) {
                                        echo '<span class="light-text-color fa fa-star checked"></span>';
                                    } else {
                                        echo '<span class="light-text-color fa fa-star"></span>';
                                    }
                                }
                            }
                            ?>
                        </div>
                        <div class="row mt-4">
                            <h2>fee per day: <span style="font-weight: bold">$<?php echo $keeper->getFee() ?><span></h2>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="row justify-content-center">
                            <h2>Available</h2>
                        </div>
                        <div class="row justify-content-center">
                            <!-- Since -->
                            <p><?php echo $keeper->getStay()->getSince() ?></p>
                        </div>
                        <div class="row justify-content-center">
                            <!-- Until -->
                            <div class="input-underline">
                                <p><?php echo $keeper->getStay()->getUntil() ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3 justify-content-between align-self-end" style="padding: 0px 15px 0 15px">
                    <a href="<?php echo FRONT_ROOT ?>Owner/Reviews?id=<?php echo $keeper->getId() ?>">
                        <button class="btn btn-secondary" <?php if (empty($keeper->getReviews())) echo "disabled" ?>>See reviews</button>
                    </a>
                    <button class="btn btn-secondary">Book</button>
                </div>
            </div>
        <?php } ?>
    </div>
</div>