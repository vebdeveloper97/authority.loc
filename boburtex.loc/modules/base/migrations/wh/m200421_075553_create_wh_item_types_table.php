<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%wh_item_types}}`.
 */
class m200421_075553_create_wh_item_types_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%wh_item_types}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(50),
            'name' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%wh_item_types}}');
    }
}
