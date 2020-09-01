<?php

namespace PhpBundle\Messenger\Domain\Services;

use PhpBundle\User\Domain\Services\AuthService;
use PhpLab\Core\Domain\Interfaces\Entity\EntityIdInterface;
use PhpLab\Core\Domain\Libs\Query;
use PhpLab\Core\Domain\Helpers\EntityHelper;
use PhpLab\Core\Domain\Interfaces\GetEntityClassInterface;
use PhpLab\Core\Domain\Base\BaseCrudService;
use PhpBundle\Messenger\Domain\Entities\ChatEntity;
use PhpBundle\Messenger\Domain\Interfaces\ChatRepositoryInterface;
use PhpBundle\Messenger\Domain\Interfaces\ChatServiceInterface;
use PhpBundle\Messenger\Domain\Repositories\Eloquent\MemberRepository;
use PhpBundle\User\Domain\Entities\User;
use PhpBundle\User\Domain\Traits\UserAwareTrait;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @property ChatRepositoryInterface | GetEntityClassInterface $repository
 */
class ChatService extends BaseCrudService implements ChatServiceInterface
{

    //use UserAwareTrait;

    private $memberRepository;
    private $authService;

    public function __construct(AuthService $authService, ChatRepositoryInterface $repository, MemberRepository $memberRepository)
    {
        $this->repository = $repository;
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

    public function create($data): EntityIdInterface
    {
        // todo: create by self user id
        return parent::create($data);
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