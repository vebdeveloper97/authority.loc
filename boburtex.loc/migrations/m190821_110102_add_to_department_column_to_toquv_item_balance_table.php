<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_item_balance}}`.
 */
class m190821_110102_add_to_department_column_to_toquv_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_item_balance}}', 'to_department', $this->integer());

        //to_department
        $this->createIndex(
            'idx-toquv_item_balance-to_department',
            'toquv_item_balance',
            'to_department'
        );

        $this->addForeignKey(
            'fk-toquv_item_balance-to_department',
            'toquv_item_balance',
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
            'fk-toquv_item_balance-to_department',
            'toquv_item_balance'
        );

        $this->dropIndex(
            'idx-toquv_item_balance-to_department',
            'toquv_item_balance'
        );
        $this->dropColumn('{{%toquv_item_balance}}', 'to_department');
    }
}
