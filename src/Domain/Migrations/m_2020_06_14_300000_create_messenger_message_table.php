<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use ZnDatabase\Migration\Domain\Base\BaseCreateTableMigration;
use ZnDatabase\Migration\Domain\Enums\ForeignActionEnum;

class m_2020_06_14_300000_create_messenger_message_table extends BaseCreateTableMigration
{

    protected $tableName = 'messenger_message';
    protected $tableComment = 'Содержимое сообщений';

    public function tableSchema()
    {
        return function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->comment('Идентификатор');
            $table->integer('author_id')->comment('ID автора');
            $table->integer('chat_id')->comment('ID чата');
            $table->string('text')->comment('Текст сообщения');
            $table->dateTime('created_at')->default('now()')->comment('Время создания');

            $this->addForeign($table, 'author_id', 'user_identity');
            $this->addForeign($table, 'chat_id', 'messenger_chat');

            /*$table
                ->foreign('author_id')
                ->references('id')
                ->on($this->encodeTableName('user_identity'))
                ->onDelete(ForeignActionEnum::CASCADE)
                ->onUpdate(ForeignActionEnum::CASCADE);
            $table
                ->foreign('chat_id')
                ->references('id')
                ->on($this->encodeTableName('messenger_chat'))
                ->onDelete(ForeignActionEnum::CASCADE)
                ->onUpdate(ForeignActionEnum::CASCADE);*/
        };
    }

}
