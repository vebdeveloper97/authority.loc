<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_var_baski}}` and `{{%model_var_prints}}` and `{{%model_var_stone}}`.
 */
class m191028_075058_add_some_column_to_model_var_baski_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_var_baski}}', 'code', $this->string(30));
        $this->addColumn('{{%model_var_baski}}', 'desen_no', $this->string(30));
        $this->addColumn('{{%model_var_prints}}', 'code', $this->string(30));
        $this->addColumn('{{%model_var_prints}}', 'desen_no', $this->string(30));
        $this->addColumn('{{%model_var_stone}}', 'code', $this->string(30));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_var_baski}}', 'code');
        $this->dropColumn('{{%model_var_baski}}', 'desen_no');
        $this->dropColumn('{{%model_var_prints}}', 'code');
        $this->dropColumn('{{%model_var_prints}}', 'desen_no');
        $this->dropColumn('{{%model_var_stone}}', 'code');
    }
}
