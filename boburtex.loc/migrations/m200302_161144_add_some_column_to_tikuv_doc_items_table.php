<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_doc_items}}`.
 */
class m200302_161144_add_some_column_to_tikuv_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tikuv_doc_items','boyoqhona_model_id', $this->smallInteger(6));
        $this->addColumn('tikuv_doc_items','model_var_id', $this->integer());
        $this->addColumn('tikuv_slice_item_balance','boyoqhona_model_id', $this->smallInteger(6));

        // drops foreign key for table `{{%product}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_doc_items-model_id}}',
            '{{%tikuv_doc_items}}'
        );
        // drops index for column `model_id`
        $this->dropIndex(
            '{{%idx-tikuv_doc_items-model_id}}',
            '{{%tikuv_doc_items}}'
        );

        // drops foreign key for table `{{%product}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_slice_item_balance-model_id}}',
            '{{%tikuv_slice_item_balance}}'
        );
        // drops index for column `model_id`
        $this->dropIndex(
            '{{%idx-tikuv_slice_item_balance-model_id}}',
            '{{%tikuv_slice_item_balance}}'
        );

        $this->dropColumn('tikuv_doc_items','model_id');
        $this->dropColumn('tikuv_slice_item_balance','model_id');

        $this->addColumn('tikuv_doc_items','model_id', $this->integer());
        $this->addColumn('tikuv_slice_item_balance','model_id', $this->integer());

        // creates index for column `model_id`
        $this->createIndex(
            '{{%idx-tikuv_doc_items-model_id}}',
            '{{%tikuv_doc_items}}',
            'model_id'
        );
        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-tikuv_doc_items-model_id}}',
            '{{%tikuv_doc_items}}',
            'model_id',
            '{{%models_list}}',
            'id'
        );

        // creates index for column `boyoqhona_model_id`
        $this->createIndex(
            '{{%idx-tikuv_doc_items-boyoqhona_model_id}}',
            '{{%tikuv_doc_items}}',
            'boyoqhona_model_id'
        );
        // add foreign key for table `{{%product}}`
        $this->addForeignKey(
            '{{%fk-tikuv_doc_items-boyoqhona_model_id}}',
            '{{%tikuv_doc_items}}',
            'boyoqhona_model_id',
            '{{%product}}',
            'id'
        );

        // creates index for column `boyoqhona_model_id`
        $this->createIndex(
            '{{%idx-tikuv_slice_item_balance-boyoqhona_model_id}}',
            '{{%tikuv_slice_item_balance}}',
            'boyoqhona_model_id'
        );
        // add foreign key for table `{{%product}}`
        $this->addForeignKey(
            '{{%fk-tikuv_slice_item_balance-boyoqhona_model_id}}',
            '{{%tikuv_slice_item_balance}}',
            'boyoqhona_model_id',
            '{{%product}}',
            'id'
        );

        // creates index for column `model_id`
        $this->createIndex(
            '{{%idx-tikuv_slice_item_balance-model_id}}',
            '{{%tikuv_slice_item_balance}}',
            'model_id'
        );
        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-tikuv_slice_item_balance-model_id}}',
            '{{%tikuv_slice_item_balance}}',
            'model_id',
            '{{%models_list}}',
            'id'
        );

        // creates index for column `model_var_id`
        $this->createIndex(
            '{{%idx-tikuv_doc_items-model_var_id}}',
            '{{%tikuv_doc_items}}',
            'model_var_id'
        );
        // add foreign key for table `{{%models_variations}}`
        $this->addForeignKey(
            '{{%fk-tikuv_doc_items-model_var_id}}',
            '{{%tikuv_doc_items}}',
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
        // drops foreign key for table `{{%product}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_doc_items-boyoqhona_model_id}}',
            '{{%tikuv_doc_items}}'
        );
        // drops index for column `boyoqhona_model_id`
        $this->dropIndex(
            '{{%idx-tikuv_doc_items-boyoqhona_model_id}}',
            '{{%tikuv_doc_items}}'
        );

        // drops foreign key for table `{{%models_variations}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_doc_items-model_var_id}}',
            '{{%tikuv_doc_items}}'
        );
        // drops index for column `model_var_id`
        $this->dropIndex(
            '{{%idx-tikuv_doc_items-model_var_id}}',
            '{{%tikuv_doc_items}}'
        );

        // drops foreign key for table `{{%ikuv_slice_item_balance}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_slice_item_balance-boyoqhona_model_id}}',
            '{{%tikuv_slice_item_balance}}'
        );
        // drops index for column `boyoqhona_model_id`
        $this->dropIndex(
            '{{%idx-tikuv_slice_item_balance-boyoqhona_model_id}}',
            '{{%tikuv_slice_item_balance}}'
        );

        $this->dropColumn('tikuv_doc_items','model_var_id');
        $this->dropColumn('tikuv_slice_item_balance','boyoqhona_model_id');
        $this->dropColumn('tikuv_doc_items','boyoqhona_model_id');
    }
}
