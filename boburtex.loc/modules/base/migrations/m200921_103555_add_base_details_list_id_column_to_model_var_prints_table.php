<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_var_prints}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%base_detail_lists}}`
 */
class m200921_103555_add_base_details_list_id_column_to_model_var_prints_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_var_prints}}', 'base_details_list_id', $this->integer());

        // creates index for column `base_details_list_id`
        $this->createIndex(
            '{{%idx-model_var_prints-base_details_list_id}}',
            '{{%model_var_prints}}',
            'base_details_list_id'
        );

        // add foreign key for table `{{%base_detail_lists}}`
        $this->addForeignKey(
            '{{%fk-model_var_prints-base_details_list_id}}',
            '{{%model_var_prints}}',
            'base_details_list_id',
            '{{%base_detail_lists}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%base_detail_lists}}`
        $this->dropForeignKey(
            '{{%fk-model_var_prints-base_details_list_id}}',
            '{{%model_var_prints}}'
        );

        // drops index for column `base_details_list_id`
        $this->dropIndex(
            '{{%idx-model_var_prints-base_details_list_id}}',
            '{{%model_var_prints}}'
        );

        $this->dropColumn('{{%model_var_prints}}', 'base_details_list_id');
    }
}
