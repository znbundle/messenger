<?php

namespace ZnBundle\Messenger\Domain\Repositories\Eloquent;

use ZnBundle\Messenger\Domain\Entities\MessageEntity;
use ZnBundle\Messenger\Domain\Interfaces\ChatRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\Repositories\MessageRepositoryInterface;
use ZnDomain\Domain\Enums\RelationEnum;
use ZnDomain\Relation\Libs\Types\OneToOneRelation;
use ZnDomain\Repository\Mappers\TimeMapper;
use ZnDatabase\Eloquent\Domain\Base\BaseEloquentCrudRepository;
use ZnUser\Identity\Domain\Interfaces\Repositories\IdentityRepositoryInterface;

class MessageRepository extends BaseEloquentCrudRepository implements MessageRepositoryInterface
{

    protected $tableName = 'messenger_message';
    private $messageRelation;

    public function getEntityClass(): string
    {
        return MessageEntity::class;
    }

    public function mappers(): array
    {
        return [
            new TimeMapper(['created_at']),
        ];
    }

    public function relations()
    {
        return [
            [
                'class' => OneToOneRelation::class,
                'relationAttribute' => 'chat_id',
                'relationEntityAttribute' => 'chat',
                'foreignRepositoryClass' => ChatRepositoryInterface::class,
            ],
            [
                'class' => OneToOneRelation::class,
                'relationAttribute' => 'author_id',
                'relationEntityAttribute' => 'author',
                'foreignRepositoryClass' => IdentityRepositoryInterface::class,
            ],
        ];
    }
}