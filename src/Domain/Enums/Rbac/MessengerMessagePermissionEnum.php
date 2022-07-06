<?php

namespace ZnBundle\Messenger\Domain\Enums\Rbac;

use ZnCore\Enum\Interfaces\GetLabelsInterface;
use ZnCore\Contract\Rbac\Interfaces\GetRbacInheritanceInterface;
use ZnCore\Contract\Rbac\Traits\CrudRbacInheritanceTrait;
use ZnUser\Rbac\Domain\Enums\Rbac\SystemRoleEnum;

class MessengerMessagePermissionEnum implements GetLabelsInterface, GetRbacInheritanceInterface
{

    use CrudRbacInheritanceTrait;

    const CRUD = 'oMessengerMessageCrud';
    const ALL = 'oMessengerMessageAll';
    const ONE = 'oMessengerMessageOne';
    const CREATE = 'oMessengerMessageCreate';
    const UPDATE = 'oMessengerMessageUpdate';
    const DELETE = 'oMessengerMessageDelete';
    const RESTORE = 'oMessengerMessageRestore';

    public static function getLabels()
    {
        return [
            self::CRUD => 'Мессенджер. Сообщения. Полный доступ',
            self::ALL => 'Мессенджер. Сообщения. Просмотр списка',
            self::ONE => 'Мессенджер. Сообщения. Просмотр записи',
            self::CREATE => 'Мессенджер. Сообщения. Создание',
            self::UPDATE => 'Мессенджер. Сообщения. Редактирование',
            self::DELETE => 'Мессенджер. Сообщения. Удаление',
            self::RESTORE => 'Мессенджер. Сообщения. Восстановление',
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
