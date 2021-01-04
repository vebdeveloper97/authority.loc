<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_organization_info}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_departments}}`
 */
class m200616_104542_create_hr_organization_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_organization_info}}', [
            'department_id' => $this->primaryKey(),
            'tel' => $this->string(),
            'address' => $this->text(),
            'add_info' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
        ]);

        // creates index for column `department_id`
        $this->createIndex(
            '{{%idx-hr_organization_info-department_id}}',
            '{{%hr_organization_info}}',
            'department_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-hr_organization_info-department_id}}',
            '{{%hr_organization_info}}',
            'department_id',
            '{{%hr_departments}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-hr_organization_info-department_id}}',
            '{{%hr_organization_info}}'
        );

        // drops index for column `department_id`
        $this->dropIndex(
            '{{%idx-hr_organization_info-department_id}}',
            '{{%hr_organization_info}}'
        );

        $this->dropTable('{{%hr_organization_info}}');
    }
}
