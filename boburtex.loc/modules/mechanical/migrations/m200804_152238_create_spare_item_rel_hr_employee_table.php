<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%spare_item_rel_hr_employee}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%spare_item}}`
 * - `{{%hr_employee}}`
 * - `{{%hr_departments}}`
 */
class m200804_152238_create_spare_item_rel_hr_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%spare_item_rel_hr_employee}}', [
            'id' => $this->primaryKey(),
            'spare_item_id' => $this->integer(),
            'hr_employee_id' => $this->integer(),
            'hr_department_id' => $this->integer(),
            'add_info' => $this->text(),
            'inv_number' => $this->string(),
            'hr_country_id' => $this->integer(),
            'company_name' => $this->text(),
            'manufacture_date' => $this->dateTime(),
            'installed_date' => $this->dateTime(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `spare_item_id`
        $this->createIndex(
            '{{%idx-spare_item_rel_hr_employee-spare_item_id}}',
            '{{%spare_item_rel_hr_employee}}',
            'spare_item_id'
        );

        // add foreign key for table `{{%spare_item}}`
        $this->addForeignKey(
            '{{%fk-spare_item_rel_hr_employee-spare_item_id}}',
            '{{%spare_item_rel_hr_employee}}',
            'spare_item_id',
            '{{%spare_item}}',
            'id'
        );

        // creates index for column `hr_employee_id`
        $this->createIndex(
            '{{%idx-spare_item_rel_hr_employee-hr_employee_id}}',
            '{{%spare_item_rel_hr_employee}}',
            'hr_employee_id'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-spare_item_rel_hr_employee-hr_employee_id}}',
            '{{%spare_item_rel_hr_employee}}',
            'hr_employee_id',
            '{{%hr_employee}}',
            'id'
        );

        // creates index for column `hr_department_id`
        $this->createIndex(
            '{{%idx-spare_item_rel_hr_employee-hr_department_id}}',
            '{{%spare_item_rel_hr_employee}}',
            'hr_department_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-spare_item_rel_hr_employee-hr_department_id}}',
            '{{%spare_item_rel_hr_employee}}',
            'hr_department_id',
            '{{%hr_departments}}',
            'id'
        );
        // creates index for column `hr_country_id`
        $this->createIndex(
            '{{%idx-spare_item_rel_hr_employee-hr_country_id}}',
            '{{%spare_item_rel_hr_employee}}',
            'hr_country_id'
        );

        // add foreign key for table `{{%hr_country}}`
        $this->addForeignKey(
            '{{%fk-spare_item_rel_hr_employee-hr_country_id}}',
            '{{%spare_item_rel_hr_employee}}',
            'hr_country_id',
            '{{%hr_country}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%spare_item}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_rel_hr_employee-spare_item_id}}',
            '{{%spare_item_rel_hr_employee}}'
        );

        // drops index for column `spare_item_id`
        $this->dropIndex(
            '{{%idx-spare_item_rel_hr_employee-spare_item_id}}',
            '{{%spare_item_rel_hr_employee}}'
        );

        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_rel_hr_employee-hr_employee_id}}',
            '{{%spare_item_rel_hr_employee}}'
        );

        // drops index for column `hr_employee_id`
        $this->dropIndex(
            '{{%idx-spare_item_rel_hr_employee-hr_employee_id}}',
            '{{%spare_item_rel_hr_employee}}'
        );

        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_rel_hr_employee-hr_department_id}}',
            '{{%spare_item_rel_hr_employee}}'
        );

        // drops index for column `hr_department_id`
        $this->dropIndex(
            '{{%idx-spare_item_rel_hr_employee-hr_department_id}}',
            '{{%spare_item_rel_hr_employee}}'
        );
        // drops foreign key for table `{{%hr_country}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_rel_hr_employee-hr_country_id}}',
            '{{%spare_item_rel_hr_employee}}'
        );

        // drops index for column `hr_country_id`
        $this->dropIndex(
            '{{%idx-spare_item_rel_hr_employee-hr_country_id}}',
            '{{%spare_item_rel_hr_employee}}'
        );
        $this->dropTable('{{%spare_item_rel_hr_employee}}');
    }
}
