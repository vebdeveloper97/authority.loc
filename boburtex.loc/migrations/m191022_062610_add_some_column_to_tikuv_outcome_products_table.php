<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_outcome_products}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%goods}}`
 */
class m191022_062610_add_some_column_to_tikuv_outcome_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_outcome_products}}', 'goods_id', $this->integer());
        $this->addColumn('{{%tikuv_outcome_products}}', 'type', $this->smallInteger(2)->defaultValue(1));

        // creates index for column `goods_id`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products-goods_id}}',
            '{{%tikuv_outcome_products}}',
            'goods_id'
        );

        // add foreign key for table `{{%goods}}`
        $this->addForeignKey(
            '{{%fk-tikuv_outcome_products-goods_id}}',
            '{{%tikuv_outcome_products}}',
            'goods_id',
            '{{%goods}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%goods}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_outcome_products-goods_id}}',
            '{{%tikuv_outcome_products}}'
        );

        // drops index for column `goods_id`
        $this->dropIndex(
            '{{%idx-tikuv_outcome_products-goods_id}}',
            '{{%tikuv_outcome_products}}'
        );

        $this->dropColumn('{{%tikuv_outcome_products}}', 'goods_id');
        $this->dropColumn('{{%tikuv_outcome_products}}', 'type');
    }
}
