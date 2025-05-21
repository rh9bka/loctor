<?php

use yii\db\Migration;

class m240000_000003_add_slug_to_ads extends Migration
{
    public function up()
    {
        $this->addColumn('{{%ads}}', 'slug', $this->string(255)->notNull()->after('title'));
        $this->createIndex('idx-ads-slug', '{{%ads}}', 'slug', true);
    }

    public function down()
    {
        $this->dropIndex('idx-ads-slug', '{{%ads}}');
        $this->dropColumn('{{%ads}}', 'slug');
    }
} 