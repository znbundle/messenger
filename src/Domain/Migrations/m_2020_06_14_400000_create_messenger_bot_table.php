<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use PhpLab\Eloquent\Migration\Base\BaseCreateTableMigration;
use PhpLab\Eloquent\Migration\Enums\ForeignActionEnum;

class m_2020_06_14_400000_create_messenger_bot_table extends BaseCreateTableMigration
{

    protected $tableName = 'messenger_bot';
    protected $tableComment = 'Настройки ботов';

    public function tableSchema()
    {
        return function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->comment('Идентификатор');
            $table->integer('user_id')->comment('ID владельца');
            $table->string('auth_key')->comment('Ключ авторизации');
            $table->string('hook_url')->comment('URL для отправки сообщений боту');
            $table
                ->foreign('user_id')
                ->references('id')
                ->on($this->encodeTableName('fos_user'))
                ->onDelete(ForeignActionEnum::CASCADE)
                ->onUpdate(ForeignActionEnum::CASCADE);
        };
    }

}
