<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc_items}}`.
 */
class m200110_103056_add_is_fixed_column_to_bichuv_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_doc_items','is_fixed', $this->smallInteger()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_doc_items','is_fixed');
    }
}
