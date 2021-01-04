<?php

use yii\db\Migration;

/**
 * Class m191207_091212_add_cloumn_type_to_raw_material_table
 */
class m191207_091212_add_cloumn_type_to_raw_material_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('raw_material','type', $this->smallInteger()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('raw_material','type');
    }
}
