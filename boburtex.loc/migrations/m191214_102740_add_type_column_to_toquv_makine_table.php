<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_makine}}`.
 */
class m191214_102740_add_type_column_to_toquv_makine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%toquv_makine}}', 'type');
        $this->addColumn('{{%toquv_makine}}', 'type', $this->smallInteger()->defaultValue(1)->after('name'));
        $this->addColumn('{{%toquv_kalite}}', 'smena', $this->string(3));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_kalite}}', 'smena');
        $this->dropColumn('{{%toquv_makine}}', 'type');
        $this->addColumn('{{%toquv_makine}}', 'type', $this->string(30)->after('name'));
    }
}
