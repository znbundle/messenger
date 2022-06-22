<?php

namespace ZnBundle\Messenger\Domain\Repositories\Eloquent;

use Illuminate\Support\Collection;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnCore\Domain\Query\Entities\Query;
use ZnCore\Domain\Enums\RelationEnum;
use ZnCore\Domain\Relation\Libs\Types\OneToManyRelation;
use ZnCore\Domain\Relation\Libs\Types\OneToOneRelation;
use ZnDatabase\Eloquent\Domain\Capsule\Manager;
use ZnDatabase\Eloquent\Domain\Base\BaseEloquentCrudRepository;
use ZnBundle\Messenger\Domain\Entities\MemberEntity;
use ZnBundle\Messenger\Domain\Interfaces\MemberRepositoryInterface;

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

    public function relations2()
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