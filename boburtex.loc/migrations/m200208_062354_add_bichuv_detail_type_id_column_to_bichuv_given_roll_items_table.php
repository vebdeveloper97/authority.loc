<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_given_roll_items}}`.
 */
class m200208_062354_add_bichuv_detail_type_id_column_to_bichuv_given_roll_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_given_roll_items','bichuv_detail_type_id', $this->integer());

        // creates index for column `bichuv_detail_type_id`
        $this->createIndex(
            '{{%idx-bichuv_given_roll_items-bichuv_detail_type_id}}',
            '{{%bichuv_given_roll_items}}',
            'bichuv_detail_type_id'
        );

        // add foreign key for table `{{%bichuv_detail_type_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_given_roll_items-bichuv_detail_type_id}}',
            '{{%bichuv_given_roll_items}}',
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
            '{{%fk-bichuv_given_roll_items-bichuv_detail_type_id}}',
            '{{%bichuv_given_roll_items}}'
        );

        // drops index for column `bichuv_detail_type_id`
        $this->dropIndex(
            '{{%idx-bichuv_given_roll_items-bichuv_detail_type_id}}',
            '{{%bichuv_given_roll_items}}'
        );
        $this->dropColumn('bichuv_given_roll_items','bichuv_detail_type_id');
    }
}
