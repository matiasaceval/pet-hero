<!-- html2pdf -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<?php
$pet = $reservation->getPet();
$owner = $pet->getOwner();
?>

<body style="background: none">
    <div id="bill" class="container">
        <div class="row bill-border" style="padding: 12px">
            <div class="col">
                <div class="row align-items-center">
                    <div class="col-md-auto" style="margin-right: 0">
                        <img width="60px" height="60px" src="<?php echo FRONT_ROOT . VIEWS_PATH ?>img/pet-hero-black.png">
                    </div>
                    <div class="col-md-auto" style="padding-left: 0">
                        <span style="font-size: 48px"><strong>Pet Hero</strong></span>
                    </div>
                </div>
                <hr style="background-color: rgba(34, 34, 34, 1); height: 1.5px; margin-top: 12px; margin-bottom: 12px">
                <div class="row mt-4">
                    <div class="col-md-auto">
                        <p>PET TO HOST</p>
                        <div class="bill-border" style="width: 50em; height: fit-content; padding: 6px 6px 0px 8px; position: relative;">
                            <h3><?php echo strtoupper($pet->GetName()) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-auto">
                        <div class="row justify-content-between">
                            <div class="col-md-auto">
                                <p>ANIMAL</p>
                                <div class="bill-border" style="width: 33.5em; height: fit-content; padding: 6px 6px 0px 8px; position: relative;">
                                    <h3><?php echo strtoupper($pet->getSpecies()) . " (" . strtoupper($pet->getBreed()) . ")" ?></h3>
                                </div>
                            </div>
                            <div class="col-md-auto">
                                <p>SEX</p>
                                <div class="bill-border" style="width: 7.1em; height: fit-content; padding: 6px 6px 0px 8px; position: relative;">
                                    <h3><?php echo strtoupper($pet->getSex() == "F" ? "FEMALE" : "MALE") ?></h3>
                                </div>
                            </div>
                            <div class="col-md-auto">
                                <p>AGE</p>
                                <div class="bill-border" style="width: 5.6em; height: fit-content; padding: 6px 6px 0px 8px; position: relative;">
                                    <h3><?php echo $pet->getAge(); ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-auto">
                        <div class="row justify-content-between">
                            <div class="col-md-auto">
                                <p>SINCE</p>
                                <div class="bill-border" style="width: 24.05em; height: fit-content; padding: 6px 6px 0px 8px; position: relative;">
                                    <h3><?php echo $reservation->getSince() ?></h3>
                                </div>
                            </div>
                            <div class="col-md-auto">
                                <p>UNTIL</p>
                                <div class="bill-border" style="width: 24.05em; height: fit-content; padding: 6px 6px 0px 8px; position: relative;">
                                    <h3><?php echo $reservation->getUntil() ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-5 justify-content-center">
                    <div class="col-md-auto">
                        <p>KEEPER INFO</p>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-auto">
                        <p>FULL NAME</p>
                        <div class="bill-border" style="width: 50em; height: fit-content; padding: 6px 6px 0px 8px; position: relative;">
                            <h3><?php echo $reservation->getKeeper()->GetFullname() ?></h3>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-auto">
                        <div class="row justify-content-between">
                            <div class="col-md-auto">
                                <p>EMAIL</p>
                                <div class="bill-border" style="width: 24.05em; height: fit-content; padding: 6px 6px 0px 8px; position: relative;">
                                    <h3><?php echo $reservation->getKeeper()->getEmail(); ?></h3>
                                </div>
                            </div>
                            <div class="col-md-auto">
                                <p>PHONE</p>
                                <div class="bill-border" style="width: 24.05em; height: fit-content; padding: 6px 6px 0px 8px; position: relative;">
                                    <h3><?php echo $reservation->getKeeper()->getPhone(); ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-auto">
                        <p>TOTAL PRICE</p>
                        <div class="bill-border" style="width: 50em; height: fit-content; padding: 6px 6px 0px 8px; position: relative;">
                            <h3>$<?php echo $reservation->getPrice() ?></h3>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-auto">
                        <small>* Please verify that all the information above is correct before paying</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const element = document.getElementById('bill');
        html2pdf()
            .set({
                margin: 30,
                html2canvas:  { scale: 3, width: 1600, height: 1600, x: 638 },
                jsPDF:        { unit: 'px', orientation: 'p' },
                filename: 'reservation-<?php echo $reservation->getId() . "-" . strtolower($reservation->getPet()->getName()) ?>-bill'
            })
            .from(element)
            .save()
            .then(() => {
                window.close();
            })
    </script>
</body>