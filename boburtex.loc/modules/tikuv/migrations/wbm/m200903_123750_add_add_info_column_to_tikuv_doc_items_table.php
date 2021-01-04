<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_doc_items}}`.
 */
class m200903_123750_add_add_info_column_to_tikuv_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_doc_items}}', 'add_info', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tikuv_doc_items}}', 'add_info');
    }
}
