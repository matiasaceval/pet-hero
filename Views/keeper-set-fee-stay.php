<?php

use Utils\Session;

$name = explode(" ", $keeper->getFirstname())[0];
$fee = '';
$since = '';
$until = '';
$btn = 'Save';
if (Session::VerifySession("keeper")) {
    $fee = $keeper->getFee();
    $since = $keeper->getStay()->getSince();
    $until = $keeper->getStay()->getUntil();
    $btn = 'Update';
    require_once(VIEWS_PATH . "back-nav.php");
}

?>

<div class="container overflow-hidden">
    <div class="centered-element">
        <form id="stay-fee-form" method="post" action="<?php echo FRONT_ROOT ?>Keeper/SetFeeStay">
            <div class="card-box" width="fit-content" style="padding: 48px 128px 48px 128px;">
                <!-- Head -->
                <div class="row justify-content-center">
                    <p><span class="pet-data">Hi <?php echo $name ?>!</span></p>
                </div>

                <!-- Body -->
                <div class="row mt-4">
                    <div class="col-12 ">
                        <div class="row mt-4 justify-content-center">
                            <div class="col-md-auto">
                                <p><span style="font-size:18px; text-align: center">Set your fee per day</span></p>
                            </div>
                        </div>
                        <div class="row mt-3 justify-content-center">
                            <div class="input-group mb-3 input-underline">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">
                                        $
                                    </span>
                                </div>
                                <input required type="number" name="fee" class="input-box" style="text-align:center"
                                       id=" fee" placeholder="Insert fee" value="<?php echo $fee ?>" min="0"
                                       max="999999"/>
                            </div>
                        </div>
                        <div class="row mt-5 justify-content-center">
                            <div class="col-md-auto">
                                <p><span style="font-size:18px; text-align: center">Set the range of days that you're available</span>
                                </p>
                            </div>
                        </div>
                        <div class="row mt-3 justify-content-center">
                            <div class="input-group mb-3 input-underline">
                                <input required type="text" id="daterange" class="input-box"
                                       style="text-align: center; "/>
                                <input type="hidden" id="since" value="<?php echo $since ?>" name="since"/>
                                <input type="hidden" id="until" value="<?php echo $until ?>" name="until"/>
                                <script>
                                    $(function () {
                                        const since = '<?php echo $since ?>';
                                        const until = '<?php echo $until ?>';
                                        const startDate = since ? format(new Date(since)) : format(new Date());
                                        const endDate = until ? format(new Date(until)) : format(new Date());
                                        const minDate = format(new Date());
                                        const maxDate = format(new Date(), 1);
                                        $('input[id="daterange"]').daterangepicker({
                                            opens: 'center',
                                            startDate: startDate,
                                            endDate: endDate,
                                            minDate: minDate,
                                            maxDate: maxDate,
                                        }, function (start, end, label) {
                                            $('input[name="since"]').val(start.format('MM-DD-YYYY'));
                                            $('input[name="until"]').val(end.format('MM-DD-YYYY'));
                                            console.log("A new date selection was made: " + start.format('DD-MM-YYYY') + ' to ' + end.format('DD-MM-YYYY'));
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

                                    document.getElementById('stay-fee-form').addEventListener("submit", e => {
                                        var since = document.getElementById('since');
                                        var until = document.getElementById('until');
                                        if (since.value == "" || until.value == "") {
                                            alert('Please select a range of days!');
                                            e.preventDefault();
                                        }
                                    });
                                </script>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Submit -->
            <div class="row mt-4 justify-content-center">
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary"><?php echo $btn ?></button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- 




-->