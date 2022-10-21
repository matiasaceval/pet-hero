<div class="d-flex justify-content-center">
    <div class="col-lg-3 text-center text-lg-end mt-3 mt-lg-0">
        <div class=" card">
            <div class="card-header"><?php

                                        use Utils\Session;

                                        $keeper = Session::Get("keeper") ?? Session::Get("temp-keeper");
                                        echo "¡Hola " . $keeper->getFirstname() . "!";

                                        $fee = '';
                                        $since = '';
                                        $until = '';
                                        if (Session::VerifySession("keeper")) {
                                            $fee = $keeper->getFee();
                                            $since = $keeper->getStay()->getSince();
                                            $until = $keeper->getStay()->getUntil();
                                        }
                                        ?>
            </div>
            <div class="card-body">
                <form action="<?php echo FRONT_ROOT ?>Keeper/SetFeeStay" method="POST" id="form" class="multi-range-field my-5 pb-5">
                    <div class="form-group">
                        <label class="control-label">
                            What's your fee?
                        </label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                $
                            </div>
                            <input required type="number" min="0" max="1000000" name="fee" placeholder="0" class="form-control" value="<?php echo $fee ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput">Wich range of days are you available?</label>
                        <input required type="text" id="daterange" class="form-control" />
                        <input type="hidden" id="since" <?php if (!empty($since)) echo "value='$since'" ?> name="since" />
                        <input type="hidden" id="until" <?php if (!empty($until)) echo "value='$until'" ?> name="until" />
                        <script>
                            $(function() {
                                const since = '<?php echo $since ?>';
                                const until = '<?php echo $until ?>';
                                const startDate = since ? format(new Date(formatFromDB(since))) : format(new Date());
                                const endDate = until ? format(new Date(formatFromDB(until))) : format(new Date());
                                const minDate = format(new Date());
                                const maxDate = format(new Date(), 1);
                                $('input[id="daterange"]').daterangepicker({
                                    opens: 'center',
                                    startDate: startDate,
                                    endDate: endDate,
                                    minDate: minDate,
                                    maxDate: maxDate,
                                    editable: true
                                }, function(start, end, label) {
                                    $('input[name="since"]').val(start.format('DD-MM-YYYY'));
                                    $('input[name="until"]').val(end.format('DD-MM-YYYY'));
                                    console.log("A new date selection was made: " + start.format('DD-MM-YYYY') + ' to ' + end.format('DD-MM-YYYY'));
                                });
                            });

                            function format(date, extraYear = 0) {
                                return `${date.getMonth() + 1}/${date.getDate()}/${date.getFullYear() + extraYear}`
                            }

                            function formatFromDB(date) {
                                const [day, month, year] = date.split('-');
                                return `${month}/${day}/${year}`
                            }

                            function isDate(date) {
                                return (new Date(date) !== "Invalid Date") && !isNaN(new Date(date));
                            }

                            document.getElementById('form').addEventListener("submit", e => {
                                var since = document.getElementById('since');
                                var until = document.getElementById('until');
                                if (since.value == "" || until.value == "") {
                                    alert('Please select a range of days!');
                                    e.preventDefault();
                                }
                            });
                        </script>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>