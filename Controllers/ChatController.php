<?php

namespace Controllers;

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

    public function Index(string $id)
    {
        self::VerifyLogged();

        $keeper = new Keeper();
        $owner = new Owner();
        $session = Session::Get("owner") ?? Session::Get("keeper");

        // -------------- MOCKUP --------------
        // here should be ChatDAO.getById($id), and if it's == null then redirect to 404
        if ($session instanceof Owner) {
            $owner = $session;
            $keeper->setId(3);
            $keeper->setFirstname("John");
            $keeper->setLastname("Doe");
            $keeper->setEmail("asd@gmail.com");
            $keeper->setPassword("1234");
            $keeper->setPhone("123456789");
            $keeper->setFee(1000);
        } else if ($session instanceof Keeper) {
            $keeper = $session;
            $owner->setId(3);
            $owner->setFirstname("Matías");
            $owner->setLastname("Aceval");
            $owner->setEmail("matiasaceval@gmail.com");
            $owner->setPassword("1234");
            $owner->setPhone("123456789");
        }

        $chat = new Chat(1, $keeper, $owner, [
            new Message($owner, $keeper, "Hi", "12/23/2022 22:15", "READ"),
            new Message($keeper, $owner, "Hello", "12/23/2022 22:16", "READ"),
            new Message($owner, $keeper, "How are you?", "12/23/2022 22:17", "READ"),
            new Message($keeper, $owner, "I'm fine, and you?", "12/23/2022 22:18", "READ"),
            new Message($owner, $keeper, "I'm fine too", "12/23/2022 22:19", "RECEIVED"),
            new Message($owner, $keeper, "Can I ask you something?", "12/23/2022 22:20", "PENDING"),
            new Message($owner, $keeper, "Hey", "12/23/2022 22:21", "PENDING"),
            new Message($owner, $keeper, "Hey", "12/23/2022 22:22", "PENDING"),
            new Message($owner, $keeper, "Hey", "12/23/2022 22:23", "PENDING"),
            new Message($owner, $keeper, "Hey", "12/23/2022 22:32", "PENDING"),
        ]);
        // ------------ END OF MOCKUP ------------

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

        $session = TempValues::GetValue("session");
        $chat = TempValues::GetValue("chat");
        $otherParticipant = TempValues::GetValue("other-participant");

        $message = new Message($session, $otherParticipant, $message, date("m/d/Y H:i"), MessageState::PENDING);

        // ChatDAO.sendMessage(Message)

        // -------------- MOCKUP --------------
        $chat->addMessage($message);
        TempValues::InitValues(["chat" => $chat]);
        // ------------ END OF MOCKUP ------------

        header("Location: " . FRONT_ROOT . "Chat?id=" . $chatId);
    }

    private function VerifyLogged()
    {
        if (!LoginMiddleware::IsLogged()) {
            header("Location: " . FRONT_ROOT . "Home/Index");
            exit;
        }
    }
}
