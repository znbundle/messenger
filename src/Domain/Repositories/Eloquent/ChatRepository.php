<?php

namespace ZnBundle\Messenger\Domain\Repositories\Eloquent;

use Illuminate\Support\Collection;
use ZnCore\Domain\Enums\RelationEnum;
use ZnCore\Domain\Interfaces\Entity\EntityIdInterface;
use ZnCore\Domain\Libs\Query;
use ZnCore\Domain\Libs\Relation\OneToMany;
use ZnCore\Db\Db\Helpers\Manager;
use ZnCore\Db\Db\Base\BaseEloquentCrudRepository;
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

    public function __construct(
        Manager $capsule, 
        /*FlowRepositoryInterface $flowRepository,*/ 
        MemberRepositoryInterface $memberRepository
        //Security $security
    )
    {
        parent::__construct($capsule);
        //$this->flowRepository = $flowRepository;
        $this->memberRepository = $memberRepository;
        //$this->security = $security;
    }

    public function getEntityClass(): string
    {
        return ChatEntity::class;
    }

    public function oneByIdWithMembers($id, Query $query = null): ChatEntity
    {
        $query = $this->forgeQuery($query);
        $query->with('members.user');
        return parent::oneById($id, $query);
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
            /*'messages' => [
                'type' => RelationEnum::CALLBACK,
                'callback' => function (Collection $collection) {
                    $m2m = new OneToMany;
                    $m2m->selfModel = $this;
                    $m2m->foreignModel = $this->flowRepository;
                    $m2m->selfField = 'chatId';
                    $m2m->foreignContainerField = 'messages';
                    $m2m->run($collection);
                },
            ],*/
            'members' => [
                'type' => RelationEnum::CALLBACK,
                'callback' => function (Collection $collection) {
                    $m2m = new OneToMany;
                    $m2m->selfModel = $this;
                    $m2m->foreignModel = $this->memberRepository;
                    $m2m->selfField = 'chatId';
                    $m2m->foreignContainerField = 'members';
                    $m2m->run($collection);
                },
            ],
        ];
    }

}