<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_makine}}`.
 */
class m190918_120645_add_some_column_to_toquv_makine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_makine}}', 'toquv_thread', $this->smallInteger()->after('name'));
        $this->addColumn('{{%toquv_makine}}', 'toquv_ne', $this->smallInteger()->after('name'));
        $this->addColumn('{{%toquv_makine}}', 'finish_gramaj', $this->integer()->after('name'));
        $this->addColumn('{{%toquv_makine}}', 'finish_en', $this->integer()->after('name'));
        $this->addColumn('{{%toquv_makine}}', 'thread_length', $this->integer()->after('name'));
        $this->addColumn('{{%toquv_makine}}', 'type', $this->string(30)->after('name'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_makine}}', 'toquv_thread');
        $this->dropColumn('{{%toquv_makine}}', 'toquv_ne');
        $this->dropColumn('{{%toquv_makine}}', 'finish_gramaj');
        $this->dropColumn('{{%toquv_makine}}', 'finish_en');
        $this->dropColumn('{{%toquv_makine}}', 'thread_length');
        $this->dropColumn('{{%toquv_makine}}', 'type');
    }
}
