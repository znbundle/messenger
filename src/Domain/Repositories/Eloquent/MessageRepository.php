<?php

namespace ZnBundle\Messenger\Domain\Repositories\Eloquent;

use Illuminate\Support\Collection;
use ZnBundle\Messenger\Domain\Entities\MessageEntity;
use ZnBundle\Messenger\Domain\Interfaces\ChatRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\Repositories\MessageRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnBundle\User\Domain\Repositories\Eloquent\IdentityRepository;
use ZnCore\Domain\Enums\RelationEnum;
use ZnCore\Domain\Relations\relations\OneToManyRelation;
use ZnCore\Domain\Relations\relations\OneToOneRelation;
use ZnDatabase\Eloquent\Domain\Base\BaseEloquentCrudRepository;
use ZnDatabase\Eloquent\Domain\Capsule\Manager;
use ZnDatabase\Base\Domain\Mappers\TimeMapper;

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

    public function relations2()
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