<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_raw_materials}}`.
 */
class m191011_165412_add_type_column_to_toquv_raw_materials_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_raw_materials}}', 'type', $this->smallInteger(2)->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_raw_materials}}', 'type');
    }
}
