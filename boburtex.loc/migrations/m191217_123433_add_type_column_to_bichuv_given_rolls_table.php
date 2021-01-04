<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_given_rolls}}`.
 */
class m191217_123433_add_type_column_to_bichuv_given_rolls_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_given_rolls','type',$this->smallInteger(2)->defaultValue(1));
        $this->addColumn('bichuv_roll_records','first_qty',$this->decimal(10,3)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_given_rolls','type');
        $this->dropColumn('bichuv_roll_records','first_qrt');
    }
}
