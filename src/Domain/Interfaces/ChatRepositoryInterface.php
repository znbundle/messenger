<?php

namespace ZnBundle\Messenger\Domain\Interfaces;

use ZnCore\Domain\Repository\Interfaces\CrudRepositoryInterface;
use ZnCore\Domain\Query\Entities\Query;
use ZnBundle\Messenger\Domain\Entities\ChatEntity;

interface ChatRepositoryInterface extends CrudRepositoryInterface
{

    public function oneByIdWithMembers($id, Query $query = null): ChatEntity;
}