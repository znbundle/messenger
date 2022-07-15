<?php

namespace ZnBundle\Messenger\Domain\Interfaces;

use ZnDomain\Repository\Interfaces\CrudRepositoryInterface;
use ZnDomain\Query\Entities\Query;
use ZnBundle\Messenger\Domain\Entities\ChatEntity;

interface ChatRepositoryInterface extends CrudRepositoryInterface
{

    public function findOneByIdWithMembers($id, Query $query = null): ChatEntity;
}