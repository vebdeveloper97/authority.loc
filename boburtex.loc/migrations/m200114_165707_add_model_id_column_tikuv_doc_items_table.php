<?php

use yii\db\Migration;

/**
 * Class m200114_165707_add_model_id_column_tikuv_doc_items_table
 */
class m200114_165707_add_model_id_column_tikuv_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tikuv_doc_items','model_id', $this->smallInteger(6));

        // creates index for column `model_id`
        $this->createIndex(
            '{{%idx-tikuv_doc_items-model_id}}',
            '{{%tikuv_doc_items}}',
            'model_id'
        );

        // add foreign key for table `{{%product}}`
        $this->addForeignKey(
            '{{%fk-tikuv_doc_items-model_id}}',
            '{{%tikuv_doc_items}}',
            'model_id',
            '{{%product}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_id}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_doc_items-model_id}}',
            '{{%tikuv_doc_items}}'
        );

        // drops index for column `model_id`
        $this->dropIndex(
            '{{%idx-tikuv_doc_items-model_id}}',
            '{{%tikuv_doc_items}}'
        );
        $this->dropColumn('tikuv_doc_items','model_id');
    }


}
