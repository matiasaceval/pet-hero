<?php

namespace Models;

class Chat {
    private int $id;
    private Keeper $keeper;
    private Owner $owner;
    private array $messages;

    /**
     * @param int $id
     * @param Keeper $keeper
     * @param Owner $owner
     * @param array $messages
     */
    public function __construct(int $id, Keeper $keeper, Owner $owner, array $messages) {
        $this->id = $id;
        $this->keeper = $keeper;
        $this->owner = $owner;
        $this->messages = $messages;
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
     * @return Owner
     */
    public function getOwner(): Owner {
        return $this->owner;
    }

    /**
     * @param Owner $owner
     */
    public function setOwner(Owner $owner): void {
        $this->owner = $owner;
    }

    /**
     * @return array
     */
    public function getMessages(): array {
        return $this->messages;
    }

    /**
     * @param array $messages
     */
    public function setMessages(array $messages): void {
        $this->messages = $messages;
    }


}