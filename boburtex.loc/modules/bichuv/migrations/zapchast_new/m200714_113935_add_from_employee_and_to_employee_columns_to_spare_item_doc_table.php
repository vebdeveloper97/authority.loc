<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%spare_item_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 * - `{{%users}}`
 */
class m200714_113935_add_from_employee_and_to_employee_columns_to_spare_item_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_doc-from_employee}}',
            '{{%spare_item_doc}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_doc-to_employee}}',
            '{{%spare_item_doc}}'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-spare_item_doc-from_employee}}',
            '{{%spare_item_doc}}',
            'from_employee',
            '{{%hr_employee}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-spare_item_doc-to_employee}}',
            '{{%spare_item_doc}}',
            'to_employee',
            '{{%hr_employee}}',
            'id',
            'CASCADE'
        );
    }
}
