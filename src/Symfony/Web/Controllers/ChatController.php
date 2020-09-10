<?php

namespace ZnBundle\Messenger\Symfony\Web\Controllers;

use ZnCore\Domain\Helpers\EntityHelper;
use ZnCore\Domain\Helpers\QueryHelper;
use ZnCore\Domain\Libs\DataProvider;
use ZnCore\Domain\Libs\Query;
use ZnLib\Rest\Web\Controller\BaseCrudWebController;
use ZnBundle\Messenger\Domain\Entities\ChatEntity;
use ZnBundle\Messenger\Domain\Interfaces\ChatServiceInterface;
use ZnBundle\Notify\Domain\Interfaces\Services\FlashServiceInterface;
use ZnSandbox\Sandbox\Web\Symfony4\Traits\AccessTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ChatController extends AbstractController
{

    use AccessTrait;

    private $service;
    private $flashService;

    public function __construct(ChatServiceInterface $chatService, FlashServiceInterface $flashService)
    {
        $this->service = $chatService;
        $this->flashService = $flashService;
    }

    public function index(Request $request): Response
    {
        $this->checkAuth();
        $query = QueryHelper::getAllParams($request->query->all());
        //$query->with('category');

        $page = $request->get("page", 1);
        $pageSize = $request->get("per-page", 10);
        $dataProvider = new DataProvider($this->service, $query, $page, $pageSize);
        return $this->render('@Messenger/chat/index.html.twig', [
            'dataProviderEntity' => $dataProvider->getAll(),
        ]);
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
        return $this->render('@Messenger/chat/view.html.twig', [
            'chat' => $entity,
            'members' => EntityHelper::indexingCollection($entity->getMembers(), 'id'),
        ]);
    }

    public function create(Request $request): Response
    {
        $this->checkAuth();
        return $this->render('@Messenger/chat/create.html.twig');
    }

    public function update($id, Request $request): Response
    {
        $this->checkAuth();
        $query = new Query;
        //$query->with('category');
        $entity = $this->service->oneById($id, $query);
        return $this->render('@Messenger/chat/update.html.twig', [
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
