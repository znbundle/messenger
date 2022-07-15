<?php

namespace ZnBundle\Messenger\Domain\Services;

use ZnCore\Collection\Interfaces\Enumerable;
use ZnBundle\Messenger\Domain\Entities\ChatEntity;
use ZnBundle\Messenger\Domain\Interfaces\ChatRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\ChatServiceInterface;
use ZnBundle\Messenger\Domain\Interfaces\MemberRepositoryInterface;
use ZnBundle\User\Domain\Entities\User;
use ZnBundle\User\Domain\Services\AuthService;
use ZnBundle\User\Domain\Services\AuthService2;
use ZnDomain\Domain\Interfaces\GetEntityClassInterface;
use ZnCore\Collection\Helpers\CollectionHelper;
use ZnDomain\Entity\Interfaces\EntityIdInterface;
use ZnDomain\Query\Entities\Query;
use ZnDomain\Service\Base\BaseCrudService;
use ZnUser\Authentication\Domain\Interfaces\Services\AuthServiceInterface;

/**
 * @property ChatRepositoryInterface | GetEntityClassInterface $repository
 */
class ChatService extends BaseCrudService implements ChatServiceInterface
{

    //use UserAwareTrait;

    private $memberRepository;
    private $authService;

    public function __construct(AuthServiceInterface $authService, ChatRepositoryInterface $repository, MemberRepositoryInterface $memberRepository)
    {
        $this->setRepository($repository);
        // todo: заменить на Security
        $this->authService = $authService;
        $this->memberRepository = $memberRepository;
    }

    private function allSelfChatIds(): array
    {
        /** @var User $userEntity */
        $userEntity = $this->authService->getIdentity();
        $memberQuery = Query::forge();
        $memberQuery->where('user_id', $userEntity->getId());
        $memberCollection = $this->memberRepository->findAll($memberQuery);
        $chatIdArray = CollectionHelper::getColumn($memberCollection, 'chatId');
        return $chatIdArray;
    }

    public function findAll(Query $query = null): Enumerable
    {
        /** @var ChatEntity[] $collection */
        $collection = parent::findAll($query);
        foreach ($collection as $entity) {
            $entity->setAuthUserId($this->authService->getIdentity()->getId());
        }
        return $collection;
    }

    protected function forgeQuery(Query $query = null): Query
    {
        $query = parent::forgeQuery($query);
        $chatIdArray = $this->allSelfChatIds();
        $query->where('id', $chatIdArray);
        return $query;
    }

    public function create($attributes): EntityIdInterface
    {
        // todo: create by self user id
        return parent::create($attributes);
    }

    public function updateById($id, $data)
    {
        // todo:
        return parent::updateById($id, $data);
    }

    public function deleteById($id)
    {
        // todo:
        return parent::deleteById($id);
    }

}