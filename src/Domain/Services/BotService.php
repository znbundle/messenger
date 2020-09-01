<?php

namespace PhpBundle\Messenger\Domain\Services;

use FOS\UserBundle\Model\FosUserInterface;
use GuzzleHttp\Client;
use PhpBundle\User\Domain\Services\AuthService;
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
use PhpBundle\Messenger\Domain\Interfaces\Services\BotServiceInterface;
use PhpBundle\Messenger\Domain\Interfaces\Services\MessageServiceInterface;
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
