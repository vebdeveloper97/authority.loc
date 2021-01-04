<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%spare_inspection_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%spare_item_doc_item_balance}}`
 */
class m200914_144532_add_spare_item_balance_id_column_to_spare_inspection_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%spare_inspection_items}}', 'spare_item_balance_id', $this->integer());

        // creates index for column `spare_item_balance_id`
        $this->createIndex(
            '{{%idx-spare_inspection_items-spare_item_balance_id}}',
            '{{%spare_inspection_items}}',
            'spare_item_balance_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        // drops index for column `spare_item_balance_id`
        $this->dropIndex(
            '{{%idx-spare_inspection_items-spare_item_balance_id}}',
            '{{%spare_inspection_items}}'
        );

        $this->dropColumn('{{%spare_inspection_items}}', 'spare_item_balance_id');
    }
}
