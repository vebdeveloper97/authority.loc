<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%size_collections}}`.
 */
class m191021_053828_create_size_collections_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%size_collections}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'type' => $this->smallInteger(1)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%size_collections}}');
    }
}
