<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use ZnDatabase\Migration\Domain\Base\BaseCreateTableMigration;

class m_2020_06_14_100000_create_messenger_chat_table extends BaseCreateTableMigration
{

    protected $tableName = 'messenger_chat';
    protected $tableComment = 'Чат';

    public function tableSchema()
    {
        return function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->comment('Идентификатор');
            $table->enum('type', ['dialog', 'chat', 'channel'])->comment('Тип чата');
            $table->string('title')->comment('Название чата');
        };
    }

}

