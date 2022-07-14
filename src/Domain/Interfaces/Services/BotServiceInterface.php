<?php

namespace ZnBundle\Messenger\Domain\Interfaces\Services;

use ZnDomain\Service\Interfaces\CrudServiceInterface;
use ZnBundle\Messenger\Domain\Entities\BotEntity;

interface BotServiceInterface extends CrudServiceInterface
{

    public function authByToken(string $botToken): BotEntity;
}
