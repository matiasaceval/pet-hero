<!-- jspdf -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" integrity="sha512-qZvrmS2ekKPF2mSznTQsxqPgnpkI4DNTlrdUmTzrDgektczlKNRRhy5X5AAOnx5S09ydFYWWNSfcEqDTTHgtNA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- html2canvas -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<?php
$pet = $reservation->getPet();
$owner = $pet->getOwner();
?>

<script>document.title = "Generating bill..."</script>

<body style="background: none">
    <div id="bill" class="container">
        <div class="row bill-border" style="padding: 12px">
            <div class="col">
                <div class="row align-items-center">
                    <div class="col-md-auto" style="margin-right: 0">
                        <img width="60px" height="60px" src="<?php echo FRONT_ROOT . VIEWS_PATH ?>img/pet-hero-black.png">
                    </div>
                    <div class="col-md-auto" style="padding-left: 0">
                        <span class="bill-font" style=" font-size: 48px"><strong>Pet Hero</strong></span>
                    </div>
                </div>
                <hr style="background-color: rgba(34, 34, 34, 1); height: 1.5px; margin-top: 12px; margin-bottom: 12px">
                <div class="row mt-4">
                    <div class="col-md-auto">
                        <p class="bill-font">PET TO HOST</p>
                        <div class="bill-border" style="width: 50em; height: fit-content; padding: 6px 6px 0px 8px; position: relative;">
                            <h3 class="bill-font"><?php echo strtoupper($pet->GetName()) ?></h3>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-auto">
                        <div class="row justify-content-between">
                            <div class="col-md-auto">
                                <p class="bill-font">ANIMAL</p>
                                <div class="bill-border" style="width: 33.5em; height: fit-content; padding: 6px 6px 0px 8px; position: relative;">
                                    <h3 class="bill-font"><?php echo strtoupper($pet->getSpecies()) . " (" . strtoupper($pet->getBreed()) . ")" ?></h3>
                                </div>
                            </div>
                            <div class="col-md-auto">
                                <p class="bill-font">SEX</p>
                                <div class="bill-border" style="width: 7.1em; height: fit-content; padding: 6px 6px 0px 8px; position: relative;">
                                    <h3 class="bill-font"><?php echo strtoupper($pet->getSex() == "F" ? "FEMALE" : "MALE") ?></h3>
                                </div>
                            </div>
                            <div class="col-md-auto">
                                <p class="bill-font">AGE</p>
                                <div class="bill-border" style="width: 5.6em; height: fit-content; padding: 6px 6px 0px 8px; position: relative;">
                                    <h3 class="bill-font"><?php echo $pet->getAge(); ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-auto">
                        <div class="row justify-content-between">
                            <div class="col-md-auto">
                                <p class="bill-font">SINCE</p>
                                <div class="bill-border" style="width: 24.05em; height: fit-content; padding: 6px 6px 0px 8px; position: relative;">
                                    <h3 class="bill-font"><?php echo $reservation->getSince() ?></h3>
                                </div>
                            </div>
                            <div class="col-md-auto">
                                <p class="bill-font">UNTIL</p>
                                <div class="bill-border" style="width: 24.05em; height: fit-content; padding: 6px 6px 0px 8px; position: relative;">
                                    <h3 class="bill-font"><?php echo $reservation->getUntil() ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-5 justify-content-center">
                    <div class="col-md-auto">
                        <p class="bill-font">KEEPER INFO</p>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-auto">
                        <p class="bill-font">FULL NAME</p>
                        <div class="bill-border" style="width: 50em; height: fit-content; padding: 6px 6px 0px 8px; position: relative;">
                            <h3 class="bill-font"><?php echo $reservation->getKeeper()->GetFullname() ?></h3>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-auto">
                        <div class="row justify-content-between">
                            <div class="col-md-auto">
                                <p class="bill-font">EMAIL</p>
                                <div class="bill-border" style="width: 24.05em; height: fit-content; padding: 6px 6px 0px 8px; position: relative;">
                                    <h3 class="bill-font"><?php echo $reservation->getKeeper()->getEmail(); ?></h3>
                                </div>
                            </div>
                            <div class="col-md-auto">
                                <p class="bill-font">PHONE</p>
                                <div class="bill-border" style="width: 24.05em; height: fit-content; padding: 6px 6px 0px 8px; position: relative;">
                                    <h3 class="bill-font"><?php echo $reservation->getKeeper()->getPhone(); ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-auto">
                        <p class="bill-font">TOTAL PRICE</p>
                        <div class="bill-border" style="width: 50em; height: fit-content; padding: 6px 6px 0px 8px; position: relative;">
                            <h3 class="bill-font">$<?php echo $reservation->getPrice() ?></h3>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-auto">
                        <small class="bill-font">* Please verify that all the information above is correct before paying</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            window.jsPDF = window.jspdf.jsPDF;
            const filename = 'reservation-<?php echo $reservation->getId() . "-" . strtolower($reservation->getPet()->getName()) ?>-bill';
            const element = document.getElementById('bill');

            const doc = new jsPDF('p', 'pt', 'letter');
            const margin = 10;
            const scale = (doc.internal.pageSize.width - 2 * margin) / element.clientWidth;
            console.log(scale, element.clientHeight);
            doc.setFont('Outfit-VariableFont_wght', 'normal');
            doc.html(element, {
                x: margin,
                y: 60,
                html2canvas: {
                    scale: scale
                },
                callback: function(doc) {
                    doc.save(filename + '.pdf');
                    window.close();
                }
            });
        })
    </script>
</body>