<?php

namespace Models;

/**
 * @class Owner
 * @package Models
 */
class Pet {
    private int $id;
    private string $name;
    private string $species; //if it is a dog, cat, bird, etc.
    private string $breed; //if it is a dog, what breed is it? labrador, poodle, etc.
    private string $sex;
    private string $age;
    private string $image;
    private string $vaccine;
    private Owner $owner;
    private bool $active;

    //Getters and setters

    public function __construct() {
        $this->active = true;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getSpecies(): string {
        return $this->species;
    }

    public function setSpecies(string $species): void {
        $this->species = $species;
    }

    public function getBreed(): string {
        return $this->breed;
    }

    public function setBreed(string $breed): void {
        $this->breed = $breed;
    }

    public function getSex(): string {
        return $this->sex;
    }

    public function setSex(string $sex): void {
        $this->sex = $sex;
    }

    public function getAge(): string {
        return $this->age;
    }

    public function setAge(string $age): void {
        $this->age = $age;
    }

    public function getOwner(): Owner {
        return $this->owner;
    }

    public function setOwner(Owner $owner): void {
        $this->owner = $owner;
    }

    public function getImage(): string {
        return $this->image;
    }

    public function setImage(string $image): void {
        $this->image = $image;
    }

    public function getVaccine(): string {
        return $this->vaccine;
    }

    public function setVaccine(string $vaccine): void {
        $this->vaccine = $vaccine;
    }

    public function getActive(): bool {
        return $this->active;
    }

    public function setActive(bool $active): void {
        $this->active = $active;
    }
}

