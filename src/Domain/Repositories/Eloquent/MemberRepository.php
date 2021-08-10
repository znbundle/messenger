<?php

namespace ZnBundle\Messenger\Domain\Repositories\Eloquent;

use App\Certification\Domain\Interfaces\Repositories\SignatureRepositoryInterface;
use App\Certification\Domain\Interfaces\Repositories\TemplateRepositoryInterface;
use Illuminate\Support\Collection;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnCore\Domain\Interfaces\Repository\RelationConfigInterface;
use ZnCore\Domain\Libs\Query;
use ZnCore\Domain\Enums\RelationEnum;
use ZnCore\Domain\Libs\Relation\OneToOne;
use ZnCore\Domain\Relations\relations\OneToManyRelation;
use ZnCore\Domain\Relations\relations\OneToOneRelation;
use ZnLib\Db\Capsule\Manager;
use ZnLib\Db\Base\BaseEloquentCrudRepository;
use ZnBundle\Messenger\Domain\Entities\MemberEntity;
use ZnBundle\Messenger\Domain\Interfaces\MemberRepositoryInterface;

class MemberRepository extends BaseEloquentCrudRepository implements MemberRepositoryInterface//, RelationConfigInterface
{

    protected $tableName = 'messenger_member';
    private $userRepository;

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
    
    public function relations88888888888888888888888888()
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