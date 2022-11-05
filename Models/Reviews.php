<?php

namespace Models;

class Reviews {

    private int $id;
    private string $comment;
    private float $rating;
    private string $date;
    private Reservation $reservation;

    /**
     * @return Reservation
     */
    public function getReservation(): Reservation {
        return $this->reservation;
    }

    /**
     * @param Reservation $reservation
     */
    public function setReservation(Reservation $reservation): void {
        $this->reservation = $reservation;
    }


    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getComment(): string {
        return $this->comment;
    }

    public function setComment(string $comment): void {
        $this->comment = $comment;
    }

    public function getRating(): float {
        return $this->rating;
    }

    public function setRating(float $rating): void {
        $this->rating = $rating;
    }

    public function getDate(): string {
        return $this->date;
    }

    public function setDate(string $date): void {
        $this->date = $date;
    }

}