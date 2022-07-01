<?php

namespace ZnBundle\Messenger\Domain\Repositories\Eloquent;

use Illuminate\Support\Collection;
use ZnCore\Domain\Enums\RelationEnum;
use ZnCore\Domain\Entity\Interfaces\EntityIdInterface;
use ZnCore\Domain\Query\Entities\Query;
use ZnCore\Domain\Relation\Libs\Types\OneToManyRelation;
use ZnCore\Domain\Relation\Libs\Types\OneToOneRelation;
use ZnDatabase\Eloquent\Domain\Capsule\Manager;
use ZnDatabase\Eloquent\Domain\Base\BaseEloquentCrudRepository;
use ZnBundle\Messenger\Domain\Entities\ChatEntity;
use ZnBundle\Messenger\Domain\Interfaces\ChatRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\FlowRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\MemberRepositoryInterface;
use Symfony\Component\Security\Core\Security;

class ChatRepository extends BaseEloquentCrudRepository implements ChatRepositoryInterface
{

    protected $tableName = 'messenger_chat';
    private $flowRepository;
    private $memberRepository;
    //private $security;

//    public function __construct(
//        Manager $capsule,
//        /*FlowRepositoryInterface $flowRepository,*/
//        MemberRepositoryInterface $memberRepository
//        //Security $security
//    )
//    {
//        parent::__construct($capsule);
//        //$this->flowRepository = $flowRepository;
//        $this->memberRepository = $memberRepository;
//        //$this->security = $security;
//    }

    public function getEntityClass(): string
    {
        return ChatEntity::class;
    }

    public function findOneByIdWithMembers($id, Query $query = null): ChatEntity
    {
        $query = $this->forgeQuery($query);
        $query->with('members.user');
        return parent::findOneById($id, $query);
    }

    /*public function _all(Query $query = null)
    {
        $collection = parent::_all($query);
        foreach ($collection as $entity) {
            $entity->setSecurity($this->security);
        }
        return $collection;
    }*/

    public function relations()
    {
        return [
            [
                'class' => OneToManyRelation::class,
                'relationAttribute' => 'id',
                'relationEntityAttribute' => 'members',
                'foreignRepositoryClass' => MemberRepositoryInterface::class,
                'foreignAttribute' => 'chat_id',
            ],
        ];
    }
}