<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_goods_doc_accepted}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_list}}`
 * - `{{%model_var}}`
 */
class m200326_191910_add_solme_fields_column_to_tikuv_goods_doc_accepted_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_goods_doc_accepted}}', 'model_list_id', $this->integer());
        $this->addColumn('{{%tikuv_goods_doc_accepted}}', 'model_var_id', $this->integer());

        // creates index for column `model_list_id`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc_accepted-model_list_id}}',
            '{{%tikuv_goods_doc_accepted}}',
            'model_list_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-tikuv_goods_doc_accepted-model_list_id}}',
            '{{%tikuv_goods_doc_accepted}}',
            'model_list_id',
            '{{%models_list}}',
            'id'
        );

        // creates index for column `model_var_id`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc_accepted-model_var_id}}',
            '{{%tikuv_goods_doc_accepted}}',
            'model_var_id'
        );

        // add foreign key for table `{{%models_variations}}`
        $this->addForeignKey(
            '{{%fk-tikuv_goods_doc_accepted-model_var_id}}',
            '{{%tikuv_goods_doc_accepted}}',
            'model_var_id',
            '{{%models_variations}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%models_list}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_goods_doc_accepted-model_list_id}}',
            '{{%tikuv_goods_doc_accepted}}'
        );

        // drops index for column `model_list_id`
        $this->dropIndex(
            '{{%idx-tikuv_goods_doc_accepted-model_list_id}}',
            '{{%tikuv_goods_doc_accepted}}'
        );

        // drops foreign key for table `{{%models_variations}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_goods_doc_accepted-model_var_id}}',
            '{{%tikuv_goods_doc_accepted}}'
        );

        // drops index for column `model_var_id`
        $this->dropIndex(
            '{{%idx-tikuv_goods_doc_accepted-model_var_id}}',
            '{{%tikuv_goods_doc_accepted}}'
        );

        $this->dropColumn('{{%tikuv_goods_doc_accepted}}', 'model_list_id');
        $this->dropColumn('{{%tikuv_goods_doc_accepted}}', 'model_var_id');
    }
}
