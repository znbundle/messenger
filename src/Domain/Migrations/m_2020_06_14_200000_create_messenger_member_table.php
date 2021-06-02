<?php

namespace Migrations;

use Illuminate\Database\Schema\Blueprint;
use ZnLib\Migration\Domain\Base\BaseCreateTableMigration;
use ZnLib\Migration\Domain\Enums\ForeignActionEnum;

class m_2020_06_14_200000_create_messenger_member_table extends BaseCreateTableMigration
{

    protected $tableName = 'messenger_member';
    protected $tableComment = 'Участники чата';

    public function tableSchema()
    {
        return function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->comment('Идентификатор');
            $table->integer('user_id')->comment('ID пользователя');
            $table->integer('chat_id')->comment('ID чата');

            $this->addForeign($table, 'user_id', 'user_identity');
            $this->addForeign($table, 'chat_id', 'messenger_chat');

            /*$table
                ->foreign('user_id')
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
