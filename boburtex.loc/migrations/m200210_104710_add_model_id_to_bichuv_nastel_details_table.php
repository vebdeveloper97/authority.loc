<?php

use yii\db\Migration;

/**
 * Class m200210_104710_add_model_id_to_bichuv_nastel_details_table
 */
class m200210_104710_add_model_id_to_bichuv_nastel_details_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_nastel_details','model_id', $this->smallInteger(6));

        // creates index for column `model_id`
        $this->createIndex(
            '{{%idx-bichuv_nastel_details-model_id}}',
            '{{%bichuv_nastel_details}}',
            'model_id'
        );

        // add foreign key for table `{{%toquv_documents}}`
        $this->addForeignKey(
            '{{%fk-bichuv_nastel_details-model_id}}',
            '{{%bichuv_nastel_details}}',
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
            '{{%fk-bichuv_nastel_details-model_id}}',
            '{{%bichuv_nastel_details}}'
        );

        // drops index for column `model_id`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_details-model_id}}',
            '{{%bichuv_nastel_details}}'
        );
        $this->dropColumn('bichuv_nastel_details','model_id');
    }

}
