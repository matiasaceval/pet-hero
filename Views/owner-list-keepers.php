<?php

use Utils\Session;
use Utils\TempValues;

require_once(VIEWS_PATH . "back-nav.php");
?>

<script>
    function filterBtn() {
        const url = window.location.pathname;
        $('input[id="daterange"]').click();
    }
</script>
<form id="filter-form" method="get" action="<?php echo FRONT_ROOT ?>Owner/KeepersListView">
    <input type="hidden" id="since" name="since" />
    <input type="hidden" id="until" name="until" />
    <script>
        $(function() {
            const minDate = format(new Date());
            const maxDate = format(new Date(), 1);
            $('button[id="filter-btn"]').daterangepicker({
                opens: 'center',
                minDate: minDate,
                maxDate: maxDate,
            }, function(start, end, label) {
                if (start == "" || end == "") {
                    alert('Please select a valid range of days!');
                } else {
                    $('input[id="since"]').val(start.format('MM-DD-YYYY'));
                    $('input[id="until"]').val(end.format('MM-DD-YYYY'));
                    $('#filter-form').submit();
                }
            });

            if (!since && !until) {
                $('input[id="daterange"]').val('');
                $('input[id="daterange"]').attr("placeholder", "Click to select a date range");
            }
        });

        function format(date, extraYear = 0) {
            return `${date.getMonth() + 1}/${date.getDate()}/${date.getFullYear() + extraYear}`
        }

        function isDate(date) {
            return (new Date(date) !== " Invalid Date") && !isNaN(new Date(date));
        }
    </script>
</form>
<?php
$err = Session::Get("error");
$succ = Session::Get("success");
if ($err || $succ) { ?>
    <div class="row mt-1 justify-content-center">
        <div class="col-md-auto">
            <p><span style="color: #fefcfd"><?php echo $err ?? $succ ?></span></p>
        </div>
    </div>
<?php
    if($err) Session::Unset('error');
    if($succ) Session::Unset('success');
} ?>
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
                        <a href="<?php echo ($since && $until) ? FRONT_ROOT . "Owner/KeepersListView" : FRONT_ROOT ?>">
                            <button class="btn btn-primary"><?php echo ($since && $until) ? "Clear filter" : "Go back" ?></button>
                        </a>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="row justify-content-center" style="flex-direction: column">
                <div class="row justify-content-center">
                    <div class="col-md-auto" style="padding: 4px">
                        <button class="btn btn-primary" type="button" id="filter-btn" onclick="filterBtn();">Filter</button>
                    </div>
                    <?php if ($since && $until) { ?>
                        <div class="col-md-auto" style="padding: 4px">
                            <a href="<?php echo FRONT_ROOT . "Owner/KeepersListView" ?>">
                                <button class="btn btn-primary" type="button" title="Clear filter"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            </a>
                        </div>
                    <?php } ?>
                </div>
                <?php if ($since || $until) { ?>
                    <div class="row mt-1">
                        <div class="col-md-auto">
                            <?php if ($since && $until) { ?>
                                <p><span style="color: #fefcfd">Showing keepers available from <span style="font-weight:bold"><?php echo $since ?></span> to <span style="font-weight:bold"><?php echo $until ?></span></span></p>
                            <?php } else { ?>
                                <p><span style="color: #fefcfd">Please enter a full range of days to filter properly!</span></p>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
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
                            <?php $name = ucwords($keeper->getFullname()); ?>
                            <h1 title="<?php echo $name ?>"><?php echo $name ?></h1>
                        </div>
                        <div class="row mt-2">
                            <?php
                            $rating = round($keeper->getReviewsAverage(), 1);
                            if ($rating == -1) {
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
                    <a href="<?php echo FRONT_ROOT ?>Owner/PlaceReservationView?id=<?php echo $keeper->getId() ?>">
                        <button class="btn btn-secondary">Book</button>
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>