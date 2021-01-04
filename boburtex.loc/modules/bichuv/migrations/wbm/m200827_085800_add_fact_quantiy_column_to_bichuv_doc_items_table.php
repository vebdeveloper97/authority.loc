<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc_items}}`.
 */
class m200827_085800_add_fact_quantiy_column_to_bichuv_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_doc_items}}', 'fact_quantity', $this->decimal(20,3));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%bichuv_doc_items}}', 'fact_quantity');
    }
}
