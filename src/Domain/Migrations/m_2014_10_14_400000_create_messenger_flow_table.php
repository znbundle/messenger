<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use PhpLab\Eloquent\Migration\Base\BaseCreateTableMigration;
use PhpLab\Eloquent\Migration\Enums\ForeignActionEnum;

class m_2014_10_14_400000_create_messenger_flow_table extends BaseCreateTableMigration
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
                ->on($this->encodeTableName('fos_user'))
                ->onDelete(ForeignActionEnum::CASCADE)
                ->onUpdate(ForeignActionEnum::CASCADE);
        };
    }

}
