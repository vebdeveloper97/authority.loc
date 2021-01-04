<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_nastel_details}}`.
 */
class m200211_155042_add_nastel_count_column_to_bichuv_nastel_details_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_nastel_details','nastel_count', $this->integer());
        $this->addColumn('bichuv_nastel_details','bichuv_given_roll_id', $this->integer());

        // creates index for column `bichuv_given_roll_id`
        $this->createIndex(
            '{{%idx-bichuv_nastel_details-bichuv_given_roll_id}}',
            '{{%bichuv_nastel_details}}',
            'bichuv_given_roll_id'
        );

        // add foreign key for table `{{%toquv_documents}}`
        $this->addForeignKey(
            '{{%fk-bichuv_nastel_details-bichuv_given_roll_id}}',
            '{{%bichuv_nastel_details}}',
            'bichuv_given_roll_id',
            '{{%bichuv_given_rolls}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_given_roll_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_details-bichuv_given_roll_id}}',
            '{{%bichuv_nastel_details}}'
        );

        // drops index for column `bichuv_given_roll_id`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_details-bichuv_given_roll_id}}',
            '{{%bichuv_nastel_details}}'
        );
        $this->dropColumn('bichuv_nastel_details','nastel_count');
        $this->dropColumn('bichuv_nastel_details','bichuv_given_roll_id');
    }
}
