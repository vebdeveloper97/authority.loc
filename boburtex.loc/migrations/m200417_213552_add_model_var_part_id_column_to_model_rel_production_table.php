<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_rel_production}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_var_part}}`
 */
class m200417_213552_add_model_var_part_id_column_to_model_rel_production_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_rel_production}}', 'model_var_part_id', $this->integer());

        // creates index for column `model_var_part_id`
        $this->createIndex(
            '{{%idx-model_rel_production-model_var_part_id}}',
            '{{%model_rel_production}}',
            'model_var_part_id'
        );

        // add foreign key for table `{{%model_var_part}}`
        $this->addForeignKey(
            '{{%fk-model_rel_production-model_var_part_id}}',
            '{{%model_rel_production}}',
            'model_var_part_id',
            '{{%model_variation_parts}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_var_part}}`
        $this->dropForeignKey(
            '{{%fk-model_rel_production-model_var_part_id}}',
            '{{%model_rel_production}}'
        );

        // drops index for column `model_var_part_id`
        $this->dropIndex(
            '{{%idx-model_rel_production-model_var_part_id}}',
            '{{%model_rel_production}}'
        );

        $this->dropColumn('{{%model_rel_production}}', 'model_var_part_id');
    }
}
