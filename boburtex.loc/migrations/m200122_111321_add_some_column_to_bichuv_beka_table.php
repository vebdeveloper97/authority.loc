<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_beka}}`.
 */
class m200122_111321_add_some_column_to_bichuv_beka_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_beka','party_no', $this->string(50));
        $this->addColumn('bichuv_beka','musteri_party_no', $this->string(50));
        $this->addColumn('bichuv_beka','roll_count', $this->integer(2));
        $this->addColumn('bichuv_beka','model_id', $this->smallInteger(6));

        // creates index for column `model_id`
        $this->createIndex(
            '{{%idx-bichuv_beka-model_id}}',
            '{{%bichuv_beka}}',
            'model_id'
        );

        // add foreign key for table `{{%toquv_documents}}`
        $this->addForeignKey(
            '{{%fk-bichuv_beka-model_id}}',
            '{{%bichuv_beka}}',
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
            '{{%fk-bichuv_beka-model_id}}',
            '{{%bichuv_beka}}'
        );

        // drops index for column `model_id`
        $this->dropIndex(
            '{{%idx-bichuv_beka-model_id}}',
            '{{%bichuv_beka}}'
        );
        $this->dropColumn('bichuv_beka','party_no');
        $this->dropColumn('bichuv_beka','musteri_party_no');
        $this->dropColumn('bichuv_beka','roll_count');
        $this->dropColumn('bichuv_beka','model_id');
    }
}
