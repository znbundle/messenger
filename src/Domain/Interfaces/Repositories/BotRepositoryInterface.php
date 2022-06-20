<?php

namespace ZnBundle\Messenger\Domain\Interfaces\Repositories;

use ZnCore\Base\Libs\Repository\Interfaces\CrudRepositoryInterface;
use ZnBundle\Messenger\Domain\Entities\BotEntity;

interface BotRepositoryInterface extends CrudRepositoryInterface
{

    public function oneByUserId(int $userId): BotEntity;
}