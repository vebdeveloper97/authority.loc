<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%base_method}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_list}}`
 * - `{{%hr_employee}}`
 * - `{{%hr_employee}}`
 * - `{{%hr_employee}}`
 * - `{{%hr_employee}}`
 */
class m200923_053435_create_base_method_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%base_method}}', [
            'id' => $this->primaryKey(),
            'model_list_id' => $this->integer(),
            'doc_number' => $this->integer(),
            'date' => $this->date(),
            'planning_hr_id' => $this->integer(),
            'model_hr_id' => $this->integer(),
            'etyud_id' => $this->integer(),
            'master_id' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `model_list_id`
        $this->createIndex(
            '{{%idx-base_method-model_list_id}}',
            '{{%base_method}}',
            'model_list_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-base_method-model_list_id}}',
            '{{%base_method}}',
            'model_list_id',
            '{{%models_list}}',
            'id',
            'CASCADE'
        );

        // creates index for column `planning_hr_id`
        $this->createIndex(
            '{{%idx-base_method-planning_hr_id}}',
            '{{%base_method}}',
            'planning_hr_id'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-base_method-planning_hr_id}}',
            '{{%base_method}}',
            'planning_hr_id',
            '{{%hr_employee}}',
            'id',
            'CASCADE'
        );

        // creates index for column `model_hr_id`
        $this->createIndex(
            '{{%idx-base_method-model_hr_id}}',
            '{{%base_method}}',
            'model_hr_id'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-base_method-model_hr_id}}',
            '{{%base_method}}',
            'model_hr_id',
            '{{%hr_employee}}',
            'id',
            'CASCADE'
        );

        // creates index for column `etyud_id`
        $this->createIndex(
            '{{%idx-base_method-etyud_id}}',
            '{{%base_method}}',
            'etyud_id'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-base_method-etyud_id}}',
            '{{%base_method}}',
            'etyud_id',
            '{{%hr_employee}}',
            'id',
            'CASCADE'
        );

        // creates index for column `master_id`
        $this->createIndex(
            '{{%idx-base_method-master_id}}',
            '{{%base_method}}',
            'master_id'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-base_method-master_id}}',
            '{{%base_method}}',
            'master_id',
            '{{%hr_employee}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%models_list}}`
        $this->dropForeignKey(
            '{{%fk-base_method-model_list_id}}',
            '{{%base_method}}'
        );

        // drops index for column `model_list_id`
        $this->dropIndex(
            '{{%idx-base_method-model_list_id}}',
            '{{%base_method}}'
        );

        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-base_method-planning_hr_id}}',
            '{{%base_method}}'
        );

        // drops index for column `planning_hr_id`
        $this->dropIndex(
            '{{%idx-base_method-planning_hr_id}}',
            '{{%base_method}}'
        );

        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-base_method-model_hr_id}}',
            '{{%base_method}}'
        );

        // drops index for column `model_hr_id`
        $this->dropIndex(
            '{{%idx-base_method-model_hr_id}}',
            '{{%base_method}}'
        );

        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-base_method-etyud_id}}',
            '{{%base_method}}'
        );

        // drops index for column `etyud_id`
        $this->dropIndex(
            '{{%idx-base_method-etyud_id}}',
            '{{%base_method}}'
        );

        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-base_method-master_id}}',
            '{{%base_method}}'
        );

        // drops index for column `master_id`
        $this->dropIndex(
            '{{%idx-base_method-master_id}}',
            '{{%base_method}}'
        );

        $this->dropTable('{{%base_method}}');
    }
}
