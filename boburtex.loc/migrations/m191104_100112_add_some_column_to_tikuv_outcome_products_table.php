<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_outcome_products}}`.
 */
class m191104_100112_add_some_column_to_tikuv_outcome_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_outcome_products}}', 'price', $this->decimal(20,3));
        $this->addColumn('{{%tikuv_outcome_products}}', 'pb_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tikuv_outcome_products}}', 'price');
        $this->dropColumn('{{%tikuv_outcome_products}}', 'pb_id');
    }
}
