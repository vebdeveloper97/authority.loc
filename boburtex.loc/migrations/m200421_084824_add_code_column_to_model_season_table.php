<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_season}}`.
 */
class m200421_084824_add_code_column_to_model_season_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('model_season','code',$this->string());
        $this->addColumn('model_view','code', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('model_season','code');
        $this->dropColumn('model_view','code');
    }
}
