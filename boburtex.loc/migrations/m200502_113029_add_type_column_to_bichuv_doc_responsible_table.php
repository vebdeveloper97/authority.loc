<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc_responsible}}`.
 */
class m200502_113029_add_type_column_to_bichuv_doc_responsible_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_doc_responsible}}', 'type', $this->smallInteger()->defaultValue(1));
        $this->addColumn('{{%bichuv_doc_responsible}}', 'bichuv_mato_orders_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%bichuv_doc_responsible}}', 'type');
        $this->dropColumn('{{%bichuv_doc_responsible}}', 'bichuv_mato_orders_id');
    }
}
