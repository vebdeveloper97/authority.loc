<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_given_rolls}}`.
 */
class m191219_085217_add_nastel_no_column_to_bichuv_given_rolls_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_given_rolls','nastel_no',$this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_given_rolls','nastel_no');
    }
}
