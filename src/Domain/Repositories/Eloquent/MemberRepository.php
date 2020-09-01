<?php

namespace PhpBundle\Messenger\Domain\Repositories\Eloquent;

use Illuminate\Support\Collection;
use PhpBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use PhpLab\Core\Domain\Libs\Query;
use PhpLab\Core\Domain\Enums\RelationEnum;
use PhpLab\Core\Domain\Libs\Relation\OneToOne;
use PhpLab\Eloquent\Db\Helpers\Manager;
use PhpLab\Eloquent\Db\Base\BaseEloquentCrudRepository;
use PhpBundle\Messenger\Domain\Entities\MemberEntity;
use PhpBundle\Messenger\Domain\Interfaces\MemberRepositoryInterface;

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