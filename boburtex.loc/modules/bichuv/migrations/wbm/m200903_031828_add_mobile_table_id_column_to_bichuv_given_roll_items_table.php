<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_given_roll_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%mobile_tables}}`
 */
class m200903_031828_add_mobile_table_id_column_to_bichuv_given_roll_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_given_roll_items}}', 'mobile_table_id', $this->integer());

        // creates index for column `mobile_table_id`
        $this->createIndex(
            '{{%idx-bichuv_given_roll_items-mobile_table_id}}',
            '{{%bichuv_given_roll_items}}',
            'mobile_table_id'
        );

        // add foreign key for table `{{%mobile_tables}}`
        $this->addForeignKey(
            '{{%fk-bichuv_given_roll_items-mobile_table_id}}',
            '{{%bichuv_given_roll_items}}',
            'mobile_table_id',
            '{{%mobile_tables}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%mobile_tables}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_given_roll_items-mobile_table_id}}',
            '{{%bichuv_given_roll_items}}'
        );

        // drops index for column `mobile_table_id`
        $this->dropIndex(
            '{{%idx-bichuv_given_roll_items-mobile_table_id}}',
            '{{%bichuv_given_roll_items}}'
        );

        $this->dropColumn('{{%bichuv_given_roll_items}}', 'mobile_table_id');
    }
}
