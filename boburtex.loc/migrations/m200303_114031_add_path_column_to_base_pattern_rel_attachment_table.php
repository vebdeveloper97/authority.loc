<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%base_pattern_rel_attachment}}`.
 */
class m200303_114031_add_path_column_to_base_pattern_rel_attachment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('base_pattern_rel_attachment','path',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('base_pattern_rel_attachment','path');
    }
}
