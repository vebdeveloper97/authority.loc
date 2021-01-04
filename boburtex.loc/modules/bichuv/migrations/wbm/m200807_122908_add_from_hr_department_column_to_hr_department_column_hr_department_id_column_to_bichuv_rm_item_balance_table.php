<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_rm_item_balance}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_departments}}`
 * - `{{%hr_departments}}`
 * - `{{%hr_departments}}`
 */
class m200807_122908_add_from_hr_department_column_to_hr_department_column_hr_department_id_column_to_bichuv_rm_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_rm_item_balance}}', 'from_hr_department', $this->integer());
        $this->addColumn('{{%bichuv_rm_item_balance}}', 'to_hr_department', $this->integer());
        $this->addColumn('{{%bichuv_rm_item_balance}}', 'hr_department_id', $this->integer());

        // creates index for column `from_hr_department`
        $this->createIndex(
            '{{%idx-bichuv_rm_item_balance-from_hr_department}}',
            '{{%bichuv_rm_item_balance}}',
            'from_hr_department'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-bichuv_rm_item_balance-from_hr_department}}',
            '{{%bichuv_rm_item_balance}}',
            'from_hr_department',
            '{{%hr_departments}}',
            'id'
        );

        // creates index for column `to_hr_department`
        $this->createIndex(
            '{{%idx-bichuv_rm_item_balance-to_hr_department}}',
            '{{%bichuv_rm_item_balance}}',
            'to_hr_department'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-bichuv_rm_item_balance-to_hr_department}}',
            '{{%bichuv_rm_item_balance}}',
            'to_hr_department',
            '{{%hr_departments}}',
            'id'
        );

        // creates index for column `hr_department_id`
        $this->createIndex(
            '{{%idx-bichuv_rm_item_balance-hr_department_id}}',
            '{{%bichuv_rm_item_balance}}',
            'hr_department_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-bichuv_rm_item_balance-hr_department_id}}',
            '{{%bichuv_rm_item_balance}}',
            'hr_department_id',
            '{{%hr_departments}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_rm_item_balance-from_hr_department}}',
            '{{%bichuv_rm_item_balance}}'
        );

        // drops index for column `from_hr_department`
        $this->dropIndex(
            '{{%idx-bichuv_rm_item_balance-from_hr_department}}',
            '{{%bichuv_rm_item_balance}}'
        );

        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_rm_item_balance-to_hr_department}}',
            '{{%bichuv_rm_item_balance}}'
        );

        // drops index for column `to_hr_department`
        $this->dropIndex(
            '{{%idx-bichuv_rm_item_balance-to_hr_department}}',
            '{{%bichuv_rm_item_balance}}'
        );

        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_rm_item_balance-hr_department_id}}',
            '{{%bichuv_rm_item_balance}}'
        );

        // drops index for column `hr_department_id`
        $this->dropIndex(
            '{{%idx-bichuv_rm_item_balance-hr_department_id}}',
            '{{%bichuv_rm_item_balance}}'
        );

        $this->dropColumn('{{%bichuv_rm_item_balance}}', 'from_hr_department');
        $this->dropColumn('{{%bichuv_rm_item_balance}}', 'to_hr_department');
        $this->dropColumn('{{%bichuv_rm_item_balance}}', 'hr_department_id');
    }
}
