<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_outcome_products}}`.
 */
class m191015_065928_add_amount_column_to_tikuv_outcome_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_outcome_products}}', 'amount', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tikuv_outcome_products}}', 'amount');
    }
}
