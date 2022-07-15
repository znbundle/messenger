<?php

namespace ZnBundle\Messenger\Domain\Repositories\Eloquent;

use ZnBundle\Messenger\Domain\Entities\FlowEntity;
use ZnBundle\Messenger\Domain\Interfaces\FlowRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\Repositories\MessageRepositoryInterface;
use ZnDomain\Domain\Enums\RelationEnum;
use ZnDomain\Query\Entities\Query;
use ZnDomain\Relation\Libs\Types\OneToOneRelation;
use ZnDatabase\Eloquent\Domain\Base\BaseEloquentCrudRepository;

class FlowRepository extends BaseEloquentCrudRepository implements FlowRepositoryInterface
{

    protected $tableName = 'messenger_flow';
    private $messageRepository;

    /*public function __construct(Manager $capsule, MessageRepositoryInterface $messageRepository)
    {
        parent::__construct($capsule);
        $this->messageRepository = $messageRepository;
    }*/

    public function getEntityClass(): string
    {
        return FlowEntity::class;
    }

    protected function forgeQuery(Query $query = null): Query
    {
        $query = parent::forgeQuery($query);
        $query->with('message');
        return $query;
    }

    public function relations()
    {
        return [
            [
                'class' => OneToOneRelation::class,
                'relationAttribute' => 'message_id',
                'relationEntityAttribute' => 'message',
                'foreignRepositoryClass' => MessageRepositoryInterface::class,
            ],
        ];
    }
}