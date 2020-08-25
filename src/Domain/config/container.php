<?php

return [
    'PhpBundle\Messenger\Domain\Interfaces\ChatServiceInterface' => 'PhpBundle\Messenger\Domain\Services\ChatService',
    'PhpBundle\Messenger\Domain\Interfaces\Services\MessageServiceInterface' => 'PhpBundle\Messenger\Domain\Services\MessageService',
    'PhpBundle\Messenger\Domain\Interfaces\ChatRepositoryInterface' => 'PhpBundle\Messenger\Domain\Repositories\Eloquent\ChatRepository',
    'PhpBundle\Messenger\Domain\Interfaces\Repositories\MessageRepositoryInterface' => 'PhpBundle\Messenger\Domain\Repositories\Eloquent\MessageRepository',
    'PhpBundle\Messenger\Domain\Interfaces\MemberRepositoryInterface' => 'PhpBundle\Messenger\Domain\Repositories\Eloquent\MemberRepository',
    //'PhpBundle\Messenger\Domain\Interfaces\MessageServiceInterface' => 'PhpBundle\Messenger\Domain\Services\ChatService',
];