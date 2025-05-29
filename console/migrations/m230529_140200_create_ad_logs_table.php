<?php

use yii\db\Migration;

/**
 * Class m230529_140200_create_ad_logs_table
 */
class m230529_140200_create_ad_logs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ad_logs}}', [
            'id' => $this->primaryKey(),
            'ad_id' => $this->integer()->notNull(),
            'user_id' => $this->integer(),
            'action' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-ad_logs-ad_id', '{{%ad_logs}}', 'ad_id');
        $this->createIndex('idx-ad_logs-user_id', '{{%ad_logs}}', 'user_id');

        $this->addForeignKey(
            'fk-ad_logs-ad_id',
            '{{%ad_logs}}',
            'ad_id',
            '{{%ads}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-ad_logs-user_id',
            '{{%ad_logs}}',
            'user_id',
            '{{%user}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-ad_logs-user_id', '{{%ad_logs}}');
        $this->dropForeignKey('fk-ad_logs-ad_id', '{{%ad_logs}}');
        $this->dropIndex('idx-ad_logs-user_id', '{{%ad_logs}}');
        $this->dropIndex('idx-ad_logs-ad_id', '{{%ad_logs}}');
        $this->dropTable('{{%ad_logs}}');
    }
}
