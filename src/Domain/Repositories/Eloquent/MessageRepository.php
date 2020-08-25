<?php

namespace PhpBundle\Messenger\Domain\Repositories\Eloquent;

use Illuminate\Support\Collection;
use PhpLab\Core\Domain\Enums\RelationEnum;
use PhpLab\Core\Domain\Libs\Relation\ManyToMany;
use PhpLab\Core\Domain\Libs\Relation\OneToMany;
use PhpLab\Core\Domain\Libs\Relation\OneToOne;
use PhpLab\Eloquent\Db\Base\BaseEloquentCrudRepository;
use PhpLab\Eloquent\Db\Helpers\Manager;
use PhpBundle\Messenger\Domain\Entities\MessageEntity;
use PhpBundle\Messenger\Domain\Interfaces\ChatRepositoryInterface;
use PhpBundle\Messenger\Domain\Interfaces\Repositories\MessageRepositoryInterface;
use PhpBundle\Messenger\Domain\Repositories\Relations\MessageRelation;

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