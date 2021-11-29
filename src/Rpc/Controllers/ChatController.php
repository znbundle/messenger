<?php

namespace ZnBundle\Messenger\Rpc\Controllers;

use ZnBundle\Messenger\Domain\Interfaces\ChatServiceInterface;
use ZnBundle\Messenger\Domain\Interfaces\Services\TournamentServiceInterface;
use ZnLib\Rpc\Rpc\Base\BaseCrudRpcController;

class ChatController extends BaseCrudRpcController
{

    public function __construct(ChatServiceInterface $service)
    {
        $this->service = $service;
    }
}
