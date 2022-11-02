<?php

require_once(VIEWS_PATH . "back-nav.php");

?>
<div class="container overflow-hidden">
    <div class="centered-element">
        <form method="post" enctype='multipart/form-data' action="<?php echo FRONT_ROOT ?>Owner/PlaceReservation">
            <input readonly style="display: none" type="text" name="keeperId" value="<?php echo $keeper->getId() ?>">
            <div class="opr-card-box" style="padding: 48px 48px 32px 48px;">
                <!-- Head -->
                <div class="row justify-content-center">
                    <h2><span style="color: #222">You're about to book with...</span></h2>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12">
                        <h2 class="text-center"><?php echo ucwords($keeper->getFullname()) ?>!</h2>
                        <?php
                        $rating = round($keeper->getReviewsAverage(), 1);
                        if ($rating == -1) {
                            ?>
                            <div class="row align-items-center justify-content-center">
                                <div class="col-md-auto" style="padding-right: 4px">
                                    <h4 class="text-center"><span style="color: #222;">Not reviewed</span></h4>
                                </div>
                                <div class="col-md-auto" style="">
                                    <h4>|</h4>
                                </div>
                                <div class="col-md-auto" style="padding-left: 4px">
                                    <h4><span style="color: #222;">daily fee: $<?php echo $keeper->getFee() ?></span>
                                    </h4>
                                </div>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="row align-items-center justify-content-center">
                                <div class="col-md-auto" style="padding-right: 4px">
                                    <h2><span style="color: #222; font-size: 24px"><?php echo $rating ?></span></h2>
                                </div>
                                <div class="col-md-auto" style="padding-left: 4px">
                                    <h4>
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $rating) {
                                                echo '<span class="light-text-color fa fa-star checked"></span>';
                                            } else {
                                                echo '<span class="light-text-color fa fa-star"></span>';
                                            }
                                        }
                                        ?>
                                    </h4>
                                </div>
                                <div class="col-md-auto" style="padding-left: 4px">
                                    <h4>|</h4>
                                </div>
                                <div class="col-md-auto" style="padding-left: 4px">
                                    <h4><span style="color: #222;">daily fee: $<?php echo $keeper->getFee() ?></span>
                                    </h4>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="row mt-5 justify-content-center">
                    <div class="col-md-auto" style="margin-right: 18px">
                        <div class="row" id="pet-row">
                            <h3 class="text-center">What pet do you want to host?</h3>
                        </div>
                        <div class="row">
                            <select id="pet-select" name="petId">
                                <?php
                                foreach ($pets as $pet) {
                                    ?>
                                    <option value="<?php echo $pet->getId() ?>">
                                        <?php echo $pet->getName() ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>

                            <script>
                                $('#pet-select').width($('#pet-row').width() - 8);
                            </script>
                        </div>
                    </div>
                    <div class="col-md-auto" style="margin-left: 18px">
                        <div class="row" id="range-row">
                            <h3 class="text-center">In what range of days do you want to host it</h3>
                        </div>
                        <div class="row">
                            <input required readonly type="text" id="daterange" class="input-box"/>
                            <input type="hidden" id="since" name="since"/>
                            <input type="hidden" id="until" name="until"/>
                            <script>
                                $(function () {
                                    const since = '<?php echo $keeper->getStay()->getSince() ?>';
                                    const until = '<?php echo $keeper->getStay()->getUntil() ?>';
                                    const minDate = format(new Date(since));
                                    const maxDate = format(new Date(until));

                                    let isValid = false;

                                    const reservations = [
                                        <?php
                                        foreach ($reservations as $reservation) {
                                            echo "{";
                                            echo "since: new Date('" . $reservation->getSince() . "'),";
                                            echo "until: new Date('" . $reservation->getUntil() . "')";
                                            echo "},";
                                        } ?>
                                    ];

                                    <?php foreach ($reservations as $reservation) {
                                    echo "console.log('" . $reservation->getSince() . "');";
                                } ?>
                                    $('input[id="daterange"]').daterangepicker({
                                        opens: 'center',
                                        minDate: minDate,
                                        maxDate: maxDate,
                                        isInvalidDate: function (date) {
                                            for (const reservation of reservations) {
                                                if (date._d >= reservation.since && date._d <= reservation.until) {
                                                    return true;
                                                }
                                            }
                                        }
                                    });

                                    clearInputs();

                                    $('#daterange').on('apply.daterangepicker', function (ev, picker) {
                                        console.log("apply", {
                                            isValid
                                        });
                                        const start = picker.startDate;
                                        const end = picker.endDate;

                                        if (start.format('MM-DD-YYYY') == end.format('MM-DD-YYYY')) {
                                            isValid = false;
                                            alert("You must select a range of days");
                                            clearInputs();
                                            return;
                                        }

                                        if (start._d < new Date(since) || end._d > new Date(until)) {
                                            alert('You can only book between ' + since + ' and ' + until);
                                            clearInputs();
                                            isValid = false;
                                            return;
                                        }

                                        for (const reservation of reservations) {
                                            if (reservation.since >= start._d && reservation.until <= end._d) {
                                                alert('It seems that your selection includes a date that is already booked');
                                                clearInputs();
                                                isValid = false;
                                                return;
                                            }
                                        }

                                        isValid = true;
                                        $('input[id="since"]').val(start.format('MM-DD-YYYY'));
                                        $('input[id="until"]').val(end.format('MM-DD-YYYY'));

                                        const diffDays = Math.abs(parseInt((start._d - end._d) / (1000 * 60 * 60 * 24), 10));
                                        const dayOrDays = diffDays > 1 ? 'days' : 'day';
                                        $('h4[id="cost"]').text('Total cost for ' + diffDays + ' ' + dayOrDays + ': $' + diffDays * <?php echo $keeper->getFee() ?>);
                                    });

                                    $('#daterange').on('cancel.daterangepicker', function (ev, picker) {
                                        console.log("cancel", {
                                            isValid
                                        });
                                        if (!isValid) {
                                            clearInputs();
                                        }
                                    })
                                });

                                function clearInputs() {
                                    $('input[id="daterange"]').val('');
                                    $('input[id="daterange"]').attr("placeholder", "Click to select a date range");
                                    $('h4[id="cost"]').text('');
                                }

                                function format(date, extraYear = 0) {
                                    return `${date.getMonth() + 1}/${date.getDate()}/${date.getFullYear() + extraYear}`
                                }

                                function isDate(date) {
                                    return (new Date(date) !== " Invalid Date") && !isNaN(new Date(date));
                                }
                            </script>
                            <script>
                                $('#daterange').width($('#range-row').width() - 8);
                            </script>
                        </div>
                    </div>
                </div>
                <div class="row mt-5 justify-content-center">
                    <div class="col-md-auto">
                        <h4 id="cost"></h4>
                    </div>
                </div>
            </div>
            <!-- Submit -->
            <div class="row mt-4 justify-content-center">
                <div class="col-md-auto">
                    <button onclick="document.getElementById('back-btn').click(); return false;"
                            class="btn btn-primary">Cancel
                    </button>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary" <?php if ($pets == null) echo "disabled" ?>>Book
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>