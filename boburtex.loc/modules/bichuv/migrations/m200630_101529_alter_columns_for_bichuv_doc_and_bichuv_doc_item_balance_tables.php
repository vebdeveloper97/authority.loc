<?php

use yii\db\Migration;

/**
 * Class m200630_101529_alter_columns_for_bichuv_doc_and_bichuv_doc_items_and_bichuv_doc_item_balance_tables
 */
class m200630_101529_alter_columns_for_bichuv_doc_and_bichuv_doc_item_balance_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // bichuv_doc table
        $this->dropForeignKey('fk-bichuv_doc-from_department', '{{%bichuv_doc}}');
        $this->dropForeignKey('fk-bichuv_doc-to_department', "{{%bichuv_doc}}");

        $this->addForeignKey(
        'fk-bichuv_doc-from_department',
        '{{%bichuv_doc}}',
        'from_department',
        '{{%hr_departments}}',
        'id'
        );

        $this->addForeignKey(
        'fk-bichuv_doc-to_department',
        '{{%bichuv_doc}}',
        'to_department',
        '{{%hr_departments}}',
        'id'
        );

        // bichuv_item_balance table
        $this->dropForeignKey('fk-bichuv_item_balance-department_id', '{{%bichuv_item_balance}}');
        $this->dropForeignKey('fk-bichuv_item_balance-from_department', '{{%bichuv_item_balance}}');
        $this->dropForeignKey('fk-bichuv_item_balance-to_department', '{{%bichuv_item_balance}}');

        $this->addForeignKey(
        'fk-bichuv_item_balance-from_department',
        '{{%bichuv_item_balance}}',
        'from_department',
        '{{%hr_departments}}',
        'id'
        );

        $this->addForeignKey(
        'fk-bichuv_item_balance-to_department',
        '{{%bichuv_item_balance}}',
        'to_department',
        '{{%hr_departments}}',
        'id'
        );

        $this->addForeignKey(
        'fk-bichuv_item_balance-department_id',
        '{{%bichuv_item_balance}}',
        'department_id',
        '{{%hr_departments}}',
        'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200630_101529_alter_columns_for_bichuv_doc_and_bichuv_doc_items_and_bichuv_doc_item_balance_tables cannot be reverted.\n";
        $this->dropForeignKey('fk-bichuv_doc-from_department', '{{%bichuv_doc}}');
        $this->dropForeignKey('fk-bichuv_doc-to_department', '{{%bichuv_doc}}');
        $this->dropForeignKey('fk-bichuv_item_balance-department_id', '{{%bichuv_item_balance}}');
        $this->dropForeignKey('fk-bichuv_item_balance-from_department', '{{%bichuv_item_balance}}');
        $this->dropForeignKey('fk-bichuv_item_balance-to_department', '{{%bichuv_item_balance}}');
        /*return false;*/
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200630_101529_alter_columns_for_bichuv_doc_and_bichuv_doc_items_and_bichuv_doc_item_balance_tables cannot be reverted.\n";

        return false;
    }
    */
}
