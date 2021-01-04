<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_nastel_processes}}`.
 */
class m200206_181829_create_bichuv_nastel_processes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_nastel_processes}}', [
            'id' => $this->primaryKey(),
            'nastel_no' => $this->string(50),
            'bichuv_detail_type_id' => $this->integer(),
            'bichuv_nastel_stol_id' => $this->integer(2),
            'action' => $this->smallInteger(1)->defaultValue(1),
            'user_started' => $this->bigInteger(20),
            'started_time' => $this->dateTime(),
            'user_ended' => $this->bigInteger(20),
            'ended_time' => $this->dateTime(),
            'type' => $this->smallInteger(1)->defaultValue(1),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `nastel_no`
        $this->createIndex(
            '{{%idx-bichuv_nastel_processes-nastel_no}}',
            '{{%bichuv_nastel_processes}}',
            'nastel_no'
        );

        // creates index for column `bichuv_detail_type_id`
        $this->createIndex(
            '{{%idx-bichuv_nastel_processes-bichuv_detail_type_id}}',
            '{{%bichuv_nastel_processes}}',
            'bichuv_detail_type_id'
        );

        // add foreign key for table `{{%bichuv_detail_type_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_nastel_processes-bichuv_detail_type_id}}',
            '{{%bichuv_nastel_processes}}',
            'bichuv_detail_type_id',
            '{{%bichuv_detail_types}}',
            'id'
        );

        // creates index for column `user_started`
        $this->createIndex(
            '{{%idx-bichuv_nastel_processes-user_started}}',
            '{{%bichuv_nastel_processes}}',
            'user_started'
        );

        // add foreign key for table `{{%user_started}}`
        $this->addForeignKey(
            '{{%fk-bichuv_nastel_processes-user_started}}',
            '{{%bichuv_nastel_processes}}',
            'user_started',
            '{{%users}}',
            'id'
        );

        // creates index for column `user_ended`
        $this->createIndex(
            '{{%idx-bichuv_nastel_processes-user_ended}}',
            '{{%bichuv_nastel_processes}}',
            'user_ended'
        );

        // add foreign key for table `{{%user_ended}}`
        $this->addForeignKey(
            '{{%fk-bichuv_nastel_processes-user_ended}}',
            '{{%bichuv_nastel_processes}}',
            'user_ended',
            '{{%users}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `bichuv_detail_type_id`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_processes-nastel_no}}',
            '{{%bichuv_nastel_processes}}'
        );


        // drops foreign key for table `{{%bichuv_detail_type_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_processes-bichuv_detail_type_id}}',
            '{{%bichuv_nastel_processes}}'
        );

        // drops index for column `bichuv_detail_type_id`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_processes-bichuv_detail_type_id}}',
            '{{%bichuv_nastel_processes}}'
        );


        // drops foreign key for table `{{%user_started}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_processes-user_started}}',
            '{{%bichuv_nastel_processes}}'
        );

        // drops index for column `user_started`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_processes-user_started}}',
            '{{%bichuv_nastel_processes}}'
        );

        // drops foreign key for table `{{%user_ended}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_processes-user_ended}}',
            '{{%bichuv_nastel_processes}}'
        );

        // drops index for column `user_ended`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_processes-user_ended}}',
            '{{%bichuv_nastel_processes}}'
        );

        $this->dropTable('{{%bichuv_nastel_processes}}');
    }
}
