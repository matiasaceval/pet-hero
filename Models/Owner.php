<?php

namespace Models;

class Owner {

    private $id;
    private $firstname;
    private $lastname;
    private $email;
    private $password;
    private $phone;
    private $idKepeerXOwner;
    

    // Getters and setters

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function getIdKepeerXOwner() {
        return $this->idKepeerXOwner;
    }

    public function setIdKepeerXOwner($idKepeerXOwner) {
        $this->idKepeerXOwner = $idKepeerXOwner;
    }

}