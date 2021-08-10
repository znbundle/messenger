<?php

namespace ZnBundle\Messenger\Domain\Services;

use FOS\UserBundle\Model\FosUserInterface;
use GuzzleHttp\Client;
use ZnBundle\Messenger\Domain\Interfaces\ChatRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use ZnBundle\User\Domain\Services\AuthService;
use ZnBundle\User\Domain\Exceptions\UnauthorizedException;
use ZnBundle\User\Domain\Interfaces\Repositories\UserRepositoryInterface;
use ZnBundle\User\Domain\Services\AuthService2;
use ZnCore\Domain\Base\BaseCrudService;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnCore\Domain\Libs\Query;
use ZnLib\Rest\Contract\Client\RestClient;
use ZnBundle\Messenger\Domain\Entities\BotEntity;
use ZnBundle\Messenger\Domain\Entities\ChatEntity;
use ZnBundle\Messenger\Domain\Entities\FlowEntity;
use ZnBundle\Messenger\Domain\Entities\MessageEntity;
use ZnBundle\Messenger\Domain\Interfaces\FlowRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\Repositories\BotRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\Repositories\MessageRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\Services\MessageServiceInterface;
use ZnLib\Socket\Domain\Entities\SocketEventEntity;
use ZnLib\Socket\Domain\Enums\SocketEventEnum;
use ZnLib\Socket\Domain\Libs\SocketDaemon;
use Psr\Container\ContainerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

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
        MessageRepositoryInterface $repository,
        AuthServiceInterface $authService,
        ChatRepositoryInterface $chatRepository,
        IdentityRepositoryInterface $identityRepository,
        SocketDaemon $socketDaemon,
        FlowRepositoryInterface $flowRepository
        //BotService $botService
    )
    {
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

    protected function forgeQuery(Query $query = null)
    {
        return parent::forgeQuery($query)->with('author');
    }

    public function sendMessage(int $chatId, string $text)
    {
        $identity = $this->auth->getIdentity();
        $chatEntity = $this->chatRepository->oneByIdWithMembers($chatId);
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
        $chatEntity = $this->chatService->repository->oneByIdWithMembers($request['chat_id']);

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
        $author = $this->identityRepository->oneById($messageEntity->getAuthorId());
        $messageEntity->setAuthor($author);

        foreach ($chatEntity->getMembers() as $memberEntity) {

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

            $roles = $memberEntity->getUser()->getRoles();
            if (in_array('ROLE_BOT', $roles)) {
                if($messageEntity->getAuthorId() != $memberEntity->getUserId()) {
                    $this->sendMessageToBot($memberEntity->getUser(), $messageEntity);
                }
            } else {
                $flowEntity = new FlowEntity;
                $flowEntity->setChatId($chatEntity->getId());
                $flowEntity->setMessageId($messageEntity->getId());
                $flowEntity->setUserId($memberEntity->getUserId());
                $this->flowRepository->create($flowEntity);
            }
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

        $botEntity = $this->botRepository->oneByUserId($botIdentity->getId());
        $client = new Client(['base_uri' => $botEntity->getHookUrl()]);
        $response = $client->post(null, [
            'json' => $data,
        ]);
    }
}
