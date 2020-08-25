<?php

namespace PhpBundle\Messenger\Domain\Services;

use FOS\UserBundle\Model\FosUserInterface;
use GuzzleHttp\Client;
use Packages\User\Domain\Services\AuthService;
use PhpBundle\User\Domain\Exceptions\UnauthorizedException;
use PhpBundle\User\Domain\Interfaces\UserRepositoryInterface;
use PhpLab\Core\Domain\Base\BaseCrudService;
use PhpLab\Core\Domain\Exceptions\UnprocessibleEntityException;
use PhpLab\Core\Domain\Libs\Query;
use PhpLab\Rest\Contract\Client\RestClient;
use PhpBundle\Messenger\Domain\Entities\BotEntity;
use PhpBundle\Messenger\Domain\Entities\ChatEntity;
use PhpBundle\Messenger\Domain\Entities\FlowEntity;
use PhpBundle\Messenger\Domain\Entities\MessageEntity;
use PhpBundle\Messenger\Domain\Interfaces\FlowRepositoryInterface;
use PhpBundle\Messenger\Domain\Interfaces\Repositories\BotRepositoryInterface;
use PhpBundle\Messenger\Domain\Interfaces\Repositories\MessageRepositoryInterface;
use PhpBundle\Messenger\Domain\Interfaces\Services\MessageServiceInterface;
use PhpLab\Sandbox\Socket\Domain\Entities\SocketEventEntity;
use PhpLab\Sandbox\Socket\Domain\Enums\SocketEventEnum;
use PhpLab\Sandbox\Socket\Domain\Libs\SocketDaemon;
use Psr\Container\ContainerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class MessageService extends BaseCrudService implements MessageServiceInterface
{

    private $chatService;
    //private $security;
    private $flowRepository;
    private $botRepository;
    private $userRepository;
    private $botService;
    private $socketDaemon;

    public function __construct(
        MessageRepositoryInterface $repository
        //BotService $botService
    )
    {
        $this->repository = $repository;
    }

    public function ____construct(

        UserRepositoryInterface $userRepository,
        BotRepositoryInterface $botRepository,
        FlowRepositoryInterface $flowRepository,
        ChatService $chatService,
        //Security $security,
        AuthService $authService,
        SocketDaemon $socketDaemon)
    {

        $this->botRepository = $botRepository;
        $this->flowRepository = $flowRepository;
        $this->userRepository = $userRepository;
        $this->chatService = $chatService;
        //$this->security = $security;
        $this->auth = $authService;
        $this->botService = $botService;
        $this->socketDaemon = $socketDaemon;
    }

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

    public function sendMessageFromBot($botToken, array $request)
    {
        $botEntity = $this->botService->authByToken($botToken);
        $chatEntity = $this->chatService->repository->oneByIdWithMembers($request['chat_id']);

        $messageEntity = new MessageEntity;
        $messageEntity->setAuthorId($botEntity->getUserId());
        $messageEntity->setChatId($chatEntity->getId());
        $messageEntity->setChat($chatEntity);
        $messageEntity->setText($request['text']);
        $this->repository->create($messageEntity);
        $this->sendFlow($messageEntity);
        return $messageEntity;
    }

    public function sendMessage(int $chatId, string $text)
    {
        $chatEntity = $this->chatService->repository->oneByIdWithMembers($chatId);
        $messageEntity = $this->createEntity();
        $messageEntity->setChatId($chatId);
        $messageEntity->setChat($chatEntity);
        $messageEntity->setText($text);
        $this->repository->create($messageEntity);
        $this->sendFlow($messageEntity);
        return $messageEntity;
    }

    private function sendFlow(MessageEntity $messageEntity)
    {
        $chatEntity = $messageEntity->getChat();
        $author = $this->userRepository->oneById($messageEntity->getAuthorId());
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

            $roles = $memberEntity->getUser()->getRolesArray();
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
