<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%raw_material_type}}`.
 */
class m191025_155703_add_type_column_to_raw_material_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%raw_material_type}}', 'type', $this->smallInteger(2)->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%raw_material_type}}', 'type');
    }
}
