<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%}}`.
 */
class m190806_120004_change_some_fields_column_to_toquv_departments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
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

        $this->dropColumn('toquv_departments','user_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('toquv_departments','user_id', $this->integer());

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
}
