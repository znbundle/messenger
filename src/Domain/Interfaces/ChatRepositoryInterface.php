<?php

namespace ZnBundle\Messenger\Domain\Interfaces;

use ZnCore\Base\Libs\Repository\Interfaces\CrudRepositoryInterface;
use ZnCore\Base\Libs\Query\Entities\Query;
use ZnBundle\Messenger\Domain\Entities\ChatEntity;

interface ChatRepositoryInterface extends CrudRepositoryInterface
{

    public function oneByIdWithMembers($id, Query $query = null): ChatEntity;
}