<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%wh_department_area}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%toquv_departments}}`
 */
class m200421_080038_create_wh_department_area_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%wh_department_area}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'code' => $this->string(50),
            'dep_id' => $this->integer(),
            'parent_id' => $this->integer(),
            'type' => $this->smallInteger(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `dep_id`
        $this->createIndex(
            '{{%idx-wh_department_area-dep_id}}',
            '{{%wh_department_area}}',
            'dep_id'
        );

        // add foreign key for table `{{%toquv_departments}}`
        $this->addForeignKey(
            '{{%fk-wh_department_area-dep_id}}',
            '{{%wh_department_area}}',
            'dep_id',
            '{{%toquv_departments}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_departments}}`
        $this->dropForeignKey(
            '{{%fk-wh_department_area-dep_id}}',
            '{{%wh_department_area}}'
        );

        // drops index for column `dep_id`
        $this->dropIndex(
            '{{%idx-wh_department_area-dep_id}}',
            '{{%wh_department_area}}'
        );

        $this->dropTable('{{%wh_department_area}}');
    }
}
