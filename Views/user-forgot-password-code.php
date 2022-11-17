<?php

use Utils\TempValues;

require_once(VIEWS_PATH . "back-nav-no-logout.php");
?>
<link rel="stylesheet" href="<?php echo CSS_PATH ?>style.css">
<script>
    document.title = "Forgot Password / Pet Hero"
</script>
<div class="container overflow-hidden">
    <div class="centered-element">
        <div class="fp-card-box" style="padding: 48px; padding-bottom: 64px">
            <div class="row justify-content-center">
                <h1 style="text-align:center; margin: 32px">Recover your password</h1>
            </div>
            <script>
                $(document).ready(function() {
                    $(".box-input").on('paste', function(e) {
                        const splitted = e.originalEvent.clipboardData.getData('Text').split("");
                        const index = $("input[class='box-input']").index(this);
                        $(".box-input").each(function(i) {
                            const val = parseInt(splitted.shift());
                            if (!isNaN(val)) {
                                if (i >= index && $(this).val() == "") {
                                    $(this).val(val);
                                    const next = $("input[class='box-input']").eq($("input[class='box-input']").index(this) + 1);
                                    next.focus();
                                } else {
                                    splitted.unshift(val);
                                }
                            }
                        });
                        e.preventDefault();
                    });

                    $(".box-input").on('input', function(e) {
                        const val = String($(this).val());
                        if (val.length >= 1) {
                            $(this).val(val[0])
                            const next = $("input[class='box-input']").eq($("input[class='box-input']").index(this) + 1);
                            next.focus();
                        } else {
                            const i = $("input[class='box-input']").index(this);
                            if (val == "" && i > 0) {
                                const prev = $("input[class='box-input']").eq(i - 1);
                                prev.focus();
                                return;
                            }
                        }
                    });
                });
            </script>
            <div id="code">
                <form method="POST" action="<?php echo FRONT_ROOT . $userType ?>/SubmitCode">
                    <div class="row mt-4 justify-content-center">
                        <p><span style="font-size:20px !important; text-align: center">Insert the code that you received</span></p>
                    </div>
                    <div class="row justify-content-center">
                        <p><span style="font-size:12px !important; text-align: center">TIP: You can copy-paste it</span></p>
                    </div>
                    <div class="row mt-4 justify-content-center">
                        <div class="box-input-border">
                            <input required name="code[]" class="box-input" size="1" maxlength="1" type="number" max="9" min="0" placeholder="0" />
                        </div>
                        <div class="box-input-border">
                            <input required name="code[]" class="box-input" size="1" maxlength="1" type="number" max="9" min="0" placeholder="0" />
                        </div>
                        <div class="box-input-border">
                            <input required name="code[]" class="box-input" size="1" maxlength="1" type="number" max="9" min="0" placeholder="0" />
                        </div>
                        <div class="box-input-border">
                            <input required name="code[]" class="box-input" size="1" maxlength="1" type="number" max="9" min="0" placeholder="0" />
                        </div>
                        <div class="box-input-border">
                            <input required name="code[]" class="box-input" size="1" maxlength="1" type="number" max="9" min="0" placeholder="0" />
                        </div>
                    </div>
                    <div class="row mt-4 justify-content-center">
                        <div class="col-md-auto">
                            <button type="submit" class="btn btn-secondary btn-block btn-lg">Send</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>