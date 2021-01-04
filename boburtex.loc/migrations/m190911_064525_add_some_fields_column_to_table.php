<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%}}`.
 */
class m190911_064525_add_some_fields_column_to_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('toquv_rm_order','thread_length',$this->integer());
        $this->addColumn('toquv_rm_order','finish_en',$this->integer());
        $this->addColumn('toquv_rm_order','finish_gramaj',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('toquv_rm_order', 'thread_length');
        $this->dropColumn('toquv_rm_order', 'finish_en');
        $this->dropColumn('toquv_rm_order', 'finish_gramaj');
    }
}
