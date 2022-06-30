<?php

namespace ZnBundle\Messenger\Symfony4\Api\Controllers;

use ZnBundle\User\Domain\Entities\User;
use ZnBundle\User\Domain\Symfony\Authenticator;
use ZnBundle\User\Domain\Traits\AccessTrait;
use ZnCore\Domain\Entity\Helpers\CollectionHelper;
use ZnCore\Domain\Entity\Helpers\EntityHelper;
use ZnCore\Domain\Query\Entities\Query;
use ZnLib\Components\Http\Enums\HttpHeaderEnum;
use ZnLib\Rest\Symfony4\Base\BaseCrudApiController;
use ZnLib\Rest\Libs\SymfonyAuthenticator;
use ZnBundle\Messenger\Domain\Interfaces\ChatServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class ChatController extends BaseCrudApiController
{

    use AccessTrait;
    
    private $authenticator;

    public function __construct(ChatServiceInterface $chatService, Authenticator $authenticator)
    {
        $this->service = $chatService;
        $this->authenticator = $authenticator;
        $this->checkAuth();
    }

    public function index(Request $request): JsonResponse
    {
        $query = new Query;
        $query->with('members.user');
        $chatCollection = $this->service->findAll($query);
        return new JsonResponse(CollectionHelper::toArray($chatCollection));
    }
}
