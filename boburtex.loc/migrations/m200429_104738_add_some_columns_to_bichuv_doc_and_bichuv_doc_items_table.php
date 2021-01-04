<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc}}` and  `{{%bichuv_doc_items}}`.
 */
class m200429_104738_add_some_columns_to_bichuv_doc_and_bichuv_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_doc}}', 'bichuv_mato_orders_id', $this->integer());
        $this->addColumn('{{%bichuv_doc_items}}', 'bichuv_mato_order_items_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%bichuv_doc}}', 'bichuv_mato_orders_id');
        $this->dropColumn('{{%bichuv_doc_items}}', 'bichuv_mato_order_items_id');
    }
}
