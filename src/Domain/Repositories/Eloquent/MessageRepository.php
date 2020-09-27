<?php

namespace ZnBundle\Messenger\Domain\Repositories\Eloquent;

use ZnBundle\Messenger\Domain\Entities\MessageEntity;
use ZnBundle\Messenger\Domain\Interfaces\Repositories\MessageRepositoryInterface;
use ZnBundle\Messenger\Domain\Repositories\Relations\MessageRelation;
use ZnCore\Db\Db\Base\BaseEloquentCrudRepository;
use ZnCore\Db\Db\Capsule\Manager;

class MessageRepository extends BaseEloquentCrudRepository implements MessageRepositoryInterface
{

    protected $tableName = 'messenger_message';
    private $messageRelation;

    public function __construct(Manager $capsule, MessageRelation $messageRelation)
    {
        parent::__construct($capsule);
        $this->messageRelation = $messageRelation;
    }

    public function getEntityClass(): string
    {
        return MessageEntity::class;
    }

    public function relations()
    {
        return $this->messageRelation->relations();
    }
}