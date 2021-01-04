<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc}}`.
 */
class m200131_115635_add_is_returned_column_to_bichuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_doc','is_returned', $this->tinyInteger(1)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_doc','is_returned');
    }
}
