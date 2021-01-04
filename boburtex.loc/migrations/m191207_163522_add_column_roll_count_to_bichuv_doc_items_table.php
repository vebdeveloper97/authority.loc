<?php

use yii\db\Migration;

/**
 * Class m191207_163522_add_column_roll_count_to_bichuv_doc_items_table
 */
class m191207_163522_add_column_roll_count_to_bichuv_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_doc_items','roll_count', $this->decimal(5,2)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_doc_items','roll_count');
    }


}
