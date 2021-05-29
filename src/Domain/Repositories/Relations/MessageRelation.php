<?php

namespace ZnBundle\Messenger\Domain\Repositories\Relations;

use Illuminate\Support\Collection;
use Psr\Container\ContainerInterface;
use ZnBundle\Messenger\Domain\Repositories\Eloquent\ChatRepository;
use ZnBundle\User\Domain\Repositories\Eloquent\IdentityRepository;
use ZnCore\Domain\Enums\RelationEnum;
use ZnCore\Domain\Interfaces\Repository\RelationConfigInterface;
use ZnCore\Domain\Libs\Relation\OneToOne;

class MessageRelation implements RelationConfigInterface
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function primaryKey()
    {
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