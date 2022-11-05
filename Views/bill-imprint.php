<script>
    document
        .querySelectorAll('style,link[rel="stylesheet"]')
        .forEach(item => item.remove())
</script>
<div style="position: relative; min-height: 90vh; margin: 40px">
    <h1>Pethero</h1>
    <h2>Bill</h2>
    <h4>Since: <?php
        echo $reservation->getSince();
        ?></h4>
    <h4>Until: <?php
        echo $reservation->getUntil();
        ?></h4>

    <h4>Keeper: <?php
        echo $reservation->getKeeper()->getFirstName() . " " . $reservation->getKeeper()->getLastName();
        ?></h4>
    <h4>Pet: <?php
        echo $reservation->getPet()->getName() . " (" . $reservation->getPet()->getBreed() . ", " . $reservation->getPet()->getSpecies() . ")";
        ?>
    </h4>
    <div style="position:absolute; top: 0; right: 0">
        <img src="<?php echo FRONT_ROOT . UPLOADS_PATH . $reservation->getPet()->getImage(); ?>"
             style="width: 180px; height: 180px;"/>
    </div>
    <div style="position: absolute; bottom: 0; right: 0;">
        <h2>Total Price: <?php
            echo "$" . $reservation->getPrice();
            ?></h2>
        <small>Please verify that all the information above is correct before paying</small>
    </div>
</div>
<script>print()</script>

<script>// redirect to index
    setTimeout(function () {
        // TODO: Change redirection
        window.location.href = "<?php echo FRONT_ROOT . "Home/NotFound"; ?>";
    }, 50);
</script>
