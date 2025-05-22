<?php

use yii\db\Migration;

class m240000_000000_create_ads_tables extends Migration
{
    public function up()
    {
        $this->createTable('{{%categories}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'svg' => $this->text()->defaultValue(null),
            'parent_id' => $this->integer()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%ads}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->text()->notNull(),
            'price' => $this->decimal(10, 2)->notNull(),
            'category_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Добавляем внешние ключи
        $this->addForeignKey(
            'fk-category-parent_id',
            '{{%categories}}',
            'parent_id',
            '{{%categories}}',
            'id',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk-ad-category_id',
            '{{%ads}}',
            'category_id',
            '{{%categories}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-ad-user_id',
            '{{%ads}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('{{%ads}}');
        $this->dropTable('{{%categories}}');
    }
} 