<?php

namespace ZnBundle\Messenger\Domain\Interfaces;

use ZnCore\Repository\Interfaces\CrudRepositoryInterface;
use ZnCore\Query\Entities\Query;
use ZnBundle\Messenger\Domain\Entities\ChatEntity;

interface ChatRepositoryInterface extends CrudRepositoryInterface
{

    public function findOneByIdWithMembers($id, Query $query = null): ChatEntity;
}