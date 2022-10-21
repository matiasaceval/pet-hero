<div class="col-lg-6 text-center text-lg-end mt-3 mt-lg-0">
    <div class=" card">
        <div class="card-header"><?php

                                    use Utils\Session;

                                    $keeper = Session::Get("keeper");
                                    echo "¡Hola " . $keeper->getFirstname() . "!";
                                    ?></div>
        <div class="card-body">
            <form action="<?php echo FRONT_ROOT ?>Keeper/SetFeeStay" method="POST" class="multi-range-field my-5 pb-5">
                <div class="form-group">
                    <label for="formGroupExampleInput">What's you fee?</label>
                    <input required type="number" min="0" max="1000000" name="fee" class="form-control" placeholder="$1000">
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput">Wich range of days are you available?</label>
                    <input type="text" id="daterange" class="form-control" value="" placeholder="01/01/2022 - 01/15/2022" />
                    <input type="hidden" name="since" />
                    <input type="hidden" name="until" />
                    <script>
                        $(function() {
                            $('input[id="daterange"]').daterangepicker({
                                opens: 'left'
                            }, function(start, end, label) {
                                $('input[name="since"]').val(start.format('DD-MM-YYYY'));
                                $('input[name="until"]').val(end.format('DD-MM-YYYY'));
                                console.log("A new date selection was made: " + start.format('DD-MM-YYYY') + ' to ' + end.format('DD-MM-YYYY'));
                            });
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