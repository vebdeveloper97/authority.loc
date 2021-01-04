<?php

use yii\db\Migration;

/**
 * Class m200206_155006_add_bichuv_detail_type_id_to_bichuv_given_rolls_table
 */
class m200206_155006_add_bichuv_detail_type_id_to_bichuv_given_rolls_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_given_rolls','bichuv_detail_type_id', $this->integer());
        $this->addColumn('bichuv_nastel_details','type', $this->smallInteger(1)->defaultValue(1));

        // creates index for column `bichuv_detail_type_id`
        $this->createIndex(
            '{{%idx-bichuv_given_rolls-bichuv_detail_type_id}}',
            '{{%bichuv_given_rolls}}',
            'bichuv_detail_type_id'
        );

        // add foreign key for table `{{%bichuv_detail_type_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_given_rolls-bichuv_detail_type_id}}',
            '{{%bichuv_given_rolls}}',
            'bichuv_detail_type_id',
            '{{%bichuv_detail_types}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_detail_type_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_given_rolls-bichuv_detail_type_id}}',
            '{{%bichuv_given_rolls}}'
        );

        // drops index for column `bichuv_detail_type_id`
        $this->dropIndex(
            '{{%idx-bichuv_given_rolls-bichuv_detail_type_id}}',
            '{{%bichuv_given_rolls}}'
        );
        $this->dropColumn('bichuv_nastel_details','type');
        $this->dropColumn('bichuv_given_rolls','bichuv_detail_type_id');
    }

}
