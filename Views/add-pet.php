<?php
include_once 'header.php';
include_once 'nav.php';
?>


<main class="py-5">
    <section id="listado" class="mb-5">
        <div class="container">
            <h2 class="mb-4">Agregar mascota</h2>
            <form class="bg-light-alpha p-5" method="post" action="<?php echo FRONT_ROOT?>Owner/AddPet">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="">Nombre</label>
                            <input type="text" name="name" class="form-control">
                            <label for="">Especie</label>
                            <input type="text" name="species" class="form-control">
                            <label for="">Raza</label>
                            <input type="text" name="breed" class="form-control">
                            <br/>
                            <label for="">Edad</label>
                            <select name="age">
                                <option value="puppy">Cachorro</option>
                                <option value="young">Joven</option>
                                <option value="adult">Adulto</option>
                                <option value="senior">Mayor</option>
                            </select>
                            <br/>
                            <label for="gender">Sexo</label>
                            <select name="gender">
                                <option value="M">Hembra</option>
                                <option value="F">Macho</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-dark ml-auto d-block">Agregar</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</main>