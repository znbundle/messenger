<?php

namespace ZnBundle\Messenger\Domain\Services;

use ZnBundle\Messenger\Domain\Interfaces\MemberRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use ZnBundle\User\Domain\Services\AuthService;
use ZnBundle\User\Domain\Services\AuthService2;
use ZnCore\Contract\Domain\Interfaces\Entities\EntityIdInterface;
use ZnCore\Domain\Libs\Query;
use ZnCore\Base\Libs\Entity\Helpers\EntityHelper;
use ZnCore\Domain\Interfaces\GetEntityClassInterface;
use ZnCore\Domain\Base\BaseCrudService;
use ZnBundle\Messenger\Domain\Entities\ChatEntity;
use ZnBundle\Messenger\Domain\Interfaces\ChatRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\ChatServiceInterface;
use ZnBundle\Messenger\Domain\Repositories\Eloquent\MemberRepository;
use ZnBundle\User\Domain\Entities\User;
use ZnBundle\User\Domain\Traits\UserAwareTrait;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
        $this->authService = $authService;
        $this->memberRepository = $memberRepository;
    }

    private function allSelfChatIds(): array
    {
        /** @var User $userEntity */
        $userEntity = $this->authService->getIdentity();
        $memberQuery = Query::forge();
        $memberQuery->where('user_id', $userEntity->getId());
        $memberCollection = $this->memberRepository->all($memberQuery);
        $chatIdArray = EntityHelper::getColumn($memberCollection, 'chatId');
        return $chatIdArray;
    }

    public function all(Query $query = null)
    {
        /** @var ChatEntity[] $collection */
        $collection = parent::all($query);
        foreach ($collection as $entity) {
            $entity->setAuthUserId($this->authService->getIdentity()->getId());
        }
        return $collection;
    }

    protected function forgeQuery(Query $query = null)
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