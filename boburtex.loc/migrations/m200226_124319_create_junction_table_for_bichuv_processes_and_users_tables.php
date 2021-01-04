<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_processes_users}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bichuv_processes}}`
 * - `{{%users}}`
 */
class m200226_124319_create_junction_table_for_bichuv_processes_and_users_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_processes_users}}', [
            'bichuv_processes_id' => $this->integer(),
            'users_id' => $this->bigInteger(),
            'PRIMARY KEY(bichuv_processes_id, users_id)',
            'type' => $this->smallInteger(1),
            'status' => $this->smallInteger(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        // creates index for column `bichuv_processes_id`
        $this->createIndex(
            '{{%idx-bichuv_processes_users-bichuv_processes_id}}',
            '{{%bichuv_processes_users}}',
            'bichuv_processes_id'
        );

        // add foreign key for table `{{%bichuv_processes}}`
        $this->addForeignKey(
            '{{%fk-bichuv_processes_users-bichuv_processes_id}}',
            '{{%bichuv_processes_users}}',
            'bichuv_processes_id',
            '{{%bichuv_processes}}',
            'id',
            'CASCADE'
        );

        // creates index for column `users_id`
        $this->createIndex(
            '{{%idx-bichuv_processes_users-users_id}}',
            '{{%bichuv_processes_users}}',
            'users_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-bichuv_processes_users-users_id}}',
            '{{%bichuv_processes_users}}',
            'users_id',
            '{{%users}}',
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
            '{{%fk-bichuv_processes_users-bichuv_processes_id}}',
            '{{%bichuv_processes_users}}'
        );

        // drops index for column `bichuv_processes_id`
        $this->dropIndex(
            '{{%idx-bichuv_processes_users-bichuv_processes_id}}',
            '{{%bichuv_processes_users}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_processes_users-users_id}}',
            '{{%bichuv_processes_users}}'
        );

        // drops index for column `users_id`
        $this->dropIndex(
            '{{%idx-bichuv_processes_users-users_id}}',
            '{{%bichuv_processes_users}}'
        );

        $this->dropTable('{{%bichuv_processes_users}}');
    }
}
