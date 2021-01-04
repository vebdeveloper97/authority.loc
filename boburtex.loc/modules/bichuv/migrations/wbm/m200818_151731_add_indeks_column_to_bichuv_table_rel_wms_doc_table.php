<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_table_rel_wms_doc}}`.
 */
class m200818_151731_add_indeks_column_to_bichuv_table_rel_wms_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_table_rel_wms_doc}}', 'indeks', $this->double(20,19));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%bichuv_table_rel_wms_doc}}', 'indeks');
    }
}
