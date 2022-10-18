<?php

namespace Models;

/**
 * @class Owner
 * @package Models
 */


class Owner {

    private int $id;
    private string $firstname;
    private string $lastname;
    private string $email;
    private string $password;
    private string $phone;

    // Getters and setters

    public function getId(): int {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getFirstname(): string {
        return $this->firstname;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }

    public function getLastname(): string {
        return $this->lastname;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getPhone(): string {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

}

?>