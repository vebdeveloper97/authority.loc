<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_tables_users}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bichuv_tables}}`
 * - `{{%users}}`
 */
class m200224_172109_create_junction_table_for_bichuv_tables_and_users_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_tables_users}}', [
            'bichuv_tables_id' => $this->integer(),
            'users_id' => $this->bigInteger(),
            'PRIMARY KEY(bichuv_tables_id, users_id)',
            'type' => $this->smallInteger(1),
            'status' => $this->smallInteger(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        // creates index for column `bichuv_tables_id`
        $this->createIndex(
            '{{%idx-bichuv_tables_users-bichuv_tables_id}}',
            '{{%bichuv_tables_users}}',
            'bichuv_tables_id'
        );

        // add foreign key for table `{{%bichuv_tables}}`
        $this->addForeignKey(
            '{{%fk-bichuv_tables_users-bichuv_tables_id}}',
            '{{%bichuv_tables_users}}',
            'bichuv_tables_id',
            '{{%bichuv_tables}}',
            'id',
            'CASCADE'
        );

        // creates index for column `users_id`
        $this->createIndex(
            '{{%idx-bichuv_tables_users-users_id}}',
            '{{%bichuv_tables_users}}',
            'users_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-bichuv_tables_users-users_id}}',
            '{{%bichuv_tables_users}}',
            'users_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        $this->addColumn("{{%bichuv_nastel_processes}}", "bichuv_given_roll_items_id", $this->integer());

        // creates index for column `bichuv_given_roll_items_id`
        $this->createIndex(
            '{{%idx-bichuv_given_roll_items_id}}',
            '{{%bichuv_nastel_processes}}',
            'bichuv_given_roll_items_id'
        );

        // add foreign key for table `{{%bichuv_given_roll_items}}`
        $this->addForeignKey(
            '{{%fk-bichuv_given_roll_items_id}}',
            '{{%bichuv_nastel_processes}}',
            'bichuv_given_roll_items_id',
            '{{%bichuv_given_roll_items}}',
            'id'
        );

        $this->addColumn("{{%bichuv_detail_types}}", "bichuv_process_id", $this->integer());

        // creates index for column `bichuv_process_id`
        $this->createIndex(
            '{{%idx-bichuv_process_id}}',
            '{{%bichuv_detail_types}}',
            'bichuv_process_id'
        );

        // add foreign key for table `{{%bichuv_processes}}`
        $this->addForeignKey(
            '{{%fk-bichuv_process_id}}',
            '{{%bichuv_detail_types}}',
            'bichuv_process_id',
            '{{%bichuv_processes}}',
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
            '{{%fk-bichuv_given_roll_items_id}}',
            '{{%bichuv_nastel_processes}}'
        );

        // drops index for column `bichuv_given_roll_items_id`
        $this->dropIndex(
            '{{%idx-bichuv_given_roll_items_id}}',
            '{{%bichuv_nastel_processes}}'
        );

        $this->dropColumn('{{%bichuv_nastel_processes}}', 'bichuv_given_roll_items_id');

        // drops foreign key for table `{{%bichuv_detail_types}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_process_id}}',
            '{{%bichuv_detail_types}}'
        );

        // drops index for column `bichuv_process_id`
        $this->dropIndex(
            '{{%idx-bichuv_process_id}}',
            '{{%bichuv_detail_types}}'
        );

        $this->dropColumn('{{%bichuv_detail_types}}', 'bichuv_process_id');

        // drops foreign key for table `{{%bichuv_tables}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_tables_users-bichuv_tables_id}}',
            '{{%bichuv_tables_users}}'
        );

        // drops index for column `bichuv_tables_id`
        $this->dropIndex(
            '{{%idx-bichuv_tables_users-bichuv_tables_id}}',
            '{{%bichuv_tables_users}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_tables_users-users_id}}',
            '{{%bichuv_tables_users}}'
        );

        // drops index for column `users_id`
        $this->dropIndex(
            '{{%idx-bichuv_tables_users-users_id}}',
            '{{%bichuv_tables_users}}'
        );

        $this->dropTable('{{%bichuv_tables_users}}');
    }
}
