<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hr_staff_counters}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_staff}}`
 */
class m200618_073430_create_hr_staff_counters_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hr_staff_counters}}', [
            'id' => $this->primaryKey(),
            'staff_id' => $this->integer(),
            'status' => $this->smallInteger(),
            'quantity' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->bigInteger(),
        ]);

        // creates index for column `staff_id`
        $this->createIndex(
            '{{%idx-hr_staff_counters-staff_id}}',
            '{{%hr_staff_counters}}',
            'staff_id'
        );

        // add foreign key for table `{{%hr_staff}}`
        $this->addForeignKey(
            '{{%fk-hr_staff_counters-staff_id}}',
            '{{%hr_staff_counters}}',
            'staff_id',
            '{{%hr_staff}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_staff}}`
        $this->dropForeignKey(
            '{{%fk-hr_staff_counters-staff_id}}',
            '{{%hr_staff_counters}}'
        );

        // drops index for column `staff_id`
        $this->dropIndex(
            '{{%idx-hr_staff_counters-staff_id}}',
            '{{%hr_staff_counters}}'
        );

        $this->dropTable('{{%hr_staff_counters}}');
    }
}
