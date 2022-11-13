<?php

use Utils\ReviewsAverage;
use Utils\Session;

require_once(VIEWS_PATH . "back-nav.php");
?>

<script>document.title = "Review Keeper / Pet Hero" </script>
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
                <div class="row mt-4 justify-content-between kr-comment-box">
                    <div class="col-12">
                        <div class="row justify-content-between" style="padding-left: 4px">
                            <div class="col-md-auto">
                                <div class="big-star-review">
                                    <?php
                                    for ($i = 1; $i <= 5; $i++) echo '<span class="star light-text-color fa fa-star" id="star-' . $i . '"></span>';
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-auto">
                                <?php
                                $formattedDate = date("M. d, Y");
                                ?>
                                <p><span style="opacity: 0.6; font-size: 16px"><?php echo $formattedDate ?></span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <form method="post" action="<?php echo FRONT_ROOT ?>Review/PlaceReview" id="review-form">
                    <input type="hidden" name="reservationId" value="<?php echo $reservation->getId() ?>">
                    <input type="hidden" name="rating" value="0">
                    <div class="row">
                        <div class="col-12">
                            <textarea required class="review-input" name="comment" placeholder="Place your review here..."></textarea>
                        </div>
                    </div>
                    <div class="row mt-4 justify-content-center">
                        <div class="col-md-auto">
                            <button class="btn btn-secondary" style="font-size: 22px; min-width: 150px;">Submit Review</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var star = 0;

    $(document).ready(function() {
        $(".star").mouseover(function() {
            const val = $(this).attr("id").split("-")[1];
            console.log("Mouse over star " + val);
            for (let i = 1; i <= val; i++) {
                $("#star-" + i).addClass("checked");
            }
        });

        $(".star").mouseout(function() {
            const val = $(this).attr("id").split("-")[1];
            console.log("Mouse out star " + val);
            for (let i = 1; i <= 5; i++) {
                if (i > star) $("#star-" + i).removeClass("checked");
            }
        });

        $(".star").click(function() {
            const val = $(this).attr("id").split("-")[1];
            star = star == val ? 0 : val;
            console.log("Clicked star " + val);
            for (let i = 1; i <= 5; i++) {
                if (i > star) $("#star-" + i).removeClass("checked");

            }
        });

        $("#review-form").submit(function() {
            if (star == 0) {
                alert("Please select a rating");
                return false;
            }
            $("input[name='rating']").val(star);
        });
    });
</script>