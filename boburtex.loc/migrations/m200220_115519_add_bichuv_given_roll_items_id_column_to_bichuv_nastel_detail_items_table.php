<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_nastel_detail_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bichuv_given_roll_items}}`
 */
class m200220_115519_add_bichuv_given_roll_items_id_column_to_bichuv_nastel_detail_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_given_rolls}}', 'size_collection_id', $this->integer());
        $this->addColumn('{{%bichuv_nastel_detail_items}}', 'bichuv_given_roll_items_id', $this->integer());

        // creates index for column `bichuv_given_roll_items_id`
        $this->createIndex(
            '{{%idx-bichuv_nastel_detail_items-bichuv_given_roll_items_id}}',
            '{{%bichuv_nastel_detail_items}}',
            'bichuv_given_roll_items_id'
        );

        // add foreign key for table `{{%bichuv_given_roll_items}}`
        $this->addForeignKey(
            '{{%fk-bichuv_nastel_detail_items-bichuv_given_roll_items_id}}',
            '{{%bichuv_nastel_detail_items}}',
            'bichuv_given_roll_items_id',
            '{{%bichuv_given_roll_items}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_given_roll_items}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_detail_items-bichuv_given_roll_items_id}}',
            '{{%bichuv_nastel_detail_items}}'
        );

        // drops index for column `bichuv_given_roll_items_id`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_detail_items-bichuv_given_roll_items_id}}',
            '{{%bichuv_nastel_detail_items}}'
        );

        $this->dropColumn('{{%bichuv_nastel_detail_items}}', 'bichuv_given_roll_items_id');
        $this->dropColumn('{{%bichuv_given_rolls}}', 'size_collection_id');
    }
}
