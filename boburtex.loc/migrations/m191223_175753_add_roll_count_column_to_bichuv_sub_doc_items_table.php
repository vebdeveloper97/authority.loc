<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_sub_doc_items}}`.
 */
class m191223_175753_add_roll_count_column_to_bichuv_sub_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_sub_doc_items','roll_count', $this->decimal(20,2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_sub_doc_items','roll_count');
    }
}
