<?php

namespace Models;


class Pet
{
    private int $id;
    private string $name;
    private string $species; //if it is a dog, cat, bird, etc.
    private string $breed; //if it is a dog, what breed is it? labrador, poodle, etc.
    private string $gender;
    private date $birthdate;
    private int $ownerId;

    //getter and setter methods

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSpecies(): string
    {
        return $this->species;
    }


    public function setSpecies(string $species): void
    {
        $this->species = $species;
    }

    public function getBreed(): string
    {
        return $this->breed;
    }

    public function setBreed(string $breed): void
    {
        $this->breed = $breed;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    public function getBirthdate(): Date
    {
        return $this->birthdate;
    }

    public function setBirthdate(Date $birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    public function getOwnerId(): int
    {
        return $this->ownerId;
    }

    public function setOwnerId(int $ownerId): void
    {
        $this->ownerId = $ownerId;
    }
}

