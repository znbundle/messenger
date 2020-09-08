<?php

namespace ZnBundle\Messenger\Domain\Repositories\Relations;

use Illuminate\Support\Collection;
use ZnBundle\User\Domain\Repositories\Eloquent\IdentityRepository;
use ZnBundle\Article\Domain\Interfaces\CategoryRepositoryInterface;
use ZnBundle\Article\Domain\Interfaces\TagPostRepositoryInterface;
use ZnBundle\Article\Domain\Interfaces\TagRepositoryInterface;
use ZnBundle\Messenger\Domain\Repositories\Eloquent\ChatRepository;
use ZnBundle\Messenger\Domain\Repositories\Eloquent\MemberRepository;
use ZnBundle\User\Domain\Interfaces\Repositories\UserRepositoryInterface;
use ZnBundle\User\Domain\Repositories\Eloquent\UserRepository;
use ZnCore\Base\Domain\Enums\RelationEnum;
use ZnCore\Base\Domain\Interfaces\Repository\RelationConfigInterface;
use ZnCore\Base\Domain\Libs\Relation\ManyToMany;
use ZnCore\Base\Domain\Libs\Relation\OneToOne;
use ZnBundle\Messenger\Domain\Interfaces\ChatRepositoryInterface;
use Psr\Container\ContainerInterface;
use ZnCore\Db\Db\Helpers\Manager;

class MessageRelation implements RelationConfigInterface
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function primaryKey() {
        return ['id'];
    }

    public function relations()
    {
        return [
            'chat' => [
                'type' => RelationEnum::CALLBACK,
                'callback' => function (Collection $collection) {
                    $m2m = new OneToOne;
                    $m2m->foreignModel = $this->container->get(ChatRepository::class);
                    $m2m->foreignField = 'chatId';
                    $m2m->foreignContainerField = 'chat';
                    $m2m->run($collection);
                },
            ],
            'author' => [
                'type' => RelationEnum::CALLBACK,
                'callback' => function (Collection $collection) {
                    $m2m = new OneToOne;
                    $m2m->foreignModel = $this->container->get(IdentityRepository::class);
                    $m2m->foreignField = 'authorId';
                    $m2m->foreignContainerField = 'author';
                    $m2m->run($collection);
                },
            ],
        ];
    }

}