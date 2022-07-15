<?php

namespace ZnBundle\Messenger\Domain\Entities;

use ZnCore\Contract\User\Interfaces\Entities\IdentityEntityInterface;
use ZnDomain\Entity\Interfaces\EntityIdInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class MessageEntity implements EntityIdInterface
{

    private $id;
    private $text;
    private $authorId;
    private $chatId;
    private $author;
    private $chat;
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * @param mixed $authorId
     */
    public function setAuthorId($authorId): void
    {
        $this->authorId = $authorId;
    }

    /**
     * @return mixed
     */
    public function getChatId()
    {
        return $this->chatId;
    }

    /**
     * @param mixed $chatId
     */
    public function setChatId($chatId): void
    {
        $this->chatId = $chatId;
    }

    public function getAuthor(): ?IdentityEntityInterface
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor(IdentityEntityInterface $author): void
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getChat(): ?ChatEntity
    {
        return $this->chat;
    }

    /**
     * @param mixed $chat
     */
    public function setChat(ChatEntity $chat): void
    {
        $this->chat = $chat;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }
    
}