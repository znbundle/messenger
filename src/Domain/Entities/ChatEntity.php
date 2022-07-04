<?php

namespace ZnBundle\Messenger\Domain\Entities;

use ZnCore\Domain\Collection\Libs\Collection;
use ZnCore\Domain\Entity\Interfaces\EntityIdInterface;
use Symfony\Component\Security\Core\Security;

class ChatEntity implements EntityIdInterface
{

    private $id;
    private $title;
    private $type;
    private $messages;
    private $members;
    private $authUserId;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getLogo()
    {
        if($this->getType() == 'dialog' && $this->getMembers()) {
            foreach ($this->getMembers() as $memberEntity) {
                if($memberEntity->getUserId() != $this->getAuthUserId()) {
                    return $memberEntity->getUser()->getLogo();
                }
            }
        }
        return $this->title;
    }

    public function getTitle()
    {
        if($this->getType() == 'dialog' && $this->getMembers()) {
            foreach ($this->getMembers() as $memberEntity) {
                if($memberEntity->getUserId() != $this->getAuthUserId()) {
                    return $memberEntity->getUser()->getUsername();
                }
            }
        }
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function setMessages(Collection $messages): void
    {
        $this->messages = $messages;
    }

    /**
     * @return \ZnCore\Domain\Collection\Interfaces\Enumerable | MemberEntity[]
     */
    public function getMembers(): ?Collection
    {
        return $this->members;
    }

    public function setMembers($members): void
    {
        $this->members = $members;
    }

    public function getAuthUserId()
    {
        return $this->authUserId;
    }

    public function setAuthUserId($authUserId): void
    {
        $this->authUserId = $authUserId;
    }

}