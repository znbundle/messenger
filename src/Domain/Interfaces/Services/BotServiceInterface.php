<?php

namespace PhpBundle\Messenger\Domain\Interfaces\Services;

use PhpLab\Core\Domain\Interfaces\Service\CrudServiceInterface;
use PhpBundle\Messenger\Domain\Entities\BotEntity;

interface BotServiceInterface extends CrudServiceInterface
{

    public function authByToken(string $botToken): BotEntity;
}
