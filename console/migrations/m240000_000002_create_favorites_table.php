<?php

use yii\db\Migration;

class m240000_000002_create_favorites_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%favorites}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'ad_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        // Добавляем внешние ключи
        $this->addForeignKey(
            'fk-favorites-user_id',
            '{{%favorites}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-favorites-ad_id',
            '{{%favorites}}',
            'ad_id',
            '{{%ads}}',
            'id',
            'CASCADE'
        );

        // Добавляем уникальный индекс для предотвращения дублирования
        $this->createIndex(
            'idx-favorites-user_ad',
            '{{%favorites}}',
            ['user_id', 'ad_id'],
            true
        );
    }

    public function down()
    {
        $this->dropTable('{{%favorites}}');
    }
} 