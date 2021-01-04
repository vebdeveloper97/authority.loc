<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_given_roll_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bichuv_processes}}`
 */
class m200226_073422_add_some_column_to_bichuv_given_roll_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_given_roll_items}}', 'otxod', $this->decimal(20,3)->defaultValue(0));
        $this->addColumn('{{%bichuv_given_roll_items}}', 'remain', $this->decimal(20,3)->defaultValue(0));
        $this->addColumn('{{%bichuv_given_roll_items}}', 'remain_roll', $this->integer()->defaultValue(0));
        $this->addColumn('{{%bichuv_nastel_detail_items}}', 'bichuv_processes_id', $this->integer());
        $this->addColumn('{{%bichuv_nastel_detail_items}}', 'order', $this->smallInteger()->defaultValue(0));
        $this->addColumn('{{%bichuv_nastel_detail_items}}', 'brak', $this->integer()->defaultValue(0));
        $this->addColumn('{{%bichuv_nastel_detail_items}}', 'bichuv_nastel_processes_id', $this->integer());
        $this->addColumn('{{%bichuv_processes}}', 'is_countable', $this->smallInteger()->defaultValue(1));

        // creates index for column `bichuv_processes_id`
        $this->createIndex(
            '{{%idx-bichuv_nastel_detail_items-bichuv_processes_id}}',
            '{{%bichuv_nastel_detail_items}}',
            'bichuv_processes_id'
        );

        // add foreign key for table `{{%bichuv_processes}}`
        $this->addForeignKey(
            '{{%fk-bichuv_nastel_detail_items-bichuv_processes_id}}',
            '{{%bichuv_nastel_detail_items}}',
            'bichuv_processes_id',
            '{{%bichuv_processes}}',
            'id',
            'CASCADE'
        );

        // creates index for column `bichuv_nastel_processes_id`
        $this->createIndex(
            '{{%idx-bichuv_nastel_detail_items-bichuv_nastel_processes_id}}',
            '{{%bichuv_nastel_detail_items}}',
            'bichuv_nastel_processes_id'
        );

        // add foreign key for table `{{%bichuv_nastel_processes_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_nastel_detail_items-bichuv_nastel_processes_id}}',
            '{{%bichuv_nastel_detail_items}}',
            'bichuv_nastel_processes_id',
            '{{%bichuv_nastel_processes}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_processes}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_detail_items-bichuv_processes_id}}',
            '{{%bichuv_nastel_detail_items}}'
        );

        // drops index for column `bichuv_processes_id`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_detail_items-bichuv_processes_id}}',
            '{{%bichuv_nastel_detail_items}}'
        );

        // drops foreign key for table `{{%bichuv_nastel_processes_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_detail_items-bichuv_nastel_processes_id}}',
            '{{%bichuv_nastel_detail_items}}'
        );

        // drops index for column `bichuv_nastel_processes_id`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_detail_items-bichuv_nastel_processes_id}}',
            '{{%bichuv_nastel_detail_items}}'
        );
        $this->dropColumn('{{%bichuv_given_roll_items}}', 'otxod');
        $this->dropColumn('{{%bichuv_given_roll_items}}', 'remain');
        $this->dropColumn('{{%bichuv_given_roll_items}}', 'remain_roll');
        $this->dropColumn('{{%bichuv_nastel_detail_items}}', 'bichuv_processes_id');
        $this->dropColumn('{{%bichuv_nastel_detail_items}}', 'order');
        $this->dropColumn('{{%bichuv_nastel_detail_items}}', 'brak');
        $this->dropColumn('{{%bichuv_nastel_detail_items}}', 'bichuv_nastel_processes_id');
        $this->dropColumn('{{%bichuv_processes}}', 'is_countable');
    }
}
