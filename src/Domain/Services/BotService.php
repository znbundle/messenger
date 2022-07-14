<?php

namespace ZnBundle\Messenger\Domain\Services;

use FOS\UserBundle\Model\FosUserInterface;
use ZnBundle\Messenger\Domain\Entities\BotEntity;
use ZnBundle\Messenger\Domain\Interfaces\Repositories\BotRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\Services\BotServiceInterface;
use ZnCore\Contract\User\Exceptions\UnauthorizedException;
use ZnUser\Identity\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnBundle\User\Domain\Services\AuthService;
use ZnDomain\Service\Base\BaseCrudService;

class BotService extends BaseCrudService implements BotServiceInterface
{

    private $botRepository;
    private $security;
    private $userRepository;
    private $authService;

    public function __construct(
        BotRepositoryInterface $botRepository,
        IdentityRepositoryInterface $userRepository,
        //Security $security,
        AuthService $authService
    )
    {
        $this->botRepository = $botRepository;
        //$this->security = $security;
        $this->userRepository = $userRepository;
        $this->authService = $authService;
    }

    public function authByToken(string $botToken): BotEntity
    {
        list($botId) = explode(':', $botToken);

        $botEntity = $this->botRepository->findOneByUserId($botId);
        if ($botToken != $botEntity->getToken()) {
            throw new UnauthorizedException();
        }
        $userEntity = $this->userRepository->findOneById($botEntity->getUserId());
        $this->authService->authByIdentity($userEntity);
        return $botEntity;
    }
}
