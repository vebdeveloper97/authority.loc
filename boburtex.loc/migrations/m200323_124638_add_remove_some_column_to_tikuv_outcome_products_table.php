<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_outcome_products}}`.
 */
class m200323_124638_add_remove_some_column_to_tikuv_outcome_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Fetch the table schema
        $table = Yii::$app->db->schema->getTableSchema('tikuv_outcome_products');
        if(isset($table->columns['tikuv_slice_item_balance_id'])) {
            // drops foreign key for table `{{%goods}}`

            $this->dropForeignKey(
                '{{%fk-tikuv_outcome_products-tikuv_slice_item_balance_id}}',
                '{{%tikuv_outcome_products}}'
            );

            // drops index for column `tikuv_slice_item_balance_id`
            $this->dropIndex(
                '{{%idx-tikuv_outcome_products-tikuv_slice_item_balance_id}}',
                '{{%tikuv_outcome_products}}'
            );
            $this->dropColumn('tikuv_outcome_products','tikuv_slice_item_balance_id');
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
