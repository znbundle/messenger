<?php

namespace ZnBundle\Messenger\Domain\Repositories\Eloquent;

use Illuminate\Support\Collection;
use ZnCore\Domain\Enums\RelationEnum;
use ZnCore\Domain\Libs\Query;
use ZnCore\Domain\Libs\Relation\OneToMany;
use ZnCore\Db\Db\Helpers\Manager;
use ZnCore\Db\Db\Base\BaseEloquentCrudRepository;
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

    public function oneByUserId(int $userId): BotEntity {
        $query = new Query;
        $query->where('user_id', $userId);
        /** @var BotEntity $botEntity */
        return $this->one($query);
    }
}