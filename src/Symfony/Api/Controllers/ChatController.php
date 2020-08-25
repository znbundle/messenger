<?php

namespace PhpBundle\Messenger\Symfony\Api\Controllers;

use PhpBundle\User\Domain\Entities\User;
use PhpBundle\User\Domain\Symfony\Authenticator;
use PhpBundle\User\Domain\Traits\AccessTrait;
use PhpLab\Core\Domain\Helpers\EntityHelper;
use PhpLab\Core\Domain\Libs\Query;
use PhpLab\Core\Enums\Http\HttpHeaderEnum;
use PhpLab\Rest\Base\BaseCrudApiController;
use PhpLab\Rest\Libs\SymfonyAuthenticator;
use PhpBundle\Messenger\Domain\Interfaces\ChatServiceInterface;
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
        $chatCollection = $this->service->all($query);
        return new JsonResponse(EntityHelper::collectionToArray($chatCollection));
    }
}
