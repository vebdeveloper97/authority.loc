<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_departments_attachments}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_departments}}`
 * - `{{%attachments}}`
 */
class m200616_222408_create_junction_table_for_hr_departments_and_attachments_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_departments_attachments}}', [
            'hr_departments_id' => $this->integer(),
            'attachments_id' => $this->integer(),
            'PRIMARY KEY(hr_departments_id, attachments_id)',
        ]);

        // creates index for column `hr_departments_id`
        $this->createIndex(
            '{{%idx-hr_departments_attachments-hr_departments_id}}',
            '{{%hr_departments_attachments}}',
            'hr_departments_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-hr_departments_attachments-hr_departments_id}}',
            '{{%hr_departments_attachments}}',
            'hr_departments_id',
            '{{%hr_departments}}',
            'id'
        );

        // creates index for column `attachments_id`
        $this->createIndex(
            '{{%idx-hr_departments_attachments-attachments_id}}',
            '{{%hr_departments_attachments}}',
            'attachments_id'
        );

        // add foreign key for table `{{%attachments}}`
        $this->addForeignKey(
            '{{%fk-hr_departments_attachments-attachments_id}}',
            '{{%hr_departments_attachments}}',
            'attachments_id',
            '{{%attachments}}',
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
            '{{%fk-hr_departments_attachments-hr_departments_id}}',
            '{{%hr_departments_attachments}}'
        );

        // drops index for column `hr_departments_id`
        $this->dropIndex(
            '{{%idx-hr_departments_attachments-hr_departments_id}}',
            '{{%hr_departments_attachments}}'
        );

        // drops foreign key for table `{{%attachments}}`
        $this->dropForeignKey(
            '{{%fk-hr_departments_attachments-attachments_id}}',
            '{{%hr_departments_attachments}}'
        );

        // drops index for column `attachments_id`
        $this->dropIndex(
            '{{%idx-hr_departments_attachments-attachments_id}}',
            '{{%hr_departments_attachments}}'
        );

        $this->dropTable('{{%hr_departments_attachments}}');
    }
}
