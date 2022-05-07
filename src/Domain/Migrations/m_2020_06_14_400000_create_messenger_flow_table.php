<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use ZnDatabase\Migration\Domain\Base\BaseCreateTableMigration;
use ZnDatabase\Migration\Domain\Enums\ForeignActionEnum;

class m_2020_06_14_400000_create_messenger_flow_table extends BaseCreateTableMigration
{

    protected $tableName = 'messenger_flow';
    protected $tableComment = 'Поток сообщений для каждого пользователя';

    public function tableSchema()
    {
        return function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->comment('Идентификатор');
            $table->integer('message_id')->comment('ID сообщения');
            $table->integer('chat_id')->comment('ID чата');
            $table->integer('user_id')->comment('ID владельца');
            $table->boolean('is_seen')->comment('Прочтено');

            $table->unique(['message_id', 'chat_id', 'user_id']);
            
            $this->addForeign($table, 'message_id', 'messenger_message');
            $this->addForeign($table, 'chat_id', 'messenger_chat');
            $this->addForeign($table, 'user_id', 'user_identity');
        };
    }

}
