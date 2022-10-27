<?php
include_once 'header.php';
include_once 'nav.php';
?>

<main class="py-5">
    <section id="listado" class="mb-5">
        <div class="container">
            <h2 class="mb-4">Add new pet</h2>
            <form class="bg-light-alpha p-5" method="post" action="<?php echo FRONT_ROOT ?>Owner/AddPet">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="">Photo URL</label>
                            <input type="text" name="image" value="" class="form-control" required>
                            <label for="">Name</label>
                            <input type="text" name="name" class="form-control">
                            <label for="">Specie</label>
                            <input type="text" name="species" class="form-control">
                            <label for="">Breed</label>
                            <input type="text" name="breed" class="form-control">
                            <label for="">Vaccines Photo URL</label>
                            <input type="text" name="vaccine" class="form-control">
                            <br />
                            <label for="">Age</label>
                            <select name="age">
                                <option value="Puppy">Puppy</option>
                                <option value="Young">Young</option>
                                <option value="Adult">Adult</option>
                                <option value="Elder">Elder</option>
                            </select>
                            <br />
                            <label for="sex">Sex</label>
                            <select name="sex">
                                <option value="F">Female</option>
                                <option value="M">Male</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-dark ml-auto d-block">Agregar</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</main>