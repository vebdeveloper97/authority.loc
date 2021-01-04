<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_outcome_products_pack}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%order}}`
 */
class m200405_182958_add_order_id_column_to_tikuv_outcome_products_pack_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_outcome_products_pack}}', 'order_id', $this->integer());

        // creates index for column `order_id`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products_pack-order_id}}',
            '{{%tikuv_outcome_products_pack}}',
            'order_id'
        );

        // add foreign key for table `{{%model_orders}}`
        $this->addForeignKey(
            '{{%fk-tikuv_outcome_products_pack-order_id}}',
            '{{%tikuv_outcome_products_pack}}',
            'order_id',
            '{{%model_orders}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%order}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_outcome_products_pack-order_id}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        // drops index for column `order_id`
        $this->dropIndex(
            '{{%idx-tikuv_outcome_products_pack-order_id}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        $this->dropColumn('{{%tikuv_outcome_products_pack}}', 'order_id');
    }
}
