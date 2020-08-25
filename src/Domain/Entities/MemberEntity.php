<?php

namespace PhpBundle\Messenger\Domain\Entities;

use PhpBundle\User\Domain\Entities\Identity;
use PhpLab\Core\Domain\Interfaces\Entity\EntityIdInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class MemberEntity implements EntityIdInterface
{

    private $id;
    private $userId;
    private $chatId;
    private $user;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    public function getChatId()
    {
        return $this->chatId;
    }

    public function setChatId($chatId): void
    {
        $this->chatId = $chatId;
    }

    public function getUser()/*: ?UserInterface*/
    {
        return $this->user;
    }

    public function setUser(/*UserInterface*/ $user): void
    {
        $this->user = $user;
    }

}