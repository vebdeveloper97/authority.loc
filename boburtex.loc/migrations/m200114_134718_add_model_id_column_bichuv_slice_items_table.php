<?php

use yii\db\Migration;

/**
 * Class m200114_134718_add_model_id_column_bichuv_slice_items_table
 */
class m200114_134718_add_model_id_column_bichuv_slice_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_slice_items','model_id', $this->smallInteger(6));

        // creates index for column `model_id`
        $this->createIndex(
            '{{%idx-bichuv_slice_items-model_id}}',
            '{{%bichuv_slice_items}}',
            'model_id'
        );

        // add foreign key for table `{{%toquv_documents}}`
        $this->addForeignKey(
            '{{%fk-bichuv_slice_items-model_id}}',
            '{{%bichuv_slice_items}}',
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
            '{{%fk-bichuv_slice_items-model_id}}',
            '{{%bichuv_slice_items}}'
        );

        // drops index for column `model_id`
        $this->dropIndex(
            '{{%idx-bichuv_slice_items-model_id}}',
            '{{%bichuv_slice_items}}'
        );
        $this->dropColumn('bichuv_slice_items','model_id');
    }
}
