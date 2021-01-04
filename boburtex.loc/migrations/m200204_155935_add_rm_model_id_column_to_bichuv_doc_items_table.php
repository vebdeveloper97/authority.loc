<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc_items}}`.
 */
class m200204_155935_add_rm_model_id_column_to_bichuv_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_doc_items','rm_model_id',$this->smallInteger(6));

        // creates index for column `rm_model_id`
        $this->createIndex(
            '{{%idx-bichuv_doc_items-rm_model_id}}',
            '{{%bichuv_doc_items}}',
            'rm_model_id'
        );
        // add foreign key for table `{{%rm_model_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_doc_items-rm_model_id}}',
            '{{%bichuv_doc_items}}',
            'rm_model_id',
            '{{%product}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%rm_model_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_doc_items-rm_model_id}}',
            '{{%bichuv_doc_items}}'
        );

        // drops index for column `rm_model_id`
        $this->dropIndex(
            '{{%idx-bichuv_doc_items-rm_model_id}}',
            '{{%bichuv_doc_items}}'
        );

        $this->dropColumn('bichuv_doc_items','rm_model_id');
    }
}
