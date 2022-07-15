<?php

namespace ZnBundle\Messenger\Domain\Entities;

use ZnDomain\Entity\Interfaces\EntityIdInterface;

class FlowEntity implements EntityIdInterface
{

    private $id;
    private $messageId;
    private $chatId;
    private $userId;
    private $isSeen = false;
    //private $text;
    private $message;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getMessageId()
    {
        return $this->messageId;
    }

    public function setMessageId($messageId): void
    {
        $this->messageId = $messageId;
    }

    public function getChatId()
    {
        return $this->chatId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    public function getisSeen()
    {
        return $this->isSeen;
    }

    public function setIsSeen($isSeen): void
    {
        $this->isSeen = $isSeen;
    }

    public function setChatId($chatId): void
    {
        $this->chatId = $chatId;
    }

    /*public function getText()
    {
        return $this->getMessage()->getText();
    }*/

    public function getMessage(): ?MessageEntity
    {
        return $this->message;
    }

    public function setMessage(MessageEntity $message): void
    {
        $this->message = $message;
    }

}