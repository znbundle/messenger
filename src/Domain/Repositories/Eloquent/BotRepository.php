<?php

namespace ZnBundle\Messenger\Domain\Repositories\Eloquent;

use ZnBundle\Messenger\Domain\Entities\BotEntity;
use ZnBundle\Messenger\Domain\Interfaces\Repositories\BotRepositoryInterface;
use ZnDomain\Domain\Enums\RelationEnum;
use ZnDomain\Query\Entities\Query;
use ZnDatabase\Eloquent\Domain\Base\BaseEloquentCrudRepository;

class BotRepository extends BaseEloquentCrudRepository implements BotRepositoryInterface
{

    protected $tableName = 'messenger_bot';

    public function getEntityClass(): string
    {
        return BotEntity::class;
    }

    public function findOneByUserId(int $userId): BotEntity
    {
        $query = new Query;
        $query->where('user_id', $userId);
        /** @var BotEntity $botEntity */
        return $this->findOne($query);
    }
}