<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_list}}` and `{{%models_variations}}`.
 */
class m191012_120607_add_some_column_to_models_list_and_models_variations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_list}}','brend_id', $this->integer());
        $this->addColumn('{{%models_variations}}','code', $this->string(30));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%models_list}}','brend_id');
        $this->dropColumn('{{%models_variations}}','code');
    }
}
