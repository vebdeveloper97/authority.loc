<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_tables}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bichuv_processes}}`
 */
class m200213_064529_create_bichuv_tables_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_tables}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100),
            'bichuv_processes_id' => $this->integer(),
            'type' => $this->smallInteger(1)->defaultValue(1),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `bichuv_processes_id`
        $this->createIndex(
            '{{%idx-bichuv_tables-bichuv_processes_id}}',
            '{{%bichuv_tables}}',
            'bichuv_processes_id'
        );

        // add foreign key for table `{{%bichuv_processes}}`
        $this->addForeignKey(
            '{{%fk-bichuv_tables-bichuv_processes_id}}',
            '{{%bichuv_tables}}',
            'bichuv_processes_id',
            '{{%bichuv_processes}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_processes}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_tables-bichuv_processes_id}}',
            '{{%bichuv_tables}}'
        );

        // drops index for column `bichuv_processes_id`
        $this->dropIndex(
            '{{%idx-bichuv_tables-bichuv_processes_id}}',
            '{{%bichuv_tables}}'
        );

        $this->dropTable('{{%bichuv_tables}}');
    }
}
