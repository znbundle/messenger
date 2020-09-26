<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use ZnCore\Db\Migration\Base\BaseCreateTableMigration;
use ZnCore\Db\Migration\Enums\ForeignActionEnum;

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
            $table
                ->foreign('message_id')
                ->references('id')
                ->on($this->encodeTableName('messenger_message'))
                ->onDelete(ForeignActionEnum::CASCADE)
                ->onUpdate(ForeignActionEnum::CASCADE);
            $table
                ->foreign('chat_id')
                ->references('id')
                ->on($this->encodeTableName('messenger_chat'))
                ->onDelete(ForeignActionEnum::CASCADE)
                ->onUpdate(ForeignActionEnum::CASCADE);
            $table
                ->foreign('user_id')
                ->references('id')
                ->on($this->encodeTableName('user_identity'))
                ->onDelete(ForeignActionEnum::CASCADE)
                ->onUpdate(ForeignActionEnum::CASCADE);
        };
    }

}