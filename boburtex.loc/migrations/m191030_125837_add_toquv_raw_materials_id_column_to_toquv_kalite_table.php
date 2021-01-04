<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_kalite}}`.
 */
class m191030_125837_add_toquv_raw_materials_id_column_to_toquv_kalite_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_kalite}}', 'toquv_raw_materials_id', $this->smallInteger(2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_kalite}}', 'toquv_raw_materials_id');
    }
}
