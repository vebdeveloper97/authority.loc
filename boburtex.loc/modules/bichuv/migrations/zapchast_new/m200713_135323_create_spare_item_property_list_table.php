<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%spare_item_property_list}}`.
 */
class m200713_135323_create_spare_item_property_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%spare_item_property_list}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'status' => $this->integer(),
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
        $this->dropTable('{{%spare_item_property_list}}');
    }
}
