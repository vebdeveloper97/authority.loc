<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_slice_item_balance}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_departments}}`
 * - `{{%hr_departments}}`
 * - `{{%hr_departments}}`
 */
class m200907_093759_add_departments_columns_to_tikuv_slice_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_slice_item_balance}}', 'hr_department_id', $this->integer());
        $this->addColumn('{{%tikuv_slice_item_balance}}', 'from_hr_department', $this->integer());
        $this->addColumn('{{%tikuv_slice_item_balance}}', 'to_hr_department', $this->integer());

        // creates index for column `hr_department_id`
        $this->createIndex(
            '{{%idx-tikuv_slice_item_balance-hr_department_id}}',
            '{{%tikuv_slice_item_balance}}',
            'hr_department_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-tikuv_slice_item_balance-hr_department_id}}',
            '{{%tikuv_slice_item_balance}}',
            'hr_department_id',
            '{{%hr_departments}}',
            'id'
        );

        // creates index for column `from_hr_department`
        $this->createIndex(
            '{{%idx-tikuv_slice_item_balance-from_hr_department}}',
            '{{%tikuv_slice_item_balance}}',
            'from_hr_department'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-tikuv_slice_item_balance-from_hr_department}}',
            '{{%tikuv_slice_item_balance}}',
            'from_hr_department',
            '{{%hr_departments}}',
            'id'
        );

        // creates index for column `to_hr_department`
        $this->createIndex(
            '{{%idx-tikuv_slice_item_balance-to_hr_department}}',
            '{{%tikuv_slice_item_balance}}',
            'to_hr_department'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-tikuv_slice_item_balance-to_hr_department}}',
            '{{%tikuv_slice_item_balance}}',
            'to_hr_department',
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
            '{{%fk-tikuv_slice_item_balance-hr_department_id}}',
            '{{%tikuv_slice_item_balance}}'
        );

        // drops index for column `hr_department_id`
        $this->dropIndex(
            '{{%idx-tikuv_slice_item_balance-hr_department_id}}',
            '{{%tikuv_slice_item_balance}}'
        );

        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_slice_item_balance-from_hr_department}}',
            '{{%tikuv_slice_item_balance}}'
        );

        // drops index for column `from_hr_department`
        $this->dropIndex(
            '{{%idx-tikuv_slice_item_balance-from_hr_department}}',
            '{{%tikuv_slice_item_balance}}'
        );

        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_slice_item_balance-to_hr_department}}',
            '{{%tikuv_slice_item_balance}}'
        );

        // drops index for column `to_hr_department`
        $this->dropIndex(
            '{{%idx-tikuv_slice_item_balance-to_hr_department}}',
            '{{%tikuv_slice_item_balance}}'
        );

        $this->dropColumn('{{%tikuv_slice_item_balance}}', 'hr_department_id');
        $this->dropColumn('{{%tikuv_slice_item_balance}}', 'from_hr_department');
        $this->dropColumn('{{%tikuv_slice_item_balance}}', 'to_hr_department');
    }
}
