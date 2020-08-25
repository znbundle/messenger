<?php

namespace PhpBundle\Messenger\Domain\Entities;

use Illuminate\Support\Collection;
use PhpLab\Core\Domain\Interfaces\Entity\EntityIdInterface;
use Symfony\Component\Security\Core\Security;

class BotEntity implements EntityIdInterface
{

    private $id;
    private $userId;
    private $authKey;
    private $hookUrl;

    public function getToken() {
        return $this->getUserId() . ':' . $this->getAuthKey();
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
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @param mixed $authKey
     */
    public function setAuthKey($authKey): void
    {
        $this->authKey = $authKey;
    }

    /**
     * @return mixed
     */
    public function getHookUrl()
    {
        return $this->hookUrl;
    }

    /**
     * @param mixed $hookUrl
     */
    public function setHookUrl($hookUrl): void
    {
        $this->hookUrl = $hookUrl;
    }

}