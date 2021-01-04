<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_nastel_process_brak}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bichuv_nastel_processes_id}}`
 * - `{{%bichuv_nastel_detail_items_id}}`
 * - `{{%bichuv_given_roll_items_id}}`
 * - `{{%users}}`
 */
class m200226_210559_create_bichuv_nastel_process_brak_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_nastel_process_brak}}', [
            'id' => $this->primaryKey(),
            'quantity' => $this->decimal(20,3),
            'bichuv_nastel_processes_id' => $this->integer(),
            'bichuv_nastel_detail_items_id' => $this->integer(),
            'bichuv_given_roll_items_id' => $this->integer(),
            'users_id' => $this->bigInteger(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `bichuv_nastel_processes_id`
        $this->createIndex(
            '{{%idx-bichuv_nastel_process_brak-bichuv_nastel_processes_id}}',
            '{{%bichuv_nastel_process_brak}}',
            'bichuv_nastel_processes_id'
        );

        // add foreign key for table `{{%bichuv_nastel_processes}}`
        $this->addForeignKey(
            '{{%fk-bichuv_nastel_process_brak-bichuv_nastel_processes_id}}',
            '{{%bichuv_nastel_process_brak}}',
            'bichuv_nastel_processes_id',
            '{{%bichuv_nastel_processes}}',
            'id',
            'CASCADE'
        );

        // creates index for column `bichuv_nastel_detail_items_id`
        $this->createIndex(
            '{{%idx-bichuv_nastel_process_brak-bichuv_nastel_detail_items_id}}',
            '{{%bichuv_nastel_process_brak}}',
            'bichuv_nastel_detail_items_id'
        );

        // add foreign key for table `{{%bichuv_nastel_detail_items}}`
        $this->addForeignKey(
            '{{%fk-bichuv_nastel_process_brak-bichuv_nastel_detail_items_id}}',
            '{{%bichuv_nastel_process_brak}}',
            'bichuv_nastel_detail_items_id',
            '{{%bichuv_nastel_detail_items}}',
            'id',
            'CASCADE'
        );

        // creates index for column `bichuv_given_roll_items_id`
        $this->createIndex(
            '{{%idx-bichuv_nastel_process_brak-bichuv_given_roll_items_id}}',
            '{{%bichuv_nastel_process_brak}}',
            'bichuv_given_roll_items_id'
        );

        // add foreign key for table `{{%bichuv_given_roll_items}}`
        $this->addForeignKey(
            '{{%fk-bichuv_nastel_process_brak-bichuv_given_roll_items_id}}',
            '{{%bichuv_nastel_process_brak}}',
            'bichuv_given_roll_items_id',
            '{{%bichuv_given_roll_items}}',
            'id',
            'CASCADE'
        );

        // creates index for column `users_id`
        $this->createIndex(
            '{{%idx-bichuv_nastel_process_brak-users_id}}',
            '{{%bichuv_nastel_process_brak}}',
            'users_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-bichuv_nastel_process_brak-users_id}}',
            '{{%bichuv_nastel_process_brak}}',
            'users_id',
            '{{%users}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_nastel_processes}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_process_brak-bichuv_nastel_processes_id}}',
            '{{%bichuv_nastel_process_brak}}'
        );

        // drops index for column `bichuv_nastel_processes_id`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_process_brak-bichuv_nastel_processes_id}}',
            '{{%bichuv_nastel_process_brak}}'
        );

        // drops foreign key for table `{{%bichuv_nastel_detail_items}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_process_brak-bichuv_nastel_detail_items_id}}',
            '{{%bichuv_nastel_process_brak}}'
        );

        // drops index for column `bichuv_nastel_detail_items_id`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_process_brak-bichuv_nastel_detail_items_id}}',
            '{{%bichuv_nastel_process_brak}}'
        );

        // drops foreign key for table `{{%bichuv_given_roll_items}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_process_brak-bichuv_given_roll_items_id}}',
            '{{%bichuv_nastel_process_brak}}'
        );

        // drops index for column `bichuv_given_roll_items_id`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_process_brak-bichuv_given_roll_items_id}}',
            '{{%bichuv_nastel_process_brak}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_process_brak-users_id}}',
            '{{%bichuv_nastel_process_brak}}'
        );

        // drops index for column `users_id`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_process_brak-users_id}}',
            '{{%bichuv_nastel_process_brak}}'
        );

        $this->dropTable('{{%bichuv_nastel_process_brak}}');
    }
}
