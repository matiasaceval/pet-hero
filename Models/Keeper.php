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

    public function isDateAvailable(string $since, string $until): bool {
        $staySince = \DateTime::createFromFormat("m-d-Y", $this->getStay()->getSince());
        $stayUntil = \DateTime::createFromFormat("m-d-Y", $this->getStay()->getUntil());

        $sinceDate = \DateTime::createFromFormat("m-d-Y", $since);
        $untilDate = \DateTime::createFromFormat("m-d-Y", $until);

        // sinceDate must be before untilDate and there must be at least a day between them
        if ($sinceDate > $untilDate || $sinceDate == $untilDate) {
            return false;
        }
        return $sinceDate >= $staySince && $untilDate <= $stayUntil;
    }

    public function calculatePrice(string $since, string $until): int {
        /*
        * Check more about createFromFormat method
        * https://www.php.net/manual/en/datetime.createfromformat.php
        */
        $sinceDate = \DateTime::createFromFormat("m-d-Y", $since);
        $untilDate = \DateTime::createFromFormat("m-d-Y", $until);

        /* Whole price calculation
        *
         * DateTimeInterface::diff() method returns a DateInterval object representing the difference between two DateTimeInterface objects.
         * https://www.php.net/manual/en/datetime.diff.php
         *
         * DateInterval::d property returns the number of days in the interval.
         * https://www.php.net/manual/en/class.dateinterval.php
         *
         * Example:
         * $since = new DateTime("2020-01-01");
         * $until = new DateTime("2020-01-03");
         * $interval = $since->diff($until);
         * echo $interval->d; // 2
         */
        $days = $sinceDate->diff($untilDate)->d;
        return $days * $this->getFee();
    }
}