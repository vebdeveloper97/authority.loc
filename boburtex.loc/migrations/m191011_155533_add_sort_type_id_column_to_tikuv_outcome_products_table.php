<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_outcome_products}}`.
 */
class m191011_155533_add_sort_type_id_column_to_tikuv_outcome_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tikuv_outcome_products','sort_type_id', $this->integer());

        // creates index for column `sort_type_id`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products-sort_type_id}}',
            '{{%tikuv_outcome_products}}',
            'sort_type_id'
        );

        // add foreign key for table `{{%musteri}}`
        $this->addForeignKey(
            '{{%fk-tikuv_outcome_products-sort_type_id}}',
            '{{%tikuv_outcome_products}}',
            'sort_type_id',
            '{{%sort_name}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%fk-tikuv_outcome_products-sort_type_id}}',
            '{{%tikuv_outcome_products}}'
        );

        // drops index for column `sort_type_id`
        $this->dropIndex(
            '{{%idx-tikuv_outcome_products-sort_type_id}}',
            '{{%tikuv_outcome_products}}'
        );

        $this->dropColumn('tikuv_outcome_products','sort_type_id');
    }
}
