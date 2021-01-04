<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%base_method_seam}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_types}}`
 */
class m200925_114134_add_model_type_id_column_to_base_method_seam_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%base_method_seam}}', 'model_type_id', $this->integer());

        // creates index for column `model_type_id`
        $this->createIndex(
            '{{%idx-base_method_seam-model_type_id}}',
            '{{%base_method_seam}}',
            'model_type_id'
        );

        // add foreign key for table `{{%model_types}}`
        $this->addForeignKey(
            '{{%fk-base_method_seam-model_type_id}}',
            '{{%base_method_seam}}',
            'model_type_id',
            '{{%model_types}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_types}}`
        $this->dropForeignKey(
            '{{%fk-base_method_seam-model_type_id}}',
            '{{%base_method_seam}}'
        );

        // drops index for column `model_type_id`
        $this->dropIndex(
            '{{%idx-base_method_seam-model_type_id}}',
            '{{%base_method_seam}}'
        );

        $this->dropColumn('{{%base_method_seam}}', 'model_type_id');
    }
}
