<?php

namespace ZnBundle\Messenger\Domain\Interfaces\Repositories;

use ZnDomain\Repository\Interfaces\CrudRepositoryInterface;
use ZnBundle\Messenger\Domain\Entities\BotEntity;

interface BotRepositoryInterface extends CrudRepositoryInterface
{

    public function findOneByUserId(int $userId): BotEntity;
}