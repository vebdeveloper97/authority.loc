<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_makine}}`.
 */
class m191030_105007_add_raw_material_type_id_column_to_toquv_makine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_makine}}', 'raw_material_type_id', $this->smallInteger(2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_makine}}', 'raw_material_type_id');
    }
}
