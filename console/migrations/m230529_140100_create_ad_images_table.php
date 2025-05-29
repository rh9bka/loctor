<?php

use yii\db\Migration;

/**
 * Class m230529_140100_create_ad_images_table
 */
class m230529_140100_create_ad_images_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ad_images}}', [
            'id' => $this->primaryKey(),
            'ad_id' => $this->integer()->notNull(),
            'filename' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-ad_images-ad_id', '{{%ad_images}}', 'ad_id');
        $this->addForeignKey(
            'fk-ad_images-ad_id',
            '{{%ad_images}}',
            'ad_id',
            '{{%ads}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-ad_images-ad_id', '{{%ad_images}}');
        $this->dropIndex('idx-ad_images-ad_id', '{{%ad_images}}');
        $this->dropTable('{{%ad_images}}');
    }
}
