<?php

namespace PhpBundle\Messenger\Yii\Api\controllers;

use PhpBundle\User\Domain\Entities\User;
use PhpBundle\User\Domain\Symfony\Authenticator;
use PhpBundle\User\Domain\Traits\AccessTrait;
use PhpLab\Core\Domain\Helpers\EntityHelper;
use PhpLab\Core\Domain\Libs\Query;
use PhpLab\Core\Enums\Http\HttpHeaderEnum;
use PhpLab\Rest\Base\BaseCrudApiController;
use PhpLab\Rest\Libs\SymfonyAuthenticator;
use PhpBundle\Messenger\Domain\Interfaces\ChatServiceInterface;
use Psr\Container\ContainerInterface;
use RocketLab\Bundle\Rest\Base\BaseCrudController;
use RocketLab\Bundle\Rest\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use RocketLab\Bundle\Rest\Base\BaseController;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class ChatController extends BaseCrudController
{

    //use AccessTrait;

    public function __construct(
        string $id,
        Module $module,
        array $config = [],
        // ChatServiceInterface $chatService,
        ContainerInterface $container
    )
    {
        parent::__construct($id, $module, $config);
        $this->serializer = [
            'class' => Serializer::class,
            'normalizer' => $this->createNormalizer(),
            'context' => $this->normalizerContext(),
        ];
        //$this->service = $chatService;
        $this->service = $container->get(ChatServiceInterface::class);
    }

    /*public function index(Request $request): JsonResponse
    {
        $query = new Query;
        $query->with('members.user');
        $chatCollection = $this->service->all($query);
        return new JsonResponse(EntityHelper::collectionToArray($chatCollection));
    }*/
}
