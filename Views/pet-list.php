<?php
include_once 'header.php';
include_once 'nav.php';
?>

<div>
    <h1 class="text-center">Listado de mascotas</h1>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Sex</th>
                            <th>Specie</th>
                            <th>Breed</th>
                            <th>Vaccines</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($petList)) { ?>
                            <?php /*
                                    * @var Pet $pet
                                    */ foreach ($petList as $pet) { ?>
                                <tr>
                                    <td><img src="<?php echo $pet->getImage(); ?>" alt="Pet photo" width="75px" height="75px"></td>
                                    <td><?php echo $pet->getName(); ?></td>
                                    <td><?php echo $pet->getAge(); ?></td>
                                    <td><?php echo $pet->getSex() == 'F' ? "Female" : "Male"; ?></td>
                                    <td><?php echo $pet->getSpecies(); ?></td>
                                    <td><?php echo $pet->getBreed(); ?></td>
                                    <td><img src="<?php echo $pet->getVaccine(); ?>" alt="Pet vaccines" width="75px" height="75px"></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>