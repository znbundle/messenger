<?php

return [
    'ZnBundle\Messenger\Domain\Interfaces\ChatServiceInterface' => 'ZnBundle\Messenger\Domain\Services\ChatService',
    'ZnBundle\Messenger\Domain\Interfaces\Services\MessageServiceInterface' => 'ZnBundle\Messenger\Domain\Services\MessageService',
    'ZnBundle\Messenger\Domain\Interfaces\ChatRepositoryInterface' => 'ZnBundle\Messenger\Domain\Repositories\Eloquent\ChatRepository',
    'ZnBundle\Messenger\Domain\Interfaces\Repositories\MessageRepositoryInterface' => 'ZnBundle\Messenger\Domain\Repositories\Eloquent\MessageRepository',
    'ZnBundle\Messenger\Domain\Interfaces\MemberRepositoryInterface' => 'ZnBundle\Messenger\Domain\Repositories\Eloquent\MemberRepository',
    //'ZnBundle\Messenger\Domain\Interfaces\MessageServiceInterface' => 'ZnBundle\Messenger\Domain\Services\ChatService',
];