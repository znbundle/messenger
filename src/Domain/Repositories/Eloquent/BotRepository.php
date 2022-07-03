<?php

namespace ZnBundle\Messenger\Domain\Repositories\Eloquent;

use ZnCore\Domain\Collection\Libs\Collection;
use ZnCore\Domain\Enums\RelationEnum;
use ZnCore\Domain\Query\Entities\Query;
use ZnDatabase\Eloquent\Domain\Capsule\Manager;
use ZnDatabase\Eloquent\Domain\Base\BaseEloquentCrudRepository;
use ZnBundle\Messenger\Domain\Entities\BotEntity;
use ZnBundle\Messenger\Domain\Entities\ChatEntity;
use ZnBundle\Messenger\Domain\Interfaces\ChatRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\FlowRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\MemberRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\Repositories\BotRepositoryInterface;
use Symfony\Component\Security\Core\Security;

class BotRepository extends BaseEloquentCrudRepository implements BotRepositoryInterface
{

    protected $tableName = 'messenger_bot';

    public function getEntityClass(): string
    {
        return BotEntity::class;
    }

    public function findOneByUserId(int $userId): BotEntity {
        $query = new Query;
        $query->where('user_id', $userId);
        /** @var BotEntity $botEntity */
        return $this->findOne($query);
    }
}