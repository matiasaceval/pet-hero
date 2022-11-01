<?php

namespace Models;

class Reviews {

    private int $id;
    private string $comment;
    private float $rating;
    private string $date;
    private Pet $pet;


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

    public function getPet(): Pet {
        return $this->pet;
    }

    public function setPet(Pet $pet): void {
        $this->pet = $pet;
    }

}