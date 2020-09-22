<?php

namespace ZnBundle\Messenger\Yii2\Api\controllers;

use ZnBundle\User\Domain\Entities\User;
use ZnBundle\User\Domain\Symfony\Authenticator;
use ZnBundle\User\Domain\Traits\AccessTrait;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnCore\Domain\Libs\Query;
use ZnCore\Base\Enums\Http\HttpHeaderEnum;
use ZnLib\Rest\Base\BaseCrudApiController;
use ZnLib\Rest\Libs\SymfonyAuthenticator;
use ZnBundle\Messenger\Domain\Interfaces\ChatServiceInterface;
use Psr\Container\ContainerInterface;
use ZnLib\Rest\Yii2\Base\BaseCrudController;
use ZnLib\Rest\Yii2\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use ZnLib\Rest\Yii2\Base\BaseController;
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
