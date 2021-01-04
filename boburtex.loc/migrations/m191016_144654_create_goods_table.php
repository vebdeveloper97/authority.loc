<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%goods}}` and `{{%goods_items}}`.
 */
class m191016_144654_create_goods_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%goods}}', [
            'id' => $this->primaryKey(),
            'barcode' => $this->integer(13),
            'type' => $this->integer()->defaultValue(1),
            'model_no' => $this->string(30),
            'model_id' => $this->integer(),
            'size_type' => $this->integer(),
            'size' => $this->integer(),
            'color' => $this->integer(),
            'name' => $this->string(100),
            'old_name' => $this->string(100),
            'category' => $this->integer(),
            'sub_category' => $this->integer(),
            'model_type' => $this->integer(),
            'season' => $this->integer(),
            'status' => $this->smallInteger(6)->defaultValue(1),
        ]);
        $this->createTable('{{%goods_items}}', [
            'id' => $this->primaryKey(),
            'parent' => $this->integer(),
            'child' => $this->integer(),
            'quantity' => $this->decimal(20,3),
            'type' => $this->smallInteger(6)->defaultValue(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%goods_items}}');
        $this->dropTable('{{%goods}}');
    }
}
