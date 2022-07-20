<?php

use ZnBundle\Messenger\Domain\Enums\Rbac\MessengerChatPermissionEnum;
use ZnBundle\Messenger\Domain\Enums\Rbac\MessengerMessagePermissionEnum;
use ZnUser\Rbac\Domain\Enums\Rbac\SystemRoleEnum;

return [
    'roleEnums' => [

    ],
    'permissionEnums' => [
        MessengerChatPermissionEnum::class,
        MessengerMessagePermissionEnum::class,
    ],
    'inheritance' => [
        SystemRoleEnum::USER => [
            MessengerChatPermissionEnum::CRUD,
            MessengerMessagePermissionEnum::CRUD,
        ],
    ],
];
