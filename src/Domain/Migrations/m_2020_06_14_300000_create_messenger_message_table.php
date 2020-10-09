<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use ZnLib\Migration\Domain\Base\BaseCreateTableMigration;
use ZnLib\Migration\Domain\Enums\ForeignActionEnum;

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

            $table
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
                ->onUpdate(ForeignActionEnum::CASCADE);
        };
    }

}
