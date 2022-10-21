<?php

namespace Controllers;

class ListPetController
{
    public function Index()
    {
        require_once(VIEWS_PATH."list-pet.php");
    }
}