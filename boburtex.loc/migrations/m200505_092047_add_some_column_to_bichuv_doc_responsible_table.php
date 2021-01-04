<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc_responsible}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bichuv_mato_order_items}}`
 */
class m200505_092047_add_some_column_to_bichuv_doc_responsible_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_doc_responsible}}', 'bichuv_mato_order_items_id', $this->integer());

        // creates index for column `bichuv_mato_order_items_id`
        $this->createIndex(
            '{{%idx-bichuv_doc_responsible-bichuv_mato_order_items_id}}',
            '{{%bichuv_doc_responsible}}',
            'bichuv_mato_order_items_id'
        );

        // add foreign key for table `{{%bichuv_mato_order_items}}`
        $this->addForeignKey(
            '{{%fk-bichuv_doc_responsible-bichuv_mato_order_items_id}}',
            '{{%bichuv_doc_responsible}}',
            'bichuv_mato_order_items_id',
            '{{%bichuv_mato_order_items}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_mato_order_items}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_doc_responsible-bichuv_mato_order_items_id}}',
            '{{%bichuv_doc_responsible}}'
        );

        // drops index for column `bichuv_mato_order_items_id`
        $this->dropIndex(
            '{{%idx-bichuv_doc_responsible-bichuv_mato_order_items_id}}',
            '{{%bichuv_doc_responsible}}'
        );

        $this->dropColumn('{{%bichuv_doc_responsible}}', 'bichuv_mato_order_items_id');
    }
}
