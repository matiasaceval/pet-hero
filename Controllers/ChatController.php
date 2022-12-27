<?php

namespace Controllers;

use DAO\SQLDAO\ChatDAO as ChatDAO;
use Models\Chat;
use Models\Keeper;
use Models\Message;
use Models\MessageState;
use Models\Owner;
use Utils\LoginMiddleware;
use Utils\Session;
use Utils\TempValues;

class ChatController
{
    private ChatDAO $chatDAO;

    public function __construct()
    {
        $this->chatDAO = new ChatDAO();
    }

    public function Index(string $id)
    {
        self::VerifyLogged();

        $session = Session::Get("owner") ?? Session::Get("keeper");

        $chat = $this->chatDAO->GetById($id);
        $this->chatDAO->MarkAsRead($session, $chat);

        $otherParticipant = $chat->getOtherParticipant($session);

        TempValues::InitValues([
            "chat" => $chat,
            "session" => $session,
            "other-participant" => $otherParticipant,
            "back-page" => FRONT_ROOT . ($session instanceof \Models\Owner ? "Reservation/Reservations" : "Keeper/Reservations")
        ]);
        require_once(VIEWS_PATH . "chat.php");
    }

    public function SendMessage(string $chatId, string $message)
    {
        self::VerifyLogged();

        $session = Session::Get("owner") ?? Session::Get("keeper");
        $chat = TempValues::GetValue("chat");

        $message = new Message($session, $message, MessageState::PENDING);

        $chat->addMessage($message);
        $this->chatDAO->CreateMessage($chat);

        header("Location: " . FRONT_ROOT . "Chat?id=" . $chatId);
    }

    private function VerifyLogged()
    {
        if (!LoginMiddleware::IsLogged()) {
            header("Location: " . FRONT_ROOT . "Home/Index");
            exit;
        }
    }

    public function Refresh(string $id)
    {
        $session = Session::Get("owner") ?? Session::Get("keeper");

        $chat = $this->chatDAO->GetById($id);
        $this->chatDAO->MarkAsRead($session, $chat);

        $previousMessageFromOtherParticipant = false;
        foreach ($chat->getMessages() as $key => $row) {
            echo "<div class='message'>";
            if ($row->senderIsSession()) {
                $firstMessage = ($key == 0 || $previousMessageFromOtherParticipant) ? "first-message" : "";
                echo "<div class='message-content message-content-right text-right " . $firstMessage . "'>";
                echo "<p>" . $row->getText() . "</p>";
                echo "<div>";
                echo "<span class='time'>" . $row->getDate() . "</span>";
                if (
                    $row->getState() == MessageState::READ
                ) {
                    echo "<img width='24px' src='" . VIEWS_PATH . "/img/double-tick-blue.png' alt='Mensaje leído'>";
                } else if ($row->getState() == MessageState::RECEIVED) {
                    echo "<img width='24px' src='" . VIEWS_PATH . "/img/double-tick-gray.png' alt='Mensaje recibido'>";
                } else {
                    echo "<img width='24px' src='" . VIEWS_PATH . "/img/tick-gray.png' alt='Mensaje pendiente'>";
                }
                echo "</div>";
                echo "</div>";
                $previousMessageFromOtherParticipant = false;
            } else {
                $firstMessage = ($key == 0 || !$previousMessageFromOtherParticipant) ? "first-message" : "";
                echo "<div class='message-content message-content-left text-left " . $firstMessage . "'>";
                if (!$previousMessageFromOtherParticipant) {
                    echo "<p class='chat-other-user'>" . $row->getSender()->getFirstName() . "</p>";
                }
                echo "<p>" . $row->getText() . "</p>";
                echo "<span class='time'>" . $row->getDate() . "</span>";
                echo "</div>";
                $previousMessageFromOtherParticipant = true;
            }
            echo "</div>";
        }

        TempValues::InitValues([
            "chat" => $chat
        ]);
    }
}
