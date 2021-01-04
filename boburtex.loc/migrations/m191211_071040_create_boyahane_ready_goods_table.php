<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%boyahane_ready_goods}}`.
 */
class m191211_071040_create_boyahane_ready_goods_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%boyahane_ready_goods}}', [
            'id' => $this->primaryKey(),
            'pus_fine' => $this->integer(),
            'ne_id' => $this->smallInteger(6),
            'raw_material_id' => $this->smallInteger(6),
            'thread_id' => $this->smallInteger(6),
            'rm_type' => $this->tinyInteger(4),
            'thread_consists' => $this->string(20),
            'color_id' => $this->integer(),
            'color_tone' => $this->integer(),
            'color_type' => $this->tinyInteger(4),
            'finish_en' => $this->string(10),
            'finish_gramaj' => $this->string(10),
            'status' => $this->smallInteger(2)->defaultValue(1),
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
        $this->dropTable('{{%boyahane_ready_goods}}');
    }
}
