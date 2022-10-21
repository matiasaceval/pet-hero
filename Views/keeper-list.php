<?php

use Models\Keeper;

include_once "header.php";
include_once "nav.php";
?>

<div>
    <h1 class="text-center">Listado de keepers</h1>
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

