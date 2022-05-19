<?php

use ZnUser\Rbac\Domain\Enums\Rbac\SystemRoleEnum;
use ZnBundle\Messenger\Rpc\Controllers\ChatController;
use ZnBundle\Messenger\Domain\Enums\Rbac\MessengerChatPermissionEnum;

return [
    [
        'method_name' => 'messenger-chat.all',
        'version' => '1',
        'is_verify_eds' => false,
        'is_verify_auth' => true,
        'permission_name' => MessengerChatPermissionEnum::ALL,
        'handler_class' => ChatController::class,
        'handler_method' => 'all',
        'status_id' => 100,
        'title' => null,
        'description' => null,
    ],
    [
        'method_name' => 'messenger-chat.oneById',
        'version' => '1',
        'is_verify_eds' => false,
        'is_verify_auth' => true,
        'permission_name' => MessengerChatPermissionEnum::ONE,
        'handler_class' => ChatController::class,
        'handler_method' => 'oneById',
        'status_id' => 100,
        'title' => null,
        'description' => null,
    ],
    [
        'method_name' => 'messenger-chat.create',
        'version' => '1',
        'is_verify_eds' => false,
        'is_verify_auth' => true,
        'permission_name' => MessengerChatPermissionEnum::CREATE,
        'handler_class' => ChatController::class,
        'handler_method' => 'add',
        'status_id' => 100,
        'title' => null,
        'description' => null,
    ],
    [
        'method_name' => 'messenger-chat.update',
        'version' => '1',
        'is_verify_eds' => false,
        'is_verify_auth' => true,
        'permission_name' => MessengerChatPermissionEnum::UPDATE,
        'handler_class' => ChatController::class,
        'handler_method' => 'update',
        'status_id' => 100,
        'title' => null,
        'description' => null,
    ],
    [
        'method_name' => 'messenger-chat.delete',
        'version' => '1',
        'is_verify_eds' => false,
        'is_verify_auth' => true,
        'permission_name' => MessengerChatPermissionEnum::DELETE,
        'handler_class' => ChatController::class,
        'handler_method' => 'delete',
        'status_id' => 100,
        'title' => null,
        'description' => null,
    ],
];