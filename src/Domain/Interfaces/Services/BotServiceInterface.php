<?php

namespace ZnBundle\Messenger\Domain\Interfaces\Services;

use ZnCore\Domain\Interfaces\Service\CrudServiceInterface;
use ZnBundle\Messenger\Domain\Entities\BotEntity;

interface BotServiceInterface extends CrudServiceInterface
{

    public function authByToken(string $botToken): BotEntity;
}
