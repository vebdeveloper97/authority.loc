<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_departments}}`.
 */
class m190806_062128_create_toquv_departments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%toquv_departments}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'parent' => $this->integer()->defaultValue(0),
            'tel' => $this->string(),
            'address' => $this->text(),
            'user_id' => $this->bigInteger(),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);

        //user_id
        $this->createIndex(
            'idx-toquv_departments-user_id',
            'toquv_departments',
            'user_id'
        );

        $this->addForeignKey(
            'fk-toquv_departments-user_id',
            'toquv_departments',
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
        //user_id
        $this->dropForeignKey(
            'fk-toquv_departments-user_id',
            'toquv_departments'
        );

        $this->dropIndex(
            'idx-toquv_departments-user_id',
            'toquv_departments'
        );

        $this->dropTable('{{%toquv_departments}}');
    }
}
