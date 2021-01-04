<?php

use yii\db\Migration;

/**
 * Class m200213_111210_add_required_count_column_bichuv_given_roll_items_table
 */
class m200213_111210_add_required_count_column_bichuv_given_roll_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_given_roll_items','required_count',$this->decimal(20,3));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_given_roll_items','required_count');
    }

}
