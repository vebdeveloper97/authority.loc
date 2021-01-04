<?php

use yii\db\Migration;

/**
 * Class m191219_153913_add_work_weight_to_bichuv_doc_items_table
 */
class m191219_153913_add_work_weight_to_bichuv_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_doc_items','work_weight', $this->integer(5)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropColumn('bichuv_doc_items','work_weight');
    }


}
