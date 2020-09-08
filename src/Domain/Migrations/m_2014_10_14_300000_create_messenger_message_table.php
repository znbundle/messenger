<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use ZnCore\Db\Migration\Base\BaseCreateTableMigration;
use ZnCore\Db\Migration\Enums\ForeignActionEnum;

class m_2014_10_14_300000_create_messenger_message_table extends BaseCreateTableMigration
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
                ->on($this->encodeTableName('fos_user'))
                ->onDelete(ForeignActionEnum::CASCADE)
                ->onUpdate(ForeignActionEnum::CASCADE);
            $table
                ->foreign('chat_id')
                ->references('id')
                ->on($this->encodeTableName('messenger_member'))
                ->onDelete(ForeignActionEnum::CASCADE)
                ->onUpdate(ForeignActionEnum::CASCADE);
        };
    }

}
