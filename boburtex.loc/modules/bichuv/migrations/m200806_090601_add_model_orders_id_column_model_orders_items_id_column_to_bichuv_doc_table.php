<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders}}`
 * - `{{%model_orders_items}}`
 */
class m200806_090601_add_model_orders_id_column_model_orders_items_id_column_to_bichuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_doc}}', 'model_orders_id', $this->integer());
        $this->addColumn('{{%bichuv_doc}}', 'model_orders_items_id', $this->integer());

        // creates index for column `model_orders_id`
        $this->createIndex(
            '{{%idx-bichuv_doc-model_orders_id}}',
            '{{%bichuv_doc}}',
            'model_orders_id'
        );

        // add foreign key for table `{{%model_orders}}`
        $this->addForeignKey(
            '{{%fk-bichuv_doc-model_orders_id}}',
            '{{%bichuv_doc}}',
            'model_orders_id',
            '{{%model_orders}}',
            'id',
            'CASCADE'
        );

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-bichuv_doc-model_orders_items_id}}',
            '{{%bichuv_doc}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-bichuv_doc-model_orders_items_id}}',
            '{{%bichuv_doc}}',
            'model_orders_items_id',
            '{{%model_orders_items}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_orders}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_doc-model_orders_id}}',
            '{{%bichuv_doc}}'
        );

        // drops index for column `model_orders_id`
        $this->dropIndex(
            '{{%idx-bichuv_doc-model_orders_id}}',
            '{{%bichuv_doc}}'
        );

        // drops foreign key for table `{{%model_orders_items}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_doc-model_orders_items_id}}',
            '{{%bichuv_doc}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-bichuv_doc-model_orders_items_id}}',
            '{{%bichuv_doc}}'
        );

        $this->dropColumn('{{%bichuv_doc}}', 'model_orders_id');
        $this->dropColumn('{{%bichuv_doc}}', 'model_orders_items_id');
    }
}
