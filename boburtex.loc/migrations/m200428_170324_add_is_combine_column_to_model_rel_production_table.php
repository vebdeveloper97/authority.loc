<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_rel_production}}`.
 */
class m200428_170324_add_is_combine_column_to_model_rel_production_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('model_rel_production','is_combine', $this->smallInteger(1)->defaultValue(1));
        $this->addColumn('tikuv_doc','combined_nastel', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('model_rel_production','is_combine');
        $this->dropColumn('tikuv_doc','combined_nastel');
    }
}
