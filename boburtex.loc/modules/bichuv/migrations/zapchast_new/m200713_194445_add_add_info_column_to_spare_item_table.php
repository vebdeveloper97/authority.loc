<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%spare_item}}`.
 */
class m200713_194445_add_add_info_column_to_spare_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%spare_item}}', 'add_info', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%spare_item}}', 'add_info');
    }
}
