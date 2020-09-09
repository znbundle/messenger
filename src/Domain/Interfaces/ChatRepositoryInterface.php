<?php

namespace ZnBundle\Messenger\Domain\Interfaces;

use ZnCore\Domain\Interfaces\Repository\CrudRepositoryInterface;
use ZnCore\Domain\Libs\Query;
use ZnBundle\Messenger\Domain\Entities\ChatEntity;

interface ChatRepositoryInterface extends CrudRepositoryInterface
{

    public function oneByIdWithMembers($id, Query $query = null): ChatEntity;
}