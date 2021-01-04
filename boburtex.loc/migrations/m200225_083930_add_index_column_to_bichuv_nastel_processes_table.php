<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_nastel_processes}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bichuv_tables}}`
 */
class m200225_083930_add_index_column_to_bichuv_nastel_processes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // creates index for column `bichuv_nastel_stol_id`
        $this->createIndex(
            '{{%idx-bichuv_nastel_processes-bichuv_nastel_stol_id}}',
            '{{%bichuv_nastel_processes}}',
            'bichuv_nastel_stol_id'
        );

        // add foreign key for table `{{%bichuv_tables}}`
        $this->addForeignKey(
            '{{%fk-bichuv_nastel_processes-bichuv_nastel_stol_id}}',
            '{{%bichuv_nastel_processes}}',
            'bichuv_nastel_stol_id',
            '{{%bichuv_tables}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_tables}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_processes-bichuv_nastel_stol_id}}',
            '{{%bichuv_nastel_processes}}'
        );

        // drops index for column `bichuv_nastel_stol_id`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_processes-bichuv_nastel_stol_id}}',
            '{{%bichuv_nastel_processes}}'
        );
    }
}
