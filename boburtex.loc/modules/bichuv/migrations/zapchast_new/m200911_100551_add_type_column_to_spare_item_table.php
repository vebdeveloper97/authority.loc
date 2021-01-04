<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%spare_item}}`.
 */
class m200911_100551_add_type_column_to_spare_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%spare_item}}', 'type', $this->integer()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%spare_item}}', 'type');
    }
}
