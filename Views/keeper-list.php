<?php
    require_once(VIEWS_PATH . "back-nav.php");
?>
<div>
    <h1 class="text-center">Keepers</h1>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Telefono</th>
                        <th>Desde</th>
                        <th>Hasta</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($keeperList)) { ?>

                        <?php /* @var $keeper Keeper */
                        usort($keeperList, function ($item1, $item2) {
                            return $item1->getStay()->GetSince() <=> $item2->getStay()->GetSince();
                        });
                        foreach ($keeperList as $keeper) { ?>
                            <tr>
                                <td><?php echo $keeper->getFirstname(); ?></td>
                                <td><?php echo $keeper->getLastname(); ?></td>
                                <td><?php echo $keeper->getEmail(); ?></td>
                                <td><?php echo $keeper->getPhone(); ?></td>
                                <td><?php echo $keeper->getStay()->getSince() ?></td>
                                <td><?php echo $keeper->getStay()->getUntil() ?></td>

                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

