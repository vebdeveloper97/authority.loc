<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_acs_property_list}}`.
 */
class m200710_071129_create_bichuv_acs_property_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_acs_property_list}}', [
            'id' => $this->primaryKey(),
            'name' => $this->char(255),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bichuv_acs_property_list}}');
    }
}
