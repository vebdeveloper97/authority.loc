<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_var_stone}}`.
 */
class m190909_094805_add_model_var_id_column_to_model_var_stone_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('model_var_stone','model_var_id', $this->integer());
        //model_var_id
        $this->createIndex(
            'idx-model_var_stone-model_var_id',
            'model_var_stone',
            'model_var_id'
        );

        $this->addForeignKey(
            'fk-model_var_stone-model_var_id',
            'model_var_stone',
            'model_var_id',
            'models_variations',
            'id'
        );

        //model_list_id
        $this->dropForeignKey(
            'fk-model_var_stone-model_list_id',
            'model_var_stone'
        );

        $this->dropIndex(
            'idx-model_var_stone-model_list_id',
            'model_var_stone'
        );

        $this->dropColumn('model_var_stone','model_list_id');
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }
}
