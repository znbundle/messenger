<?php

namespace PhpBundle\Messenger\Domain\Repositories\Eloquent;

use Illuminate\Support\Collection;
use PhpLab\Core\Domain\Enums\RelationEnum;
use PhpLab\Core\Domain\Libs\Query;
use PhpLab\Core\Domain\Libs\Relation\OneToMany;
use PhpLab\Eloquent\Db\Helpers\Manager;
use PhpLab\Eloquent\Db\Base\BaseEloquentCrudRepository;
use PhpBundle\Messenger\Domain\Entities\BotEntity;
use PhpBundle\Messenger\Domain\Entities\ChatEntity;
use PhpBundle\Messenger\Domain\Interfaces\ChatRepositoryInterface;
use PhpBundle\Messenger\Domain\Interfaces\FlowRepositoryInterface;
use PhpBundle\Messenger\Domain\Interfaces\MemberRepositoryInterface;
use PhpBundle\Messenger\Domain\Interfaces\Repositories\BotRepositoryInterface;
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