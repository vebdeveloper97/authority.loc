<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_raw_materials}}`.
 */
class m200815_090520_add_color_id_column_to_models_raw_materials_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_raw_materials}}', 'color_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%models_raw_materials}}', 'color_id');
    }
}
