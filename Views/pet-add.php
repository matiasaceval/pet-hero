<?php

require_once(VIEWS_PATH . "back-nav.php");

?>

<script>document.title = "Add Pet / Pet Hero" </script>
<div class="container overflow-hidden">
    <div class="centered-element">
        <form method="post" enctype='multipart/form-data' action="<?php echo FRONT_ROOT ?>Pet/AddPet">
            <div class="card-box pet-card-background" width="fit-content" style="padding: 32px 48px 32px 48px;">
                <!-- Head -->
                <div class=" row">
                    <div class="row" style="padding: 0px 15px 0 15px">
                        <div class="col-2 align-self-center">
                            <img class="invert" width="60px"
                                 src="<?php echo FRONT_ROOT . VIEWS_PATH ?>img/pet-hero.png">
                        </div>
                        <div class="col-md-auto">
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
                        <div class="col-6"></div>
                    </div>
                </div>

                <!-- Body -->
                <div class="row mt-4">
                    <div class="col-md-auto">
                        <div class="row" style="padding: 0px 15px 0 15px">
                            <img id="pet-image" class="cover"
                                 src="<?php echo FRONT_ROOT . VIEWS_PATH ?>img/pet-placeholder.png" width="300px"
                                 height="300px">
                        </div>
                        <div class="row mt-1 justify-content-center">
                            <div class="col-md-auto">
                                <input required id="selectedFile" name="image" type="file"
                                       accept="image/png, image/jpeg" style="display:none" onchange="(function (){
                                                   var selectedFile = document.getElementById('selectedFile').files[0];
                                                   var img = document.getElementById('pet-image')
                                                   var reader = new FileReader();
                                                   reader.onload = function(){
                                                       img.src = this.result
                                                   }
                                                   reader.readAsDataURL(selectedFile);
                                               })()"/>
                                <input required type="button" value="Upload Image" class="btn-secondary"
                                       onclick="document.getElementById('selectedFile').click();"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="row">
                            <div class="col-md-auto">
                                <p>Name</p>
                                <input required name="name" maxlength="20" class="pet-data" placeholder="Name"/>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-auto">
                                <p>Species</p>
                                <input required name="species" maxlength="20" class="pet-data" placeholder="Species"/>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-auto">
                                <p>Breed</p>
                                <input required name="breed" maxlength="20" class="pet-data" placeholder="Breed"/>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-auto">
                                <p>Identifier</p>
                                <p><span class="pet-data">Not assigned yet</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="row mt">
                            <div class="col-md-auto">
                                <p>Sex</p>
                                <select name="sex" class="pet-data pointer" style="padding-right: 12px;">
                                    <option class="pet-data" value="M">MALE</option>
                                    <option class="pet-data" value="F">FEMALE</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-auto no-arrow">
                                <p>Age</p>
                                <input required name="age" min="0" max="100" type="number" maxlength="5"
                                       class="pet-data" placeholder="Age"/>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-auto">
                                <p>Vaccines</p>
                                <input required name="vaccine" type="url" class="pet-data" placeholder="Link to .pdf"/>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Submit -->
            <div class="row mt-4 justify-content-center">
                <div class="col-md-auto">
                    <button onclick="document.getElementById('back-btn').click(); return false;"
                            class="btn btn-primary">Cancel
                    </button>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>