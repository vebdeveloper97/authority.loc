<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_rm_item_balance}}`.
 */
class m191226_153710_add_department_id_column_to_bichuv_rm_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_rm_item_balance','department_id', $this->integer());

        // creates index for column `department_id`
        $this->createIndex(
            '{{%idx-bichuv_rm_item_balance-department_id}}',
            '{{%bichuv_rm_item_balance}}',
            'department_id'
        );

        // add foreign key for table `{{%department_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_rm_item_balance-department_id}}',
            '{{%bichuv_rm_item_balance}}',
            'department_id',
            '{{%toquv_departments}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%department_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_rm_item_balance-department_id}}',
            '{{%bichuv_rm_item_balance}}'
        );

        // drops index for column `department_id`
        $this->dropIndex(
            '{{%idx-bichuv_rm_item_balance-department_id}}',
            '{{%bichuv_rm_item_balance}}'
        );
        $this->dropColumn('bichuv_rm_item_balance','department_id');
    }
}
