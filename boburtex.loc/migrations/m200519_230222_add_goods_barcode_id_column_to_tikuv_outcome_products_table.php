<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_outcome_products}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%goods_barcode}}`
 */
class m200519_230222_add_goods_barcode_id_column_to_tikuv_outcome_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_outcome_products}}', 'goods_barcode_id', $this->integer());

        // creates index for column `goods_barcode_id`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products-goods_barcode_id}}',
            '{{%tikuv_outcome_products}}',
            'goods_barcode_id'
        );

        // add foreign key for table `{{%goods_barcode}}`
        $this->addForeignKey(
            '{{%fk-tikuv_outcome_products-goods_barcode_id}}',
            '{{%tikuv_outcome_products}}',
            'goods_barcode_id',
            '{{%goods_barcode}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%goods_barcode}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_outcome_products-goods_barcode_id}}',
            '{{%tikuv_outcome_products}}'
        );

        // drops index for column `goods_barcode_id`
        $this->dropIndex(
            '{{%idx-tikuv_outcome_products-goods_barcode_id}}',
            '{{%tikuv_outcome_products}}'
        );

        $this->dropColumn('{{%tikuv_outcome_products}}', 'goods_barcode_id');
    }
}
