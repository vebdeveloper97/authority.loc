<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_user_department}}`.
 */
class m190806_125708_create_user_department_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_user_department}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->bigInteger(),
            'department_id' => $this->integer(),
            'created_by' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        //department_id
        $this->createIndex(
            'idx-toquv_user_department-department_id',
            'toquv_user_department',
            'department_id'
        );

        $this->addForeignKey(
            'fk-toquv_user_department-department_id',
            'toquv_user_department',
            'department_id',
            'toquv_departments',
            'id'
        );

        //user_id
        $this->createIndex(
            'idx-toquv_user_department-user_id',
            'toquv_user_department',
            'user_id'
        );

        $this->addForeignKey(
            'fk-toquv_user_department-user_id',
            'toquv_user_department',
            'user_id',
            'users',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //department_id
        $this->dropForeignKey(
            'fk-toquv_user_department-department_id',
            'toquv_user_department'
        );

        $this->dropIndex(
            'idx-toquv_user_department-department_id',
            'toquv_user_department'
        );

        //user_id
        $this->dropForeignKey(
            'fk-toquv_user_department-user_id',
            'toquv_user_department'
        );

        $this->dropIndex(
            'idx-toquv_user_department-user_id',
            'toquv_user_department'
        );

        $this->dropTable('{{%toquv_user_department}}');
    }
}
