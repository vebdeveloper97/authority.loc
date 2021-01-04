<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%spare_item_doc}}`.
 */
class m200714_083838_add_add_info_column_to_spare_item_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%spare_item_doc}}', 'add_info', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%spare_item_doc}}', 'add_info');
    }
}
