<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%spare_inspection_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%spare_item}}`
 */
class m200912_065400_add_spare_item_id_column_quantity_column_to_spare_inspection_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%spare_inspection_items}}', 'spare_item_id', $this->integer());
        $this->addColumn('{{%spare_inspection_items}}', 'quantity', $this->decimal(20,3));

        // creates index for column `spare_item_id`
        $this->createIndex(
            '{{%idx-spare_inspection_items-spare_item_id}}',
            '{{%spare_inspection_items}}',
            'spare_item_id'
        );

        // add foreign key for table `{{%spare_item}}`
        $this->addForeignKey(
            '{{%fk-spare_inspection_items-spare_item_id}}',
            '{{%spare_inspection_items}}',
            'spare_item_id',
            '{{%spare_item}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%spare_item}}`
        $this->dropForeignKey(
            '{{%fk-spare_inspection_items-spare_item_id}}',
            '{{%spare_inspection_items}}'
        );

        // drops index for column `spare_item_id`
        $this->dropIndex(
            '{{%idx-spare_inspection_items-spare_item_id}}',
            '{{%spare_inspection_items}}'
        );

        $this->dropColumn('{{%spare_inspection_items}}', 'spare_item_id');
        $this->dropColumn('{{%spare_inspection_items}}', 'quantity');
    }
}
