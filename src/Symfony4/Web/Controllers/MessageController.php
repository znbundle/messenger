<?php

namespace ZnBundle\Messenger\Symfony4\Web\Controllers;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use ZnBundle\Messenger\Domain\Forms\MessageForm;
use ZnBundle\Summary\Domain\Exceptions\AttemptsBlockedException;
use ZnBundle\Summary\Domain\Exceptions\AttemptsExhaustedException;
use ZnBundle\User\Domain\Enums\WebCookieEnum;
use ZnBundle\User\Domain\Forms\AuthForm;
use ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use ZnBundle\User\Symfony4\Web\Enums\WebUserEnum;
use ZnCore\Base\Enums\Http\HttpStatusCodeEnum;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Base\Libs\DotEnv\DotEnv;
use ZnCore\Domain\Entities\Query\Where;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnCore\Domain\Helpers\QueryHelper;
use ZnCore\Domain\Libs\DataProvider;
use ZnCore\Domain\Libs\Query;
use ZnLib\Rest\Web\Controller\BaseCrudWebController;
use ZnBundle\Messenger\Domain\Entities\ChatEntity;
use ZnBundle\Messenger\Domain\Interfaces\ChatServiceInterface;
use ZnBundle\Notify\Domain\Interfaces\Services\FlashServiceInterface;
use ZnBundle\Messenger\Domain\Interfaces\Services\MessageServiceInterface;
use ZnLib\Web\Symfony4\MicroApp\BaseWebController;
use ZnLib\Web\Symfony4\MicroApp\Libs\CookieValue;
use ZnLib\Web\Symfony4\MicroApp\Traits\ControllerFormTrait;
use ZnLib\Web\Symfony4\WebBundle\Traits\AccessTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends BaseWebController
{

   // use AccessTrait;
    use ControllerFormTrait;

    protected $viewsDir = __DIR__ . '/../views/message';
    protected $baseUri = '/messenger';
    //protected $layout = __DIR__ . '/../../../../../../../vendor/znsymfony/admin-panel/src/Symfony4/Admin/views/layouts/admin/main.php';
    private $service;
    private $flashService;
    private $messageService;
    private $authService;

    public function __construct(
        FormFactoryInterface $formFactory,
        CsrfTokenManagerInterface $tokenManager,

        AuthServiceInterface $authService,
        MessageServiceInterface $messageService,
        ChatServiceInterface $chatService,
        FlashServiceInterface $flashService
    )
    {
        $this->setFormFactory($formFactory);
        $this->setTokenManager($tokenManager);
        
        //dd(111);
        $this->authService = $authService;
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

        $this->layout = __DIR__ . '/../../../../../../../vendor/znsymfony/admin-panel/src/Symfony4/Admin/views/layouts/admin/blank-rjs.php';

       // dd($request);
        //$this->checkAuth();
        $query = new Query;
        $chatId = $request->query->get('chatId');

//        $queryParams = $request->query->all();
//        $queryParams = ArrayHelper::extractByKeys($queryParams, ['page', 'per-page']);

        //$query = QueryHelper::getAllParams($queryParams, $query);

        $query->where('chat_id', $chatId);
        $query->orderBy(['id'=>SORT_DESC]);
        $page = $request->get("page", 1);
        $pageSize = $request->get("per-page", 20);
        $query->page($page);
        $query->perPage($pageSize);
        $dataProvider = $this->service->getDataProvider($query);
        $messageForm = new MessageForm();
        $buildForm = $this->buildForm($messageForm, $request);
        $messageForm->setChatId($chatId);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'formView' => $buildForm->createView(),
        ]);
        /*return $this->render('@Messenger/message/index.html.twig', [
            //'chatCollection' => $chatCollection,
        ]);*/
    }

    public function messageList(Request $request): Response
    {

        $this->layout = null;

        // dd($request);
        //$this->checkAuth();
        $query = new Query;
        $chatId = $request->query->get('chatId');

//        $queryParams = $request->query->all();
//        $queryParams = ArrayHelper::extractByKeys($queryParams, ['page', 'per-page']);

        //$query = QueryHelper::getAllParams($queryParams, $query);

        $query->where('chat_id', $chatId);
        $query->orderBy(['id'=>SORT_DESC]);
        $page = $request->get("page", 1);
        $pageSize = $request->get("per-page", 20);
        $query->page($page);
        $query->perPage($pageSize);
        $dataProvider = $this->service->getDataProvider($query);
        $messageForm = new MessageForm();
        $buildForm = $this->buildForm($messageForm, $request);
        $messageForm->setChatId($chatId);
        return $this->render('_messages', [
            'collection' => $dataProvider->getCollection(),
            'myId' => $this->authService->getIdentity()->getId(),
        ]);
    }

    public function send(Request $request): Response
    {
        $messageForm = new MessageForm();
        EntityHelper::setAttributes($messageForm, $request->request->all());
        $this->service->sendMessageByForm($messageForm);
        return new Response();
    }

    public function chatList(Request $request): Response
    {
        //$this->checkAuth();
        $query = new Query;
        $query->with('members.user');
        $chatCollection = $this->chatService->all($query);
        return new JsonResponse(EntityHelper::collectionToArray($chatCollection));
    }

    public function messageList11111111(Request $request, int $chatId = null): Response
    {
        //$this->checkAuth();
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
        //$this->checkAuth();
        $this->getUser();
        $text = $request->request->get('text');
        $this->service->sendMessage($chatId, $text);
        //$postListUrl = $this->generateUrl('web_messenger_message_index', ['chatId'=>$chatId]);
        return new Response();
    }

    public function view($id, Request $request): Response
    {
        //$this->checkAuth();
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
