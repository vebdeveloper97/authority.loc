<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_raw_materials}}`.
 */
class m191026_122606_add_density_column_to_toquv_raw_materials_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_raw_materials}}', 'density', $this->decimal(20,3));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_raw_materials}}', 'density');
    }
}
