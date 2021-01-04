<?php

use yii\db\Migration;

/**
 * Class m191216_095937_create_bichuv_attach_worker_to_machine_table
 */
class m191216_095937_create_bichuv_attach_worker_to_machine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_attach_worker_to_machine}}', [
            'id' => $this->primaryKey(),
            'nastel_machine_id' => $this->integer(),
            'user_id' => $this->bigInteger(20),
            'add_info' => $this->text(),
            'reg_date' => $this->dateTime(),
            'until' => $this->dateTime(),
            'type' => $this->smallInteger(2)->defaultValue(1),
            'status' => $this->smallInteger(2)->defaultValue(1),
            'created_by' => $this->bigInteger(20),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        // creates index for column `nastel_machine_id`
        $this->createIndex(
            '{{%idx-bichuv_attach_worker_to_machine-nastel_machine_id}}',
            '{{%bichuv_attach_worker_to_machine}}',
            'nastel_machine_id'
        );

        // add foreign key for table `{{%nastel_machine_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_attach_worker_to_machine-nastel_machine_id}}',
            '{{%bichuv_attach_worker_to_machine}}',
            'nastel_machine_id',
            '{{%bichuv_nastel_machines}}',
            'id'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-bichuv_attach_worker_to_machine-user_id}}',
            '{{%bichuv_attach_worker_to_machine}}',
            'user_id'
        );

        // add foreign key for table `{{%user_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_attach_worker_to_machine-user_id}}',
            '{{%bichuv_attach_worker_to_machine}}',
            'user_id',
            '{{%users}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%nastel_machine_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_attach_worker_to_machine-nastel_machine_id}}',
            '{{%bichuv_attach_worker_to_machine}}'
        );

        // drops index for column `nastel_machine_id`
        $this->dropIndex(
            '{{%idx-bichuv_attach_worker_to_machine-nastel_machine_id}}',
            '{{%bichuv_attach_worker_to_machine}}'
        );

        // drops foreign key for table `{{%user_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_attach_worker_to_machine-user_id}}',
            '{{%bichuv_attach_worker_to_machine}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-bichuv_attach_worker_to_machine-user_id}}',
            '{{%bichuv_attach_worker_to_machine}}'
        );

        $this->dropTable('{{%bichuv_attach_worker_to_machine}}');
    }

}
