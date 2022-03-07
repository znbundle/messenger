<?php

namespace ZnBundle\Messenger\Domain\Repositories\Eloquent;

use Illuminate\Support\Collection;
use ZnCore\Domain\Libs\Query;
use ZnCore\Domain\Enums\RelationEnum;
use ZnCore\Domain\Relations\relations\OneToManyRelation;
use ZnCore\Domain\Relations\relations\OneToOneRelation;
use ZnDatabase\Eloquent\Domain\Capsule\Manager;
use ZnDatabase\Eloquent\Domain\Base\BaseEloquentCrudRepository;
use ZnBundle\Messenger\Domain\Entities\FlowEntity;
use ZnBundle\Messenger\Domain\Interfaces\FlowRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\Repositories\MessageRepositoryInterface;

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

    protected function forgeQuery(Query $query = null)
    {
        $query = parent::forgeQuery($query);
        $query->with('message');
        return $query;
    }

    public function relations2()
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