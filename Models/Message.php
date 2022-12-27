<?php

namespace Models;

use Utils\Session;

class Message
{
    private Owner|Keeper $sender;
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
    public function __construct(Keeper|Owner $sender, string $text, string $state, string $date = "")
    {
        $this->sender = $sender;
        $this->text = $text;
        $this->state = $state;
        $this->date = $date;
    }

    /**
     * @return Keeper|Owner
     */
    public function getSender(): Keeper|Owner
    {
        return $this->sender;
    }

    /**
     * @param Keeper|Owner $sender
     */
    public function setSender(Keeper|Owner $sender): void
    {
        $this->sender = $sender;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function senderIsSession(): bool
    {
        $session = Session::Get("owner") ?? Session::Get("keeper");
        return $this->sender->getId() === $session->getId() && get_class($this->sender) === get_class($session);
    }
}
