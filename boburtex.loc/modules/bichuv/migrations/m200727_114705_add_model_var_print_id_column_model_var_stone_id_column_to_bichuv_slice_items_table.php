<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_slice_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_var_prints}}`
 * - `{{%model_var_stone}}`
 */
class m200727_114705_add_model_var_print_id_column_model_var_stone_id_column_to_bichuv_slice_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_slice_items}}', 'model_var_print_id', $this->integer());
        $this->addColumn('{{%bichuv_slice_items}}', 'model_var_stone_id', $this->integer());

        // creates index for column `model_var_print_id`
        $this->createIndex(
            '{{%idx-bichuv_slice_items-model_var_print_id}}',
            '{{%bichuv_slice_items}}',
            'model_var_print_id'
        );

        // add foreign key for table `{{%model_var_prints}}`
        $this->addForeignKey(
            '{{%fk-bichuv_slice_items-model_var_print_id}}',
            '{{%bichuv_slice_items}}',
            'model_var_print_id',
            '{{%model_var_prints}}',
            'id'
        );

        // creates index for column `model_var_stone_id`
        $this->createIndex(
            '{{%idx-bichuv_slice_items-model_var_stone_id}}',
            '{{%bichuv_slice_items}}',
            'model_var_stone_id'
        );

        // add foreign key for table `{{%model_var_stone}}`
        $this->addForeignKey(
            '{{%fk-bichuv_slice_items-model_var_stone_id}}',
            '{{%bichuv_slice_items}}',
            'model_var_stone_id',
            '{{%model_var_stone}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_var_prints}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_slice_items-model_var_print_id}}',
            '{{%bichuv_slice_items}}'
        );

        // drops index for column `model_var_print_id`
        $this->dropIndex(
            '{{%idx-bichuv_slice_items-model_var_print_id}}',
            '{{%bichuv_slice_items}}'
        );

        // drops foreign key for table `{{%model_var_stone}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_slice_items-model_var_stone_id}}',
            '{{%bichuv_slice_items}}'
        );

        // drops index for column `model_var_stone_id`
        $this->dropIndex(
            '{{%idx-bichuv_slice_items-model_var_stone_id}}',
            '{{%bichuv_slice_items}}'
        );

        $this->dropColumn('{{%bichuv_slice_items}}', 'model_var_print_id');
        $this->dropColumn('{{%bichuv_slice_items}}', 'model_var_stone_id');
    }
}
