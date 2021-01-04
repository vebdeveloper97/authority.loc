<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_list}}`.
 */
class m200807_075307_add_token_column_to_models_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_list}}', 'token', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%models_list}}', 'token');
    }
}
