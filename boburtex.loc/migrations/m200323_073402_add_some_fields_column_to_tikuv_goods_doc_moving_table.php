<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_goods_doc_moving}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_list}}`
 * - `{{%models_variations}}`
 */
class m200323_073402_add_some_fields_column_to_tikuv_goods_doc_moving_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_goods_doc_moving}}', 'model_list_id', $this->integer());
        $this->addColumn('{{%tikuv_goods_doc_moving}}', 'model_var_id', $this->integer());

        // creates index for column `model_list_id`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc_moving-model_list_id}}',
            '{{%tikuv_goods_doc_moving}}',
            'model_list_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-tikuv_goods_doc_moving-model_list_id}}',
            '{{%tikuv_goods_doc_moving}}',
            'model_list_id',
            '{{%models_list}}',
            'id'
        );

        // creates index for column `model_var_id`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc_moving-model_var_id}}',
            '{{%tikuv_goods_doc_moving}}',
            'model_var_id'
        );

        // add foreign key for table `{{%models_variations}}`
        $this->addForeignKey(
            '{{%fk-tikuv_goods_doc_moving-model_var_id}}',
            '{{%tikuv_goods_doc_moving}}',
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
            '{{%fk-tikuv_goods_doc_moving-model_list_id}}',
            '{{%tikuv_goods_doc_moving}}'
        );

        // drops index for column `model_list_id`
        $this->dropIndex(
            '{{%idx-tikuv_goods_doc_moving-model_list_id}}',
            '{{%tikuv_goods_doc_moving}}'
        );

        // drops foreign key for table `{{%models_variations}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_goods_doc_moving-model_var_id}}',
            '{{%tikuv_goods_doc_moving}}'
        );

        // drops index for column `model_var_id`
        $this->dropIndex(
            '{{%idx-tikuv_goods_doc_moving-model_var_id}}',
            '{{%tikuv_goods_doc_moving}}'
        );

        $this->dropColumn('{{%tikuv_goods_doc_moving}}', 'model_list_id');
        $this->dropColumn('{{%tikuv_goods_doc_moving}}', 'model_var_id');
    }
}
