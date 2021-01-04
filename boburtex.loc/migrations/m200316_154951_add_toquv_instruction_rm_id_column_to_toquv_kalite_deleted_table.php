<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_kalite_deleted}}`.
 */
class m200316_154951_add_toquv_instruction_rm_id_column_to_toquv_kalite_deleted_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_kalite_deleted}}', 'toquv_instruction_rm_id', $this->integer()->after('toquv_instructions_id'));
        $this->addColumn('{{%toquv_kalite_deleted}}', 'toquv_raw_materials_id', $this->integer()->after('updated_at'));
        $this->addColumn('{{%toquv_kalite_deleted}}', 'type', $this->smallInteger()->defaultValue(1)->after('updated_at'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_kalite_deleted}}', 'toquv_instruction_rm_id');
        $this->dropColumn('{{%toquv_kalite_deleted}}', 'toquv_raw_materials_id');
        $this->dropColumn('{{%toquv_kalite_deleted}}', 'type');
    }
}
