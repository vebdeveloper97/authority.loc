<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_employee_rel_attachment}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_employee}}`
 */
class m200615_160435_create_hr_employee_rel_attachment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_employee_rel_attachment}}', [
            'id' => $this->primaryKey(),
            'hr_employee_id' => $this->integer(),
            'type' => $this->smallInteger(),
            'name' => $this->string(100),
            'size' => $this->integer(),
            'extension' => $this->char(10),
            'path' => $this->char(255),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `hr_employee_id`
        $this->createIndex(
            '{{%idx-hr_employee_rel_attachment-hr_employee_id}}',
            '{{%hr_employee_rel_attachment}}',
            'hr_employee_id'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-hr_employee_rel_attachment-hr_employee_id}}',
            '{{%hr_employee_rel_attachment}}',
            'hr_employee_id',
            '{{%hr_employee}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-hr_employee_rel_attachment-hr_employee_id}}',
            '{{%hr_employee_rel_attachment}}'
        );

        // drops index for column `hr_employee_id`
        $this->dropIndex(
            '{{%idx-hr_employee_rel_attachment-hr_employee_id}}',
            '{{%hr_employee_rel_attachment}}'
        );

        $this->dropTable('{{%hr_employee_rel_attachment}}');
    }
}
