<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%mobile_process}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_departments}}`
 */
class m200824_091416_create_mobile_process_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%mobile_process}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->unique(),
            'department_id' => $this->integer(),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->bigInteger(),
            'updated_by' => $this->bigInteger(),
        ]);

        // creates index for column `department_id`
        $this->createIndex(
            '{{%idx-mobile_process-department_id}}',
            '{{%mobile_process}}',
            'department_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-mobile_process-department_id}}',
            '{{%mobile_process}}',
            'department_id',
            '{{%hr_departments}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-mobile_process-department_id}}',
            '{{%mobile_process}}'
        );

        // drops index for column `department_id`
        $this->dropIndex(
            '{{%idx-mobile_process-department_id}}',
            '{{%mobile_process}}'
        );

        $this->dropTable('{{%mobile_process}}');
    }
}
