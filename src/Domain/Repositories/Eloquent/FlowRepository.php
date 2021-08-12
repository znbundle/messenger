<?php

namespace ZnBundle\Messenger\Domain\Repositories\Eloquent;

use Illuminate\Support\Collection;
use ZnCore\Domain\Interfaces\Repository\RelationConfigInterface;
use ZnCore\Domain\Libs\Query;
use ZnCore\Domain\Enums\RelationEnum;
use ZnCore\Domain\Libs\Relation\OneToOne;
use ZnCore\Domain\Relations\relations\OneToManyRelation;
use ZnCore\Domain\Relations\relations\OneToOneRelation;
use ZnLib\Db\Capsule\Manager;
use ZnLib\Db\Base\BaseEloquentCrudRepository;
use ZnBundle\Messenger\Domain\Entities\FlowEntity;
use ZnBundle\Messenger\Domain\Interfaces\FlowRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\Repositories\MessageRepositoryInterface;

class FlowRepository extends BaseEloquentCrudRepository implements FlowRepositoryInterface//, RelationConfigInterface
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

    public function relations222222222222()
    {
        return [
            'message' => [
                'type' => RelationEnum::CALLBACK,
                'callback' => function (Collection $collection) {
                    $m2m = new OneToOne;
                    //$m2m->selfModel = $this;
                    $m2m->foreignModel = $this->messageRepository;
                    $m2m->foreignField = 'contentId';
                    $m2m->foreignContainerField = 'message';
                    $m2m->run($collection);
                },
            ],
        ];
    }

}