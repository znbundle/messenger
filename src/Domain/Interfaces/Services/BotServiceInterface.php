<?php

namespace ZnBundle\Messenger\Domain\Interfaces\Services;

use ZnCore\Base\Libs\Service\Interfaces\CrudServiceInterface;
use ZnBundle\Messenger\Domain\Entities\BotEntity;

interface BotServiceInterface extends CrudServiceInterface
{

    public function authByToken(string $botToken): BotEntity;
}
