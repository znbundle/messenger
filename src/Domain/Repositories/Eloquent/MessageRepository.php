<?php

namespace ZnBundle\Messenger\Domain\Repositories\Eloquent;

use App\Certification\Domain\Interfaces\Repositories\SignatureRepositoryInterface;
use App\Certification\Domain\Interfaces\Repositories\TemplateRepositoryInterface;
use Illuminate\Support\Collection;
use ZnBundle\Messenger\Domain\Entities\MessageEntity;
use ZnBundle\Messenger\Domain\Interfaces\ChatRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\Repositories\MessageRepositoryInterface;
use ZnBundle\Messenger\Domain\Repositories\Relations\MessageRelation;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnBundle\User\Domain\Repositories\Eloquent\IdentityRepository;
use ZnCore\Domain\Enums\RelationEnum;
use ZnCore\Domain\Interfaces\Repository\RelationConfigInterface;
use ZnCore\Domain\Libs\Relation\OneToOne;
use ZnCore\Domain\Relations\relations\OneToManyRelation;
use ZnCore\Domain\Relations\relations\OneToOneRelation;
use ZnLib\Db\Base\BaseEloquentCrudRepository;
use ZnLib\Db\Capsule\Manager;
use ZnLib\Db\Mappers\TimeMapper;

class MessageRepository extends BaseEloquentCrudRepository implements MessageRepositoryInterface//, RelationConfigInterface
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
    
    public function relations888888888888888()
    {
        return $this->messageRelation->relations();
    }
}