<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_outcome_products}} and {{%tikuv_outcome_products_pack}}`.
 */
class m191014_111557_add_some_column_to_tikuv_outcome_products_and_tikuv_outcome_products_pack_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_outcome_products}}', 'unit_id', $this->integer());
        $this->addColumn('{{%tikuv_outcome_products}}', 'reg_date', $this->dateTime());
        $this->addColumn('{{%tikuv_outcome_products_pack}}', 'reg_date', $this->dateTime());
        // creates index for column `unit_id`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products-unit_id}}',
            '{{%tikuv_outcome_products}}',
            'unit_id'
        );

        // add foreign key for table `{{%unit}}`
        $this->addForeignKey(
            '{{%fk-tikuv_outcome_products-unit_id}}',
            '{{%tikuv_outcome_products}}',
            'unit_id',
            '{{%unit}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%unit}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_outcome_products-unit_id}}',
            '{{%tikuv_outcome_products}}'
        );

        // drops index for column `unit_id`
        $this->dropIndex(
            '{{%idx-tikuv_outcome_products-unit_id}}',
            '{{%tikuv_outcome_products}}'
        );
        $this->dropColumn('{{%tikuv_outcome_products}}', 'unit_id');
        $this->dropColumn('{{%tikuv_outcome_products}}', 'reg_date');
        $this->dropColumn('{{%tikuv_outcome_products_pack}}', 'reg_date');
    }
}
