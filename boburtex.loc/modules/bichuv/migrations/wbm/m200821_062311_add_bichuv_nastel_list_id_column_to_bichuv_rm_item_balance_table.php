<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_rm_item_balance}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bichuv_nastel_lists}}`
 */
class m200821_062311_add_bichuv_nastel_list_id_column_to_bichuv_rm_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_rm_item_balance}}', 'bichuv_nastel_list_id', $this->integer());

        // creates index for column `bichuv_nastel_list_id`
        $this->createIndex(
            '{{%idx-bichuv_rm_item_balance-bichuv_nastel_list_id}}',
            '{{%bichuv_rm_item_balance}}',
            'bichuv_nastel_list_id'
        );

        // add foreign key for table `{{%bichuv_nastel_lists}}`
        $this->addForeignKey(
            '{{%fk-bichuv_rm_item_balance-bichuv_nastel_list_id}}',
            '{{%bichuv_rm_item_balance}}',
            'bichuv_nastel_list_id',
            '{{%bichuv_nastel_lists}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_nastel_lists}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_rm_item_balance-bichuv_nastel_list_id}}',
            '{{%bichuv_rm_item_balance}}'
        );

        // drops index for column `bichuv_nastel_list_id`
        $this->dropIndex(
            '{{%idx-bichuv_rm_item_balance-bichuv_nastel_list_id}}',
            '{{%bichuv_rm_item_balance}}'
        );

        $this->dropColumn('{{%bichuv_rm_item_balance}}', 'bichuv_nastel_list_id');
    }
}
