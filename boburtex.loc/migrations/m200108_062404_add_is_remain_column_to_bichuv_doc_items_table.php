<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc_items}}`.
 */
class m200108_062404_add_is_remain_column_to_bichuv_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_doc_items','is_remain', $this->tinyInteger()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_doc_items','is_remain');
    }
}
