<?php

namespace Models;

class Keeper {
    private int $id;
    private string $firstname;
    private string $lastname;
    private string $email;
    private string $password;
    private string $phone;

    private int $fee;

    /** @var Reviews[] */
    private array $reviews;

    private Stay $stay;

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getFirstname(): string {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): void {
        $this->firstname = $firstname;
    }

    public function getLastname(): string {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void {
        $this->lastname = $lastname;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function getPhone(): string {
        return $this->phone;
    }

    public function setPhone(string $phone): void {
        $this->phone = $phone;
    }

    public function getFee(): int {
        return $this->fee;
    }

    public function setFee(int $fee): void {
        $this->fee = $fee;
    }

    public function getReviews(): array {
        return $this->reviews;
    }

    /**
     * @param Reviews[] $reviews
     * @return void
     */
    public function setReviews(array $reviews): void {
        $this->reviews = $reviews;
    }

    public function getStay(): Stay {
        return $this->stay;
    }

    public function setStay(Stay $stay): void {
        $this->stay = $stay;
    }
}