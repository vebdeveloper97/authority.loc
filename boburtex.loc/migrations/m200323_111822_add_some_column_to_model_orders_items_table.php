<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%size_collections}}`
 */
class m200323_111822_add_some_column_to_model_orders_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_items}}', 'size_collections_id', $this->integer());

        // creates index for column `size_collections_id`
        $this->createIndex(
            '{{%idx-model_orders_items-size_collections_id}}',
            '{{%model_orders_items}}',
            'size_collections_id'
        );

        // add foreign key for table `{{%size_collections}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items-size_collections_id}}',
            '{{%model_orders_items}}',
            'size_collections_id',
            '{{%size_collections}}',
            'id',
            'CASCADE'
        );
        $this->execute("ALTER TABLE `model_orders` ADD `prepayment` FLOAT(5,2) NULL DEFAULT '0' AFTER `status`;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%size_collections}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items-size_collections_id}}',
            '{{%model_orders_items}}'
        );

        // drops index for column `size_collections_id`
        $this->dropIndex(
            '{{%idx-model_orders_items-size_collections_id}}',
            '{{%model_orders_items}}'
        );

        $this->dropColumn('{{%model_orders_items}}', 'size_collections_id');
        $this->dropColumn('{{%model_orders}}', 'prepayment');
    }
}
