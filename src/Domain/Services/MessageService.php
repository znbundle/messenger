<?php

namespace ZnBundle\Messenger\Domain\Services;

use FOS\UserBundle\Model\FosUserInterface;
use GuzzleHttp\Client;
use Symfony\Component\Security\Core\User\UserInterface;
use ZnBundle\Messenger\Domain\Entities\FlowEntity;
use ZnBundle\Messenger\Domain\Entities\MessageEntity;
use ZnBundle\Messenger\Domain\Forms\MessageForm;
use ZnBundle\Messenger\Domain\Interfaces\ChatRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\FlowRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\Repositories\MessageRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\Services\MessageServiceInterface;
use ZnUser\Identity\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnUser\Authentication\Domain\Interfaces\Services\AuthServiceInterface;
use ZnBundle\User\Domain\Services\AuthService;
use ZnBundle\User\Domain\Services\AuthService2;
use ZnDomain\Service\Base\BaseCrudService;
use ZnDomain\Validator\Helpers\ValidationHelper;
use ZnDomain\EntityManager\Interfaces\EntityManagerInterface;
use ZnDomain\Query\Entities\Query;
use ZnLib\Socket\Domain\Entities\SocketEventEntity;
use ZnLib\Socket\Domain\Libs\SocketDaemon;

class MessageService extends BaseCrudService implements MessageServiceInterface
{

    private $chatService;
    //private $security;
    private $flowRepository;
    private $botRepository;
    private $userRepository;
    private $chatRepository;
    private $botService;
    private $socketDaemon;
    private $identityRepository;

    /** @var AuthService */
    private $auth;

    public function __construct(
        EntityManagerInterface $em,
        MessageRepositoryInterface $repository,
        AuthServiceInterface $authService,
        ChatRepositoryInterface $chatRepository,
        IdentityRepositoryInterface $identityRepository,
        SocketDaemon $socketDaemon,
        FlowRepositoryInterface $flowRepository
        //BotService $botService
    )
    {
        $this->setEntityManager($em);
        $this->setRepository($repository);
        $this->auth = $authService;
        $this->chatRepository = $chatRepository;
        $this->identityRepository = $identityRepository;
        $this->socketDaemon = $socketDaemon;
        $this->flowRepository = $flowRepository;
    }

    /*public function ____construct(

        BotRepositoryInterface $botRepository,
        ChatService $chatService
        //Security $security)
(
    {

        $this->botRepository = $botRepository;
        $this->chatService = $chatService;
        //$this->security = $security;
        $this->botService = $botService;
    }*/

    public function createEntity(array $attributes = []): MessageEntity
    {
        $entity = parent::createEntity($attributes);
        $user = $this->auth->getIdentity();
        $entity->setAuthorId($user->getId());
        return $entity;
    }

    protected function forgeQuery(Query $query = null): Query
    {
        return parent::forgeQuery($query)->with('author');
    }

    public function sendMessageByForm(MessageForm $messageForm)
    {
        ValidationHelper::validateEntity($messageForm);
        $identity = $this->auth->getIdentity();
        $chatEntity = $this->chatRepository->findOneByIdWithMembers($messageForm->getChatId());
        $messageEntity = $this->createEntity();
        $messageEntity->setChatId($messageForm->getChatId());
        $messageEntity->setAuthorId($identity->getId());
        $messageEntity->setChat($chatEntity);
        $messageEntity->setText($messageForm->getText());
        $this->getRepository()->create($messageEntity);
        $this->getEntityManager()->loadEntityRelations($messageEntity, ['chat.members.user']);
        $this->sendFlow($messageEntity);
        return $messageEntity;
    }
    
    public function sendMessage(int $chatId, string $text)
    {
        $identity = $this->auth->getIdentity();
        $chatEntity = $this->chatRepository->findOneByIdWithMembers($chatId);
        $messageEntity = $this->createEntity();
        $messageEntity->setChatId($chatId);
        $messageEntity->setAuthorId($identity->getId());
        $messageEntity->setChat($chatEntity);
        $messageEntity->setText($text);
        $this->getRepository()->create($messageEntity);
        $this->sendFlow($messageEntity);
        return $messageEntity;
    }

    public function sendMessageFromBot($botToken, array $request)
    {
        $botEntity = $this->botService->authByToken($botToken);
        $chatEntity = $this->chatService->repository->findOneByIdWithMembers($request['chat_id']);

        $messageEntity = new MessageEntity;
        $messageEntity->setAuthorId($botEntity->getUserId());
        $messageEntity->setChatId($chatEntity->getId());
        $messageEntity->setChat($chatEntity);
        $messageEntity->setText($request['text']);
        $this->getRepository()->create($messageEntity);

        $this->sendFlow($messageEntity);
        return $messageEntity;
    }

    private function sendFlow(MessageEntity $messageEntity)
    {
        $chatEntity = $messageEntity->getChat();
        $author = $this->identityRepository->findOneById($messageEntity->getAuthorId());
        $messageEntity->setAuthor($author);

        foreach ($chatEntity->getMembers() as $memberEntity) {

//            $roles = $memberEntity->getUser()->getRoles();
//            if (in_array('ROLE_BOT', $roles)) {
            if(1 == 2) {
                if($messageEntity->getAuthorId() != $memberEntity->getUserId()) {
                    $this->sendMessageToBot($memberEntity->getUser(), $messageEntity);
                }
            } else {
                $flowEntity = new FlowEntity();
                $flowEntity->setChatId($chatEntity->getId());
                $flowEntity->setMessageId($messageEntity->getId());
                $flowEntity->setUserId($memberEntity->getUserId());
                $this->flowRepository->create($flowEntity);
            }


            $isMe = $memberEntity->getUserId() == $this->auth->getIdentity()->getId();
            $event = new SocketEventEntity;
            $event->setUserId($memberEntity->getUserId());
            $event->setName('sendMessage');
            $event->setData([
                'direction' => $isMe ? 'out' : 'in',
                'text' => $messageEntity->getText(),
                'chatId' => $memberEntity->getChatId(),
            ]);
            $this->socketDaemon->sendMessageToTcp($event);

        }
    }

    public function sendMessageToBot(UserInterface $botIdentity, MessageEntity $messageEntity)
    {
        $data = [
            "update_id" => $messageEntity->getId(),
            "message" => [
                "message_id" => $messageEntity->getId(),
                "from" => [
                    "id" => $messageEntity->getAuthorId(),
                    "is_bot" => false,
                    "first_name" => $messageEntity->getAuthor()->getUsername(),
                    "username" => $messageEntity->getAuthor()->getUsername(),
                    "language_code" => "ru",
                ],
                "chat" => [
                    "id" => $messageEntity->getChatId(),
                    "first_name" => $messageEntity->getChat()->getTitle(),
                    "username" => $messageEntity->getChat()->getTitle(),
                    "type" => 'private',
                ],
                "date" => time(),
                "text" => $messageEntity->getText(),
            ]
        ];

        $botEntity = $this->botRepository->findOneByUserId($botIdentity->getId());
        $client = new Client(['base_uri' => $botEntity->getHookUrl()]);
        $response = $client->post(null, [
            'json' => $data,
        ]);
    }
}
