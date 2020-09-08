<?php

namespace ZnBundle\Messenger\Domain\Services;

use FOS\UserBundle\Model\FosUserInterface;
use GuzzleHttp\Client;
use ZnBundle\User\Domain\Services\AuthService;
use ZnBundle\User\Domain\Exceptions\UnauthorizedException;
use ZnBundle\User\Domain\Interfaces\Repositories\UserRepositoryInterface;
use ZnCore\Base\Domain\Base\BaseCrudService;
use ZnCore\Base\Domain\Exceptions\UnprocessibleEntityException;
use ZnCore\Base\Domain\Libs\Query;
use ZnLib\Rest\Contract\Client\RestClient;
use ZnBundle\Messenger\Domain\Entities\BotEntity;
use ZnBundle\Messenger\Domain\Entities\ChatEntity;
use ZnBundle\Messenger\Domain\Entities\FlowEntity;
use ZnBundle\Messenger\Domain\Entities\MessageEntity;
use ZnBundle\Messenger\Domain\Interfaces\FlowRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\Repositories\BotRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\Repositories\MessageRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\Services\BotServiceInterface;
use ZnBundle\Messenger\Domain\Interfaces\Services\MessageServiceInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class BotService extends BaseCrudService implements BotServiceInterface
{

    private $botRepository;
    private $security;
    private $userRepository;
    private $authService;

    public function __construct(
        BotRepositoryInterface $botRepository, 
        UserRepositoryInterface $userRepository, 
        //Security $security,
        AuthService $authService
    )
    {
        $this->botRepository = $botRepository;
        //$this->security = $security;
        $this->userRepository = $userRepository;
        $this->authService = $authService;
    }

    public function authByToken(string $botToken): BotEntity {
        list($botId) = explode(':', $botToken);

        $botEntity = $this->botRepository->oneByUserId($botId);
        if($botToken != $botEntity->getToken()) {
            throw new UnauthorizedException();
        }
        $userEntity = $this->userRepository->oneById($botEntity->getUserId());
        $this->authService->authByIdentity($userEntity);
        return $botEntity;
    }
}
