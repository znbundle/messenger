<?php

namespace ZnBundle\Messenger\Domain\Entities;

use ZnDomain\Entity\Interfaces\EntityIdInterface;

class BotEntity implements EntityIdInterface
{

    private $id;
    private $userId;
    private $authKey;
    private $hookUrl;

    public function getToken()
    {
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