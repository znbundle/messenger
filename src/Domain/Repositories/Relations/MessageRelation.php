<?php

namespace PhpBundle\Messenger\Domain\Repositories\Relations;

use Illuminate\Support\Collection;
use PhpBundle\User\Domain\Repositories\Eloquent\IdentityRepository;
use PhpBundle\Article\Domain\Interfaces\CategoryRepositoryInterface;
use PhpBundle\Article\Domain\Interfaces\TagPostRepositoryInterface;
use PhpBundle\Article\Domain\Interfaces\TagRepositoryInterface;
use PhpBundle\Messenger\Domain\Repositories\Eloquent\ChatRepository;
use PhpBundle\Messenger\Domain\Repositories\Eloquent\MemberRepository;
use PhpBundle\User\Domain\Interfaces\Repositories\UserRepositoryInterface;
use PhpBundle\User\Domain\Repositories\Eloquent\UserRepository;
use PhpLab\Core\Domain\Enums\RelationEnum;
use PhpLab\Core\Domain\Interfaces\Repository\RelationConfigInterface;
use PhpLab\Core\Domain\Libs\Relation\ManyToMany;
use PhpLab\Core\Domain\Libs\Relation\OneToOne;
use PhpBundle\Messenger\Domain\Interfaces\ChatRepositoryInterface;
use Psr\Container\ContainerInterface;
use PhpLab\Eloquent\Db\Helpers\Manager;

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