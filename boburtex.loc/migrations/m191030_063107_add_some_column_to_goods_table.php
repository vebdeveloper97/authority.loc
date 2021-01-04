<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%goods}}`.
 */
class m191030_063107_add_some_column_to_goods_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%goods}}', 'size_collection', $this->string());
        $this->addColumn('{{%goods}}', 'color_collection', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%goods}}', 'size_collection');
        $this->dropColumn('{{%goods}}', 'size_collection');
    }
}
