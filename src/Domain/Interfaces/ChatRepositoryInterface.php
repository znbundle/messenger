<?php

namespace PhpBundle\Messenger\Domain\Interfaces;

use PhpLab\Core\Domain\Interfaces\Repository\CrudRepositoryInterface;
use PhpLab\Core\Domain\Libs\Query;
use PhpBundle\Messenger\Domain\Entities\ChatEntity;

interface ChatRepositoryInterface extends CrudRepositoryInterface
{

    public function oneByIdWithMembers($id, Query $query = null): ChatEntity;
}