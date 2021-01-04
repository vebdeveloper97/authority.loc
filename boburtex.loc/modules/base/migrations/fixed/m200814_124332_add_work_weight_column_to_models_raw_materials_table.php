<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_raw_materials}}`.
 */
class m200814_124332_add_work_weight_column_to_models_raw_materials_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_raw_materials}}', 'work_weight', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%models_raw_materials}}', 'work_weight');
    }
}
