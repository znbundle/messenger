<?php

namespace ZnBundle\Messenger\Rpc\Controllers;

use ZnBundle\Messenger\Domain\Filters\MessageFilter;
use ZnBundle\Messenger\Domain\Interfaces\Services\MessageServiceInterface;
use ZnBundle\Messenger\Domain\Interfaces\Services\TournamentServiceInterface;
use ZnLib\Rpc\Domain\Entities\RpcRequestEntity;
use ZnLib\Rpc\Domain\Entities\RpcResponseEntity;
use ZnLib\Rpc\Rpc\Base\BaseCrudRpcController;

class MessageController extends BaseCrudRpcController
{

    protected $filterModel = MessageFilter::class;

    public function __construct(MessageServiceInterface $service)
    {
        $this->service = $service;
    }

    public function send(RpcRequestEntity $requestEntity): RpcResponseEntity {
        $chatId = $requestEntity->getParamItem('chatId');
        $message = $requestEntity->getParamItem('message');
        $this->service->sendMessage($chatId, $message);
        return new RpcResponseEntity();
    }
}
