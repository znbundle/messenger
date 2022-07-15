<?php

namespace ZnBundle\Messenger\Domain\Repositories\Eloquent;

use ZnBundle\Messenger\Domain\Entities\MemberEntity;
use ZnBundle\Messenger\Domain\Interfaces\MemberRepositoryInterface;
use ZnDomain\Domain\Enums\RelationEnum;
use ZnDomain\Query\Entities\Query;
use ZnDomain\Relation\Libs\Types\OneToOneRelation;
use ZnDatabase\Eloquent\Domain\Base\BaseEloquentCrudRepository;
use ZnUser\Identity\Domain\Interfaces\Repositories\IdentityRepositoryInterface;

class MemberRepository extends BaseEloquentCrudRepository implements MemberRepositoryInterface
{

    protected $tableName = 'messenger_member';
    private $userRepository;

    public function getEntityClass(): string
    {
        return MemberEntity::class;
    }

    protected function forgeQuery(Query $query = null): Query
    {
        $query = parent::forgeQuery($query);
        $query->with('user');
        return $query;
    }

    public function relations()
    {
        return [
            [
                'class' => OneToOneRelation::class,
                'relationAttribute' => 'user_id',
                'relationEntityAttribute' => 'user',
                'foreignRepositoryClass' => IdentityRepositoryInterface::class,
            ],
        ];
    }
}