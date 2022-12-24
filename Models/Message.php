<?php

namespace Models;

class Message {
    private Owner|Keeper $sender;
    private Owner|Keeper $receiver;
    private string $text;
    private string $date;
    private string $state;

    /**
     * @param Keeper|Owner $sender
     * @param Keeper|Owner $receiver
     * @param string $text
     * @param string $date
     * @param string $state
     */
    public function __construct(Keeper|Owner $sender, Keeper|Owner $receiver, string $text, string $date, string $state) {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->text = $text;
        $this->date = $date;
        $this->state = $state;
    }

    /**
     * @return Keeper|Owner
     */
    public function getSender(): Keeper|Owner {
        return $this->sender;
    }

    /**
     * @param Keeper|Owner $sender
     */
    public function setSender(Keeper|Owner $sender): void {
        $this->sender = $sender;
    }

    /**
     * @return Keeper|Owner
     */
    public function getReceiver(): Keeper|Owner {
        return $this->receiver;
    }

    /**
     * @param Keeper|Owner $receiver
     */
    public function setReceiver(Keeper|Owner $receiver): void {
        $this->receiver = $receiver;
    }

    /**
     * @return string
     */
    public function getText(): string {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getDate(): string {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate(string $date): void {
        $this->date = $date;
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


}