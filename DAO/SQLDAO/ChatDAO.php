<?php

namespace DAO\SQLDAO;

use DAO\Connection;
use DAO\IChatDAO;
use DAO\QueryType;
use DateTime;
use Models\Chat;
use Models\Keeper;
use Models\Owner;
use Utils\MapFromSQL;
use Utils\SetterSQLData;

class ChatDAO implements IChatDAO
{
    private $connection;

    function cmp($a, $b)
    {
        return DateTime::createFromFormat("m-d-Y H:m", $a) <=> DateTime::createFromFormat("m-d-Y H:m", $b);
    }

    /**
     * @throws Exception
     */
    public function GetById(int $id): ?Chat
    {
        $this->connection = Connection::GetInstance();
        $query = 'CALL getChatById(?)';
        $parameters["id"] = $id;
        $result = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);
        $query = 'CALL getMessagesByChatId(?)';
        $messages = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);

        if (count($result) > 0) {
            $chat = MapFromSQL::MapFromChat($result[0]);
            $messagesArr = MapFromSQL::MapFromMessages($chat, $messages);

            $chat->setMessages($messagesArr);
            return $chat;
        }
        return null;
    }

    /**
     * @throws Exception
     */

    public function CreateMessage(Chat $chat): ?int
    {
        $this->connection = Connection::GetInstance();
        $query = 'CALL createMessage(?,?,?)';
        $id = $chat->getId();
        $message = end($chat->getMessages());
        $parameters = SetterSQLData::SetFromMessage($id, $message);
        $id = $this->connection->Execute($query, $parameters, QueryType::StoredProcedure);
        if (count($id) > 0) {
            return $id[0]['LAST_INSERT_ID()'];
        }
        return null;
    }

    /**
     * @throws Exception
     */
    public function MarkAsReceived(Owner|Keeper $user): void
    {
        $this->connection = Connection::GetInstance();
        $query = $user instanceof Owner ? 'CALL markKeeperMessagesAsReceived(?)' : 'CALL markOwnerMessagesAsReceived(?)';

        $parameters['id'] = $user->getId();
        $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure);
    }

    /**
     * @throws Exception
     */
    public function MarkAsRead(Owner|Keeper $user, Chat $chat): void
    {
        $this->connection = Connection::GetInstance();
        $query = $user instanceof Owner ? 'CALL markKeeperChatAsRead(?,?)' : 'CALL markOwnerChatAsRead(?,?)';
        $parameters[$user instanceof Owner ? 'ownerId' : 'keeperId'] = $user->getId();
        $parameters['chatId'] = $chat->getId();
        $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure);
    }
}
