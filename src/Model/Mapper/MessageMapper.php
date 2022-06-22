<?php

namespace Model\Mapper;

use Model\Entity\Message;

class MessageMapper
{
    private const MAP = [
        "message_date" => [
            "type" => "string",
            "nullable" => false,
        ],
        "author_id" => [
            "type" => "integer",
            "nullable" => false,
        ],
        "message_text" => [
            "type" => "string",
            "nullable" => false,
        ],
    ];

    public function map(array $row): ?Message
    {
        $result = null;

        if (!(isset($row["message_date"]) && empty($row["message_date"])
            || isset($row["author_id"]) && empty($row["author_id"])
            || isset($row["message_text"]) && empty($row["message_text"]))) {
            $result = Message::withDate($row["author_id"], $row["message_text"], $row["message_date"]);
        }

        return $result;
    }
}