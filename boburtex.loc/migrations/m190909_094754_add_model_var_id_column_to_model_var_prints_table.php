<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_var_prints}}`.
 */
class m190909_094754_add_model_var_id_column_to_model_var_prints_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('model_var_prints','model_var_id', $this->integer());
        //model_var_id
        $this->createIndex(
            'idx-model_var_prints-model_var_id',
            'model_var_prints',
            'model_var_id'
        );

        $this->addForeignKey(
            'fk-model_var_prints-model_var_id',
            'model_var_prints',
            'model_var_id',
            'models_variations',
            'id'
        );

        //model_list_id
        $this->dropForeignKey(
            'fk-model_var_prints-model_list_id',
            'model_var_prints'
        );

        $this->dropIndex(
            'idx-model_var_prints-model_list_id',
            'model_var_prints'
        );

        $this->dropColumn('model_var_prints','model_list_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       return false;
    }
}
