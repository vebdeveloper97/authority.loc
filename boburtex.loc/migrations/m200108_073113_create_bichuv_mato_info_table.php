<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_mato_info}}`.
 */
class m200108_073113_create_bichuv_mato_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_mato_info}}', [
            'id' => $this->primaryKey(),
            'rm_id' => $this->integer(),
            'ne_id' => $this->integer(),
            'thread_id' => $this->integer(),
            'pus_fine_id' => $this->integer(),
            'color_id' => $this->integer(),
            'en' => $this->decimal(20,3),
            'gramaj' => $this->decimal(20,3),
            'status' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bichuv_mato_info}}');
    }
}
