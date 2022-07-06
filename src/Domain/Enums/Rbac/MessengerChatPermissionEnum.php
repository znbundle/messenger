<?php

namespace ZnBundle\Messenger\Domain\Enums\Rbac;

use ZnCore\Enum\Interfaces\GetLabelsInterface;
use ZnCore\Contract\Rbac\Interfaces\GetRbacInheritanceInterface;
use ZnCore\Contract\Rbac\Traits\CrudRbacInheritanceTrait;
use ZnUser\Rbac\Domain\Enums\Rbac\SystemRoleEnum;

class MessengerChatPermissionEnum implements GetLabelsInterface, GetRbacInheritanceInterface
{

    use CrudRbacInheritanceTrait;

    const CRUD = 'oMessengerChatCrud';
    const ALL = 'oMessengerChatAll';
    const ONE = 'oMessengerChatOne';
    const CREATE = 'oMessengerChatCreate';
    const UPDATE = 'oMessengerChatUpdate';
    const DELETE = 'oMessengerChatDelete';
    const RESTORE = 'oMessengerChatRestore';

    public static function getLabels()
    {
        return [
            self::CRUD => 'Мессенджер. Чаты. Полный доступ',
            self::ALL => 'Мессенджер. Чаты. Просмотр списка',
            self::ONE => 'Мессенджер. Чаты. Просмотр записи',
            self::CREATE => 'Мессенджер. Чаты. Создание',
            self::UPDATE => 'Мессенджер. Чаты. Редактирование',
            self::DELETE => 'Мессенджер. Чаты. Удаление',
            self::RESTORE => 'Мессенджер. Чаты. Восстановление',
        ];
    }

    public static function getInheritance()
    {
        return [
            self::CRUD => [
                self::ALL,
                self::ONE,
                self::CREATE,
                self::UPDATE,
                self::DELETE,
//                self::RESTORE,
            ],
            SystemRoleEnum::GUEST => [
                self::CREATE,
            ]
        ];
    }
}
