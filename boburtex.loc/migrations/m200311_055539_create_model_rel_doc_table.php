<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_rel_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_list}}`
 * - `{{%models_variations}}`
 * - `{{%tikuv_doc}}`
 */
class m200311_055539_create_model_rel_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_rel_doc}}', [
            'id' => $this->primaryKey(),
            'model_list_id' => $this->integer(),
            'model_var_id' => $this->integer(),
            'tikuv_doc_id' => $this->integer(),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        // creates index for column `model_list_id`
        $this->createIndex(
            '{{%idx-model_rel_doc-model_list_id}}',
            '{{%model_rel_doc}}',
            'model_list_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-model_rel_doc-model_list_id}}',
            '{{%model_rel_doc}}',
            'model_list_id',
            '{{%models_list}}',
            'id'
        );

        // creates index for column `model_var_id`
        $this->createIndex(
            '{{%idx-model_rel_doc-model_var_id}}',
            '{{%model_rel_doc}}',
            'model_var_id'
        );

        // add foreign key for table `{{%models_variations}}`
        $this->addForeignKey(
            '{{%fk-model_rel_doc-model_var_id}}',
            '{{%model_rel_doc}}',
            'model_var_id',
            '{{%models_variations}}',
            'id'
        );

        // creates index for column `tikuv_doc_id`
        $this->createIndex(
            '{{%idx-model_rel_doc-tikuv_doc_id}}',
            '{{%model_rel_doc}}',
            'tikuv_doc_id'
        );

        // add foreign key for table `{{%tikuv_doc}}`
        $this->addForeignKey(
            '{{%fk-model_rel_doc-tikuv_doc_id}}',
            '{{%model_rel_doc}}',
            'tikuv_doc_id',
            '{{%tikuv_doc}}',
            'id'
        );

        // creates index for column `nastel_party_no`
        $this->createIndex(
            '{{%idx-tikuv_doc_items-nastel_party_no}}',
            '{{%tikuv_doc_items}}',
            'nastel_party_no'
        );

        // creates index for column `nastel_party`
        $this->createIndex(
            '{{%idx-bichuv_given_rolls-nastel_party}}',
            '{{%bichuv_given_rolls}}',
            'nastel_party'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        // drops index for column `nastel_party`
        $this->dropIndex(
            '{{%idx-bichuv_given_rolls-nastel_party}}',
            '{{%bichuv_given_rolls}}'
        );

        // drops index for column `nastel_party_no`
        $this->dropIndex(
            '{{%idx-tikuv_doc_items-nastel_party_no}}',
            '{{%tikuv_doc_items}}'
        );

        // drops foreign key for table `{{%models_list}}`
        $this->dropForeignKey(
            '{{%fk-model_rel_doc-model_list_id}}',
            '{{%model_rel_doc}}'
        );

        // drops index for column `model_list_id`
        $this->dropIndex(
            '{{%idx-model_rel_doc-model_list_id}}',
            '{{%model_rel_doc}}'
        );

        // drops foreign key for table `{{%models_variations}}`
        $this->dropForeignKey(
            '{{%fk-model_rel_doc-model_var_id}}',
            '{{%model_rel_doc}}'
        );

        // drops index for column `model_var_id`
        $this->dropIndex(
            '{{%idx-model_rel_doc-model_var_id}}',
            '{{%model_rel_doc}}'
        );

        // drops foreign key for table `{{%tikuv_doc}}`
        $this->dropForeignKey(
            '{{%fk-model_rel_doc-tikuv_doc_id}}',
            '{{%model_rel_doc}}'
        );

        // drops index for column `tikuv_doc_id`
        $this->dropIndex(
            '{{%idx-model_rel_doc-tikuv_doc_id}}',
            '{{%model_rel_doc}}'
        );

        $this->dropTable('{{%model_rel_doc}}');
    }
}
