<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users_hr_departments}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 * - `{{%hr_departments}}`
 */
class m200701_080959_create_junction_table_for_users_and_hr_departments_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users_hr_departments}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->bigInteger(),
            'hr_departments_id' => $this->integer(),
            'type' => $this->smallInteger(3),
            'status' => $this->tinyInteger(1),
            'created_by' => $this->bigInteger(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-users_hr_departments-user_id}}',
            '{{%users_hr_departments}}',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-users_hr_departments-user_id}}',
            '{{%users_hr_departments}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `hr_departments_id`
        $this->createIndex(
            '{{%idx-users_hr_departments-hr_departments_id}}',
            '{{%users_hr_departments}}',
            'hr_departments_id'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-users_hr_departments-hr_departments_id}}',
            '{{%users_hr_departments}}',
            'hr_departments_id',
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
        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-users_hr_departments-user_id}}',
            '{{%users_hr_departments}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-users_hr_departments-user_id}}',
            '{{%users_hr_departments}}'
        );

        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-users_hr_departments-hr_departments_id}}',
            '{{%users_hr_departments}}'
        );

        // drops index for column `hr_departments_id`
        $this->dropIndex(
            '{{%idx-users_hr_departments-hr_departments_id}}',
            '{{%users_hr_departments}}'
        );

        $this->dropTable('{{%users_hr_departments}}');
    }
}
