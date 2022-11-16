<?php

namespace Models;

class Reservation {
    private Keeper $keeper;
    private Pet $pet;
    private string $since;
    private string $until;
    private string $state;
    private int $price;
    private string $createdAt;
    private int $id;
    private string $payment;

    public function __construct()
    {
        $this->keeper = new Keeper();
        $this->pet = new Pet();
        $payment = "pending";
    }

    /**
     * @return Keeper
     */
    public function getKeeper(): Keeper {
        return $this->keeper;
    }

    /**
     * @param Keeper $keeper
     */
    public function setKeeper(Keeper $keeper): void {
        $this->keeper = $keeper;
    }

    /**
     * @return Pet
     */
    public function getPet(): Pet {
        return $this->pet;
    }

    /**
     * @param Pet $pet
     */
    public function setPet(Pet $pet): void {
        $this->pet = $pet;
    }

    /**
     * @return string
     */
    public function getSince(): string {
        return $this->since;
    }

    /**
     * @param string $since
     */
    public function setSince(string $since): void {
        $this->since = $since;
    }

    /**
     * @return string
     */
    public function getUntil(): string {
        return $this->until;
    }

    /**
     * @param string $until
     */
    public function setUntil(string $until): void {
        $this->until = $until;
    }

    /**
     * @return string
     */
    public function getState(): string {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state): void {
        $this->state = $state;
    }

    /**
     * @return int
     */
    public function getPrice(): int {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price): void {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     */
    public function setCreatedAt(string $createdAt): void {
        $this->createdAt = $createdAt;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPayment(): string {
        return $this->payment;
    }

    /**
     * @param string $payment
     */
    public function setPayment(string $payment): void {
        $this->payment = $payment;
    }

}