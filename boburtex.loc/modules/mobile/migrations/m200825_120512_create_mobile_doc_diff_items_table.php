<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%mobile_doc_diff_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%unit}}`
 * - `{{%hr_departments}}`
 */
class m200825_120512_create_mobile_doc_diff_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%mobile_doc_diff_items}}', [
            'id' => $this->primaryKey(),
            'doc_items_id' => $this->integer(),
            'table_name' => $this->string(60),
            'diff_qty' => $this->decimal(20,3),
            'unit_id' => $this->integer(),
            'department_id' => $this->integer(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->bigInteger(),
            'updated_by' => $this->bigInteger(),
        ]);

        // creates index for column `unit_id`
        $this->createIndex(
            '{{%idx-mobile_doc_diff_items-unit_id}}',
            '{{%mobile_doc_diff_items}}',
            'unit_id'
        );

        // add foreign key for table `{{%unit}}`
        $this->addForeignKey(
            '{{%fk-mobile_doc_diff_items-unit_id}}',
            '{{%mobile_doc_diff_items}}',
            'unit_id',
            '{{%unit}}',
            'id'
        );

        // creates index for column `department_id`
        $this->createIndex(
            '{{%idx-mobile_doc_diff_items-department_id}}',
            '{{%mobile_doc_diff_items}}',
            'department_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-mobile_doc_diff_items-department_id}}',
            '{{%mobile_doc_diff_items}}',
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
        // drops foreign key for table `{{%unit}}`
        $this->dropForeignKey(
            '{{%fk-mobile_doc_diff_items-unit_id}}',
            '{{%mobile_doc_diff_items}}'
        );

        // drops index for column `unit_id`
        $this->dropIndex(
            '{{%idx-mobile_doc_diff_items-unit_id}}',
            '{{%mobile_doc_diff_items}}'
        );

        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-mobile_doc_diff_items-department_id}}',
            '{{%mobile_doc_diff_items}}'
        );

        // drops index for column `department_id`
        $this->dropIndex(
            '{{%idx-mobile_doc_diff_items-department_id}}',
            '{{%mobile_doc_diff_items}}'
        );

        $this->dropTable('{{%mobile_doc_diff_items}}');
    }
}
