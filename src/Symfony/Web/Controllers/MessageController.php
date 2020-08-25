<?php

namespace PhpBundle\Messenger\Symfony\Web\Controllers;

use PhpLab\Core\Domain\Entities\Query\Where;
use PhpLab\Core\Domain\Helpers\EntityHelper;
use PhpLab\Core\Domain\Helpers\QueryHelper;
use PhpLab\Core\Domain\Libs\DataProvider;
use PhpLab\Core\Domain\Libs\Query;
use PhpLab\Rest\Web\Controller\BaseCrudWebController;
use PhpBundle\Messenger\Domain\Entities\ChatEntity;
use PhpBundle\Messenger\Domain\Interfaces\ChatServiceInterface;
use PhpBundle\Notify\Domain\Interfaces\Services\FlashServiceInterface;
use PhpBundle\Messenger\Domain\Interfaces\Services\MessageServiceInterface;
use PhpLab\Web\Traits\AccessTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends AbstractController
{

    use AccessTrait;

    private $service;
    private $flashService;
    private $messageService;

    public function __construct(MessageServiceInterface $messageService, ChatServiceInterface $chatService, FlashServiceInterface $flashService)
    {
        $this->chatService = $chatService;
        $this->flashService = $flashService;
        $this->service = $messageService;
    }

    /*public function index(Request $request, int $chatId): Response
    {
        $this->checkAuth();
        $chatEntity = $this->chatService->oneById($chatId);
        $query = new Query;
        $query->with('members.user');
        $query->limit(2);
        $chatCollection = $this->chatService->all($query);
        $query = QueryHelper::getAllParams($request->query->all());
        $whereChat = new Where('chat_id', $chatId);
        $query->whereNew($whereChat);
        $query->orderBy(['id'=>SORT_ASC]);
        $page = $request->get("page", 1);
        $pageSize = $request->get("per-page", 10000000000);
        // todo: make converter
        $dataProvider = new DataProvider($this->service, $query, $page, $pageSize);
        $dataProvider->getEntity()->setMaxPageSize(10000000000);
        return $this->render('@Messenger/message/index.html.twig', [
            //'dataProviderEntity' => $dataProvider->getAll(),
            'chatEntity' => $chatEntity,
            'chatCollection' => $chatCollection,
        ]);
    }*/

    public function index(Request $request): Response
    {
        $this->checkAuth();
        //$query = new Query;
        //$query->with('members.user');
        //$query->limit(2);
        //$chatCollection = $this->chatService->all($query);
        return $this->render('@Messenger/message/index.html.twig', [
            //'chatCollection' => $chatCollection,
        ]);
    }

    public function chatList(Request $request): Response
    {
        $this->checkAuth();
        $query = new Query;
        $query->with('members.user');
        $chatCollection = $this->chatService->all($query);
        return new JsonResponse(EntityHelper::collectionToArray($chatCollection));
    }

    public function messageList(Request $request, int $chatId = null): Response
    {
        $this->checkAuth();
        $chatEntity = $this->chatService->oneById($chatId);
        $chatCollection = $this->chatService->all();
        $query = QueryHelper::getAllParams($request->query->all());
        $whereChat = new Where('chat_id', $chatId);
        $query->whereNew($whereChat);
        $query->orderBy(['id'=>SORT_ASC]);
        $page = $request->get("page", 1);
        $pageSize = $request->get("per-page", 10000000000);
        // todo: make converter
        $dataProvider = new DataProvider($this->service, $query, $page, $pageSize);
        $dataProvider->getEntity()->setMaxPageSize(10000000000);
        return $this->render('@Messenger/message/index.list.html.twig', [
            'dataProviderEntity' => $dataProvider->getAll(),
            'chatEntity' => $chatEntity,
            'chatCollection' => $chatCollection,
        ]);
    }

    public function create(Request $request, int $chatId): Response
    {
        $this->checkAuth();
        $this->getUser();
        $text = $request->request->get('text');
        $this->service->sendMessage($chatId, $text);
        //$postListUrl = $this->generateUrl('web_messenger_message_index', ['chatId'=>$chatId]);
        return new Response();
    }

    public function view($id, Request $request): Response
    {
        $this->checkAuth();
        $query = new Query;
        $query->with('messages');
        $query->with('members');
        /** @var ChatEntity $entity */
        $entity = $this->service->oneById($id, $query);
        //dd($entity);
        return $this->render('@Messenger/message/view.html.twig', [
            'chat' => $entity,
            'members' => EntityHelper::indexingCollection($entity->getMembers(), 'id'),
        ]);
    }

    public function update($id, Request $request): Response
    {
        $this->checkAuth();
        $query = new Query;
        //$query->with('category');
        $entity = $this->service->oneById($id, $query);
        return $this->render('@Messenger/message/update.html.twig', [
            'chat' => $entity,
        ]);
    }

    public function delete($id, Request $request): Response
    {
        $this->checkAuth();
        $this->service->deleteById($id);
        $chatListUrl = $this->generateUrl('web_messenger_chat_index');
        $this->flashService->addSuccess('Chat deleted!');
        return $this->redirect($chatListUrl);
    }

}
