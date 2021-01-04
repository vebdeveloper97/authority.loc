<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_given_roll_items_sub}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bichuv_nastel_processes}}`
 * - `{{%bichuv_given_roll_items}}`
 */
class m200226_195849_create_bichuv_given_roll_items_sub_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_given_roll_items_sub}}', [
            'id' => $this->primaryKey(),
            'remain' => $this->decimal(20,3)->defaultValue(0),
            'roll_remain' => $this->integer()->defaultValue(0),
            'otxod' => $this->decimal(20,3)->defaultValue(0),
            'bichuv_nastel_processes_id' => $this->integer(),
            'bichuv_given_roll_items_id' => $this->integer(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `bichuv_nastel_processes_id`
        $this->createIndex(
            '{{%idx-bichuv_given_roll_items_sub-bichuv_nastel_processes_id}}',
            '{{%bichuv_given_roll_items_sub}}',
            'bichuv_nastel_processes_id'
        );

        // add foreign key for table `{{%bichuv_nastel_processes}}`
        $this->addForeignKey(
            '{{%fk-bichuv_given_roll_items_sub-bichuv_nastel_processes_id}}',
            '{{%bichuv_given_roll_items_sub}}',
            'bichuv_nastel_processes_id',
            '{{%bichuv_nastel_processes}}',
            'id',
            'CASCADE'
        );

        // creates index for column `bichuv_given_roll_items_id`
        $this->createIndex(
            '{{%idx-bichuv_given_roll_items_sub-bichuv_given_roll_items_id}}',
            '{{%bichuv_given_roll_items_sub}}',
            'bichuv_given_roll_items_id'
        );

        // add foreign key for table `{{%bichuv_given_roll_items}}`
        $this->addForeignKey(
            '{{%fk-bichuv_given_roll_items_sub-bichuv_given_roll_items_id}}',
            '{{%bichuv_given_roll_items_sub}}',
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
        // drops foreign key for table `{{%bichuv_nastel_processes}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_given_roll_items_sub-bichuv_nastel_processes_id}}',
            '{{%bichuv_given_roll_items_sub}}'
        );

        // drops index for column `bichuv_nastel_processes_id`
        $this->dropIndex(
            '{{%idx-bichuv_given_roll_items_sub-bichuv_nastel_processes_id}}',
            '{{%bichuv_given_roll_items_sub}}'
        );

        // drops foreign key for table `{{%bichuv_given_roll_items}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_given_roll_items_sub-bichuv_given_roll_items_id}}',
            '{{%bichuv_given_roll_items_sub}}'
        );

        // drops index for column `bichuv_given_roll_items_id`
        $this->dropIndex(
            '{{%idx-bichuv_given_roll_items_sub-bichuv_given_roll_items_id}}',
            '{{%bichuv_given_roll_items_sub}}'
        );

        $this->dropTable('{{%bichuv_given_roll_items_sub}}');
    }
}
