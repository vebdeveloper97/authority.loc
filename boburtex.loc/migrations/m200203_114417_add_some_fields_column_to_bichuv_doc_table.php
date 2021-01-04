<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc}}`.
 */
class m200203_114417_add_some_fields_column_to_bichuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_doc', 'nastel_table_no', $this->smallInteger(2)->defaultValue(0));
        $this->addColumn('bichuv_doc','nastel_table_worker', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_doc', 'nastel_table_no');
        $this->dropColumn('bichuv_doc','nastel_table_worker');
    }
}
