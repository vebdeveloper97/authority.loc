<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_var_prints}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_list}}`
 */
class m200921_104400_add_models_list_id_column_to_model_var_prints_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_var_prints}}', 'models_list_id', $this->integer());

        // creates index for column `models_list_id`
        $this->createIndex(
            '{{%idx-model_var_prints-models_list_id}}',
            '{{%model_var_prints}}',
            'models_list_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-model_var_prints-models_list_id}}',
            '{{%model_var_prints}}',
            'models_list_id',
            '{{%models_list}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%models_list}}`
        $this->dropForeignKey(
            '{{%fk-model_var_prints-models_list_id}}',
            '{{%model_var_prints}}'
        );

        // drops index for column `models_list_id`
        $this->dropIndex(
            '{{%idx-model_var_prints-models_list_id}}',
            '{{%model_var_prints}}'
        );

        $this->dropColumn('{{%model_var_prints}}', 'models_list_id');
    }
}
