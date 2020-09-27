<?php

namespace ZnBundle\Messenger\Domain\Repositories\Eloquent;

use Illuminate\Support\Collection;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnCore\Domain\Libs\Query;
use ZnCore\Domain\Enums\RelationEnum;
use ZnCore\Domain\Libs\Relation\OneToOne;
use ZnCore\Db\Db\Capsule\Manager;
use ZnCore\Db\Db\Base\BaseEloquentCrudRepository;
use ZnBundle\Messenger\Domain\Entities\MemberEntity;
use ZnBundle\Messenger\Domain\Interfaces\MemberRepositoryInterface;

class MemberRepository extends BaseEloquentCrudRepository implements MemberRepositoryInterface
{

    protected $tableName = 'messenger_member';
    private $userRepository;

    public function __construct(Manager $capsule, IdentityRepositoryInterface $userRepository)
    {
        parent::__construct($capsule);
        $this->userRepository = $userRepository;
    }

    public function getEntityClass(): string
    {
        return MemberEntity::class;
    }

    protected function forgeQuery(Query $query = null)
    {
        $query = parent::forgeQuery($query);
        $query->with('user');
        return $query;
    }

    public function relations()
    {
        return [
            'user' => [
                'type' => RelationEnum::CALLBACK,
                'callback' => function (Collection $collection) {
                    $m2m = new OneToOne;
                    $m2m->foreignModel = $this->userRepository;
                    $m2m->foreignField = 'userId';
                    $m2m->foreignContainerField = 'user';
                    $m2m->run($collection);
                },
            ],
        ];
    }

}