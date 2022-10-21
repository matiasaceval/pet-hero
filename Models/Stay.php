<?php

namespace Models;

class Stay {

    private string $since;
    private string $until;
    private int $id;

    public function getSince(): string {
        return $this->since;
    }

    public function setSince(string $since): void {
        $this->since = $since;
    }

    public function getUntil(): string {
        return $this->until;
    }

    public function setUntil(string $until): void {
        $this->until = $until;
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }
}