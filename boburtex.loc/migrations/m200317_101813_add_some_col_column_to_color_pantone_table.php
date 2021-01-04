<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%color_pantone}}`.
 */
class m200317_101813_add_some_col_column_to_color_pantone_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('color_pantone','name_ru',$this->string());
        $this->addColumn('color_pantone','name_uz',$this->string());
        $this->addColumn('color_pantone','name_ml',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('color_pantone','name_ru');
        $this->dropColumn('color_pantone','name_uz');
        $this->dropColumn('color_pantone','name_ml');
    }
}
