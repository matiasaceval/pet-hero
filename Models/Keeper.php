<?php

namespace Models;

use DateTime;

class Keeper {
    private int $id;
    private string $firstname;
    private string $lastname;
    private string $email;
    private string $password;
    private string $phone;

    private int $fee;

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

    public function getFullname(): string {
        return $this->firstname . " " . $this->lastname;
    }

    public function isDateAvailable(string $since, string $until): bool {
        $staySince = DateTime::createFromFormat("m-d-Y", $this->getStay()->getSince());
        $stayUntil = DateTime::createFromFormat("m-d-Y", $this->getStay()->getUntil());

        $sinceDate = DateTime::createFromFormat("m-d-Y", $since);
        $untilDate = DateTime::createFromFormat("m-d-Y", $until);

        // sinceDate must be before untilDate and there must be at least a day between them
        return $sinceDate < $untilDate && $sinceDate >= $staySince && $untilDate <= $stayUntil;
    }

    public function getStay(): Stay {
        return $this->stay;
    }

    public function setStay(Stay $stay): void {
        $this->stay = $stay;
    }

    public function calculatePrice(string $since, string $until): int {
        /*
        * Check more about createFromFormat method
        * https://www.php.net/manual/en/datetime.createfromformat.php
        */
        $sinceDate = DateTime::createFromFormat("m-d-Y", $since);
        $untilDate = DateTime::createFromFormat("m-d-Y", $until);

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

    public function getFee(): int {
        return $this->fee;
    }

    public function setFee(int $fee): void {
        $this->fee = $fee;
    }

    public function getAvailableDays(array $reservations): int {
        $stay = $this->getStay();
        // filter reservations that are in the same period of the stay
        $reservations = array_filter($reservations, function ($reservation) use ($stay) {
            return $reservation->getSince() >= $stay->getSince() && $reservation->getUntil() <= $stay->getUntil();
        });
        // sort reservations by since date
        usort($reservations, function ($a, $b) {
            return $a->getSince() <=> $b->getSince();
        });
        // check if there are available days between reservations
        $availableDays = 0;

        // this array will contain the date of today and the date of the specified 'since'
        $dates = [0 => $stay->getSince(), 1 => date("m-d-Y")];
        // then we sort them
        usort($dates, function ($a, $b) {
            return $a <=> $b;
        });

        // and we choose the 'max' one of them. This will be the date from which we will start counting the available days
        // with this comparison we avoid counting the days that have already passed
        $lastUntil = $dates[count($dates) - 1];
        foreach ($reservations as $reservation) {

            $lastUntil = DateTime::createFromFormat("m-d-Y", $lastUntil);
            $days = DateTime::createFromFormat("m-d-Y", $reservation->getSince())->diff($lastUntil)->days;
            $availableDays += $days;

            $lastUntil->modify("+2 day");
            if ($lastUntil->format("m-d-Y") == $reservation->getSince()) {
                $availableDays -= $days;
            }

            $availableDays = $availableDays <= 1 ? 0 : $availableDays;
            $lastUntil = $reservation->getUntil();
        }
        $lastUntil = DateTime::createFromFormat("m-d-Y", $lastUntil);
        $availableDays += DateTime::createFromFormat("m-d-Y", $stay->getUntil())->diff($lastUntil)->days;
        $availableDays = $availableDays <= 1 ? 0 : $availableDays;
        return $availableDays;
    }
}