<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 20.02.20 15:45
 */

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_nastel_details}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bichuv_given_roll_items}}`
 */
class m200220_104103_add_bichuv_given_roll_itemss_id_to_bichuv_nastel_details extends Migration
{
    /**
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_nastel_details}}', 'bichuv_given_roll_items_id', $this->integer());

        // creates index for column `bichuv_given_roll_items_id`
        $this->createIndex(
            '{{%idx-bichuv_nastel_details-bichuv_given_roll_items_id}}',
            '{{%bichuv_nastel_details}}',
            'bichuv_given_roll_items_id'
        );

        // add foreign key for table `{{%bichuv_given_roll_items}}`
        $this->addForeignKey(
            '{{%fk-bichuv_nastel_details-bichuv_given_roll_items_id}}',
            '{{%bichuv_nastel_details}}',
            'bichuv_given_roll_items_id',
            '{{%bichuv_given_roll_items}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_given_roll_items}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_details-bichuv_given_roll_items_id}}',
            '{{%bichuv_nastel_details}}'
        );

        // drops index for column `bichuv_given_roll_items_id`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_details-bichuv_given_roll_items_id}}',
            '{{%bichuv_nastel_details}}'
        );

        $this->dropColumn('{{%bichuv_nastel_details}}', 'bichuv_given_roll_items_id');
    }
}
