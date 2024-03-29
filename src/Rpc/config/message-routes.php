<?php

use ZnUser\Rbac\Domain\Enums\Rbac\SystemRoleEnum;
use ZnBundle\Messenger\Rpc\Controllers\MessageController;
use ZnBundle\Messenger\Domain\Enums\Rbac\MessengerMessagePermissionEnum;

return [
    [
        'method_name' => 'messenger-message.all',
        'version' => '1',
        'is_verify_eds' => false,
        'is_verify_auth' => true,
        'permission_name' => MessengerMessagePermissionEnum::ALL,
        'handler_class' => MessageController::class,
        'handler_method' => 'all',
        'status_id' => 100,
    ],
    [
        'method_name' => 'messenger-message.oneById',
        'version' => '1',
        'is_verify_eds' => false,
        'is_verify_auth' => true,
        'permission_name' => MessengerMessagePermissionEnum::ONE,
        'handler_class' => MessageController::class,
        'handler_method' => 'oneById',
        'status_id' => 100,
    ],
    [
        'method_name' => 'messenger-message.create',
        'version' => '1',
        'is_verify_eds' => false,
        'is_verify_auth' => true,
        'permission_name' => MessengerMessagePermissionEnum::CREATE,
        'handler_class' => MessageController::class,
        'handler_method' => 'add',
        'status_id' => 100,
    ],
    [
        'method_name' => 'messenger-message.update',
        'version' => '1',
        'is_verify_eds' => false,
        'is_verify_auth' => true,
        'permission_name' => MessengerMessagePermissionEnum::UPDATE,
        'handler_class' => MessageController::class,
        'handler_method' => 'update',
        'status_id' => 100,
    ],
    [
        'method_name' => 'messenger-message.delete',
        'version' => '1',
        'is_verify_eds' => false,
        'is_verify_auth' => true,
        'permission_name' => MessengerMessagePermissionEnum::DELETE,
        'handler_class' => MessageController::class,
        'handler_method' => 'delete',
        'status_id' => 100,
    ],
    [
        'method_name' => 'messenger-message.send',
        'version' => '1',
        'is_verify_eds' => false,
        'is_verify_auth' => true,
        'permission_name' => MessengerMessagePermissionEnum::CREATE,
        'handler_class' => MessageController::class,
        'handler_method' => 'send',
        'status_id' => 100,
    ],
];