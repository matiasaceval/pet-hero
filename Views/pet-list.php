<?php

use Utils\Session;

require_once(VIEWS_PATH . "back-nav.php");
?>
<?php
$err = Session::Get("error");
$succ = Session::Get("success");
if ($err || $succ) { ?>
    <div class="row mt-1 justify-content-center">
        <div class="col-md-auto">
            <p><span style="color: #fefcfd"><?php echo $err ?? $succ ?></span></p>
        </div>
    </div>
    <?php
    if ($err) Session::Unset('error');
    if ($succ) Session::Unset('success');
} ?>
<div class="container overflow-hidden">
    <div class="centered-wrapper">
        <?php
        if (empty($petList)) { ?>
            <div class="centered-element">
                <div class="row justify-content-center">
                    <div class="col-md-auto">
                        <h2>mmmh... it seems you don't have any pets yet.</h2>
                    </div>
                </div>
                <div class="row mt-1 justify-content-center">
                    <div class="col-md-auto">
                        <h3>maybe try adding a new one!</h3>
                    </div>
                </div>
                <div class="row mt-5 justify-content-center">
                    <div class="col-md-auto">
                        <a href="<?php echo FRONT_ROOT ?>Pet/AddPetView">
                            <button class="btn btn-primary">Add pet</button>
                        </a>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="row mt-5 justify-content-center">
                <div class="col-md-auto">
                    <a href="<?php echo FRONT_ROOT ?>Pet/AddPetView">
                        <button class="btn btn-primary">Add pet</button>
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="row mt-4 justify-content-center">
        <?php
        foreach ($petList as $key => $pet) {
            $newRow = $key % 3 == 0;
            ?>
            <div class="small-card-container" id="id-<?php echo $pet->getId() ?>">
                <div class="small-card-box pet-card-background">
                    <!-- Head -->
                    <div class="row">
                        <div class="row" style="padding: 0px 15px 0 15px">
                            <div class="col-2 align-self-center">
                                <img class="invert" width="50px"
                                     src="<?php echo FRONT_ROOT . VIEWS_PATH ?>img/pet-hero.png">
                            </div>
                            <div class="col-9">
                                <div class="row">
                                    <p><span style="text-transform: uppercase;">PET HERO</span></p>
                                </div>
                                <div class="row">
                                    <p><span style="text-transform: uppercase;">PET NATIONAL REGISTER</span></p>
                                </div>
                                <div class="row">
                                    <p><span style="text-transform: uppercase;">Ministry of the interior, public works and housing</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-1 align-self-center">
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle card-update-delete" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                        Edit
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item"
                                           href="<?php echo FRONT_ROOT ?>Pet/Update?id=<?php echo $pet->GetId() ?>">Update</a>
                                        <a class="dropdown-item" href="" onclick="
                                                const petName = '<?php echo $pet->GetName() ?>';
                                                const petId = <?php echo $pet->GetId() ?>;
                                                const msg = `Are you sure you want to delete ${petName} (id: ${petId})?`;
                                                if(confirm(msg)){
                                                    window.location.href = `<?php echo FRONT_ROOT ?>Pet/RemovePet?id=${petId}`;
                                                }
                                                ">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="row mt-4">
                        <div class="col-md-auto">
                            <div class="row" style="padding: 0px 15px 0 15px">
                                <img id="pet-image" class="cover"
                                     src="<?php echo FRONT_ROOT . UPLOADS_PATH . $pet->getImage() ?>" width="200px"
                                     height="200px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="row small-card-box-overwrapped" style="width: 10em">
                                <div class="col-md-auto">
                                    <p>Name</p>
                                    <p><span class="pet-data small-card-box-overwrapped"><?php echo $pet->getName() ?></span></p>
                                </div>
                            </div>
                            <div class="row mt-2 small-card-box-overwrapped">
                                <div class="col-md-auto">
                                    <p>Species</p>
                                    <p><span class="pet-data small-card-box-overwrapped"><?php echo $pet->getSpecies() ?></span></p>
                                </div>
                            </div>
                            <div class="row mt-2 small-card-box-overwrapped">
                                <div class="col-md-auto">
                                    <p>Breed</p>
                                    <p><span class="pet-data small-card-box-overwrapped"><?php echo $pet->getBreed() ?></span></p>
                                </div>
                            </div>
                            <div class="row mt-2 small-card-box-overwrapped">
                                <div class="col-md-auto">
                                    <p>Identifier</p>
                                    <p><span class="pet-data small-card-box-overwrapped"><?php echo $pet->getId() ?></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="row">
                                <div class="col-md-auto">
                                    <p>Sex</p>
                                    <p>
                                        <span class="pet-data small-card-box-overwrapped"><?php echo $pet->getSex() == 'F' ? "Female" : "Male" ?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-auto no-arrow">
                                    <p>Age</p>
                                    <p><span class="pet-data small-card-box-overwrapped"><?php echo $pet->getAge() ?></span></p>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-auto">
                                    <p>Vaccines</p>
                                    <a href="<?php echo $pet->getVaccine() ?>">
                                        <p><span class=" pet-data small-card-box-overwrapped">Click to see</span></p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
        }
        ?>
    </div>
</div>