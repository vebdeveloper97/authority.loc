<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_slice_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bichuv_given_roll_items}}`
 */
class m200903_111946_add_bgri_id_column_to_bichuv_slice_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_slice_items}}', 'bgri_id', $this->integer());

        // creates index for column `bgri_id`
        $this->createIndex(
            '{{%idx-bichuv_slice_items-bgri_id}}',
            '{{%bichuv_slice_items}}',
            'bgri_id'
        );

        // add foreign key for table `{{%bichuv_given_roll_items}}`
        $this->addForeignKey(
            '{{%fk-bichuv_slice_items-bgri_id}}',
            '{{%bichuv_slice_items}}',
            'bgri_id',
            '{{%bichuv_given_roll_items}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_given_roll_items}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_slice_items-bgri_id}}',
            '{{%bichuv_slice_items}}'
        );

        // drops index for column `bgri_id`
        $this->dropIndex(
            '{{%idx-bichuv_slice_items-bgri_id}}',
            '{{%bichuv_slice_items}}'
        );

        $this->dropColumn('{{%bichuv_slice_items}}', 'bgri_id');
    }
}
