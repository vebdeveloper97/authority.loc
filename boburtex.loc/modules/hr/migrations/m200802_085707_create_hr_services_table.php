<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_services}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_employee}}`
 * - `{{%pul_birligi}}`
 * - `{{%hr_country}}`
 * - `{{%districts}}`
 */
class m200802_085707_create_hr_services_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_services}}', [
            'id' => $this->primaryKey(),
            'hr_employee_id' => $this->integer(),
            'type' => $this->integer(2),
            'start_date' => $this->dateTime(),
            'end_date' => $this->dateTime(),
            'reg_date' => $this->dateTime(),
            'reason' => $this->text(),
            'initiator' => $this->string(),
            'count' => $this->decimal(20,3),
            'pb_id' => $this->integer(),
            'other' => $this->text(),
            'add_info' => $this->text(),
            'hr_country_id' => $this->integer(),
            'region_id' => $this->integer(),
            'district_id' => $this->integer(),
            'region_type' => $this->integer(1),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `hr_employee_id`
        $this->createIndex(
            '{{%idx-hr_services-hr_employee_id}}',
            '{{%hr_services}}',
            'hr_employee_id'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-hr_services-hr_employee_id}}',
            '{{%hr_services}}',
            'hr_employee_id',
            '{{%hr_employee}}',
            'id'
        );

        // creates index for column `pb_id`
        $this->createIndex(
            '{{%idx-hr_services-pb_id}}',
            '{{%hr_services}}',
            'pb_id'
        );

        // add foreign key for table `{{%pul_birligi}}`
        $this->addForeignKey(
            '{{%fk-hr_services-pb_id}}',
            '{{%hr_services}}',
            'pb_id',
            '{{%pul_birligi}}',
            'id'
        );

        // creates index for column `hr_country_id`
        $this->createIndex(
            '{{%idx-hr_services-hr_country_id}}',
            '{{%hr_services}}',
            'hr_country_id'
        );

        // add foreign key for table `{{%hr_country}}`
        $this->addForeignKey(
            '{{%fk-hr_services-hr_country_id}}',
            '{{%hr_services}}',
            'hr_country_id',
            '{{%hr_country}}',
            'id'
        );
        // creates index for column `region_id`
        $this->createIndex(
            '{{%idx-hr_services-region_id}}',
            '{{%hr_services}}',
            'region_id'
        );

        // add foreign key for table `{{%regions}}`
        $this->addForeignKey(
            '{{%fk-hr_services-region_id}}',
            '{{%hr_services}}',
            'region_id',
            '{{%regions}}',
            'id'
        );
        // creates index for column `district_id`
        $this->createIndex(
            '{{%idx-hr_services-district_id}}',
            '{{%hr_services}}',
            'district_id'
        );

        // add foreign key for table `{{%districts}}`
        $this->addForeignKey(
            '{{%fk-hr_services-district_id}}',
            '{{%hr_services}}',
            'district_id',
            '{{%districts}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-hr_services-hr_employee_id}}',
            '{{%hr_services}}'
        );

        // drops index for column `hr_employee_id`
        $this->dropIndex(
            '{{%idx-hr_services-hr_employee_id}}',
            '{{%hr_services}}'
        );

        // drops foreign key for table `{{%pul_birligi}}`
        $this->dropForeignKey(
            '{{%fk-hr_services-pb_id}}',
            '{{%hr_services}}'
        );

        // drops index for column `pb_id`
        $this->dropIndex(
            '{{%idx-hr_services-pb_id}}',
            '{{%hr_services}}'
        );

        // drops foreign key for table `{{%hr_country}}`
        $this->dropForeignKey(
            '{{%fk-hr_services-hr_country_id}}',
            '{{%hr_services}}'
        );

        // drops index for column `hr_country_id`
        $this->dropIndex(
            '{{%idx-hr_services-hr_country_id}}',
            '{{%hr_services}}'
        );

        // drops foreign key for table `{{%regions}}`
        $this->dropForeignKey(
            '{{%fk-hr_services-region_id}}',
            '{{%hr_services}}'
        );

        // drops index for column `region_id`
        $this->dropIndex(
            '{{%idx-hr_services-region_id}}',
            '{{%hr_services}}'
        );
        // drops foreign key for table `{{%districts}}`
        $this->dropForeignKey(
            '{{%fk-hr_services-district_id}}',
            '{{%hr_services}}'
        );

        // drops index for column `district_id`
        $this->dropIndex(
            '{{%idx-hr_services-district_id}}',
            '{{%hr_services}}'
        );

        $this->dropTable('{{%hr_services}}');
    }
}
