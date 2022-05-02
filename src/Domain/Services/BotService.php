<?php

namespace ZnBundle\Messenger\Domain\Services;

use FOS\UserBundle\Model\FosUserInterface;
use ZnBundle\Messenger\Domain\Entities\BotEntity;
use ZnBundle\Messenger\Domain\Interfaces\Repositories\BotRepositoryInterface;
use ZnBundle\Messenger\Domain\Interfaces\Services\BotServiceInterface;
use ZnBundle\User\Domain\Exceptions\UnauthorizedException;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnBundle\User\Domain\Services\AuthService;
use ZnCore\Domain\Base\BaseCrudService;

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

        $botEntity = $this->botRepository->oneByUserId($botId);
        if ($botToken != $botEntity->getToken()) {
            throw new UnauthorizedException();
        }
        $userEntity = $this->userRepository->oneById($botEntity->getUserId());
        $this->authService->authByIdentity($userEntity);
        return $botEntity;
    }
}
