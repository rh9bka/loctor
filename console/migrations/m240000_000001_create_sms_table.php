<?php

use yii\db\Migration;

class m240000_000001_create_sms_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%sms}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'type' => $this->tinyInteger()->notNull(),
            'code' => $this->string(6)->notNull(),
            'phone' => $this->integer()->notNull(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'expired_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-sms-user_id',
            '{{%sms}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('{{%sms}}');
    }
} 