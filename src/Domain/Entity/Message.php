<?php

namespace Domain\Entity;

class Message
{
    private string $message_date;
    private int $author_id;
    private string $message_text;

    /**
     * @param int $author_id
     * @param string $message_text
     */
    public function __construct(int $author_id, string $message_text)
    {
        $this->message_date = date('Y-m-d H:i:s', time());
        $this->author_id = $author_id;
        $this->message_text = $message_text;
    }

    public static function withDate(int $author_id, string $message_text, string $message_date): Message
    {
        $instance = new self($author_id, $message_text);
        $instance->message_date = $message_date;
        return $instance;
    }

    /**
     * @return string
     */
    public function getMessageDate(): string
    {
        return $this->message_date;
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    /**
     * @return string
     */
    public function getMessageText(): string
    {
        return $this->message_text;
    }

    /**
     * @return array
     */
    public function getInfo(): array
    {
        return [
            "message_date" => $this->getMessageDate(),
            "author_id" => $this->getAuthorId(),
            "message_text" => $this->getMessageText(),
        ];
    }
}