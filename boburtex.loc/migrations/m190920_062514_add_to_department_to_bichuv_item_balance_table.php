<?php

use yii\db\Migration;

/**
 * Class m190920_062514_add_to_department_to_bichuv_item_balance_table
 */
class m190920_062514_add_to_department_to_bichuv_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_item_balance','to_department',$this->integer());

        //to_department
        $this->createIndex(
            'idx-bichuv_item_balance-to_department',
            'bichuv_item_balance',
            'to_department'
        );

        $this->addForeignKey(
            'fk-bichuv_item_balance-to_department',
            'bichuv_item_balance',
            'to_department',
            'toquv_departments',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //to_department
        $this->dropForeignKey(
            'fk-bichuv_item_balance-to_department',
            'bichuv_item_balance'
        );

        $this->dropIndex(
            'idx-bichuv_item_balance-to_department',
            'bichuv_item_balance'
        );
        $this->dropColumn('bichuv_item_balance','to_department');

        return false;
    }
}
