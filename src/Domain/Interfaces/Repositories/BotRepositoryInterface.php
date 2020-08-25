<?php

namespace PhpBundle\Messenger\Domain\Interfaces\Repositories;

use PhpLab\Core\Domain\Interfaces\Repository\CrudRepositoryInterface;
use PhpBundle\Messenger\Domain\Entities\BotEntity;

interface BotRepositoryInterface extends CrudRepositoryInterface
{

    public function oneByUserId(int $userId): BotEntity;
}