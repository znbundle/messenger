<?php

namespace ZnBundle\Messenger\Symfony4\Api\Controllers;

use ZnBundle\User\Domain\Symfony\Authenticator;
use ZnBundle\User\Domain\Traits\AccessTrait;
use ZnCore\Domain\Query\Entities\Where;
use ZnCore\Base\Validation\Exceptions\UnprocessibleEntityException;
use ZnCore\Domain\Entity\Helpers\EntityHelper;
use ZnCore\Domain\Query\Helpers\QueryHelper;
use ZnCore\Domain\DataProvider\Libs\DataProvider;
use ZnLib\Rest\Symfony4\Base\BaseCrudApiController;
use ZnLib\Rest\Libs\Serializer\JsonRestSerializer;
use ZnBundle\Messenger\Domain\Interfaces\ChatServiceInterface;
use ZnBundle\Messenger\Domain\Interfaces\Services\MessageServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ZnLib\Web\Controller\Helpers\WebQueryHelper;

class MessageController extends BaseCrudApiController
{
    
    use AccessTrait;

    private $authenticator;
    private $chatService;

    public function __construct(ChatServiceInterface $chatService, MessageServiceInterface $messageService, Authenticator $authenticator)
    {
        $this->service = $messageService;
        $this->authenticator = $authenticator;
        $this->chatService = $chatService;
        $this->checkAuth();
    }
    
    public function allByChatId(Request $request, int $chatId = null) {
        //dd($chatId);
        $chatEntity = $this->chatService->findOneById($chatId);
        $chatCollection = $this->chatService->findAll();
        $queryParams = $request->query->all();
        unset($queryParams['Authorization']);
        $query = WebQueryHelper::getAllParams($queryParams);
        $whereChat = new Where('chat_id', $chatId);
        $query->whereNew($whereChat);
        $query->orderBy(['id'=>SORT_ASC]);
        $page = $request->get("page", 1);
        $pageSize = $request->get("per-page", 10000000000);
        // todo: make converter
        $dataProvider = new DataProvider($this->service, $query, $page, $pageSize);
        $dataProvider->getEntity()->setMaxPageSize(10000000000);

        $response = new JsonResponse;


        $serializer = new JsonRestSerializer($response);
        $serializer->serializeDataProviderEntity($dataProvider->getAll());
        return $response;

        //return new JsonResponse($dataProvider);

        /*return $this->render('@Messenger/message/index.list.html.twig', [
            'dataProviderEntity' => $dataProvider->getAll(),
            'chatEntity' => $chatEntity,
            'chatCollection' => $chatCollection,
        ]);*/
    }

    public function sendMessageFromBot(Request $request, string $bot) {
        $response = new JsonResponse;
        $serializer = new JsonRestSerializer($response);
        try {
            $this->service->sendMessageFromBot($bot, $request->query->all());
            $serializer->serialize(['ok'=>true]);
        } catch (UnprocessibleEntityException $e) {
            $errorCollection = $e->getErrorCollection();
            $serializer->serialize($errorCollection);
            $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $response;
    }
}
