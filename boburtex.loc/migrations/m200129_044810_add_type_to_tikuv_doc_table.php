<?php

use yii\db\Migration;

/**
 * Class m200129_044810_add_type_to_tikuv_doc_table
 */
class m200129_044810_add_type_to_tikuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tikuv_doc','type',$this->smallInteger(1)->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tikuv_doc','type');
    }
}
