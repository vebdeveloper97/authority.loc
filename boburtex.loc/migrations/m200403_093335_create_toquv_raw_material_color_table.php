<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_raw_material_color}}`.
 */
class m200403_093335_create_toquv_raw_material_color_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_raw_material_color}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100),
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
        $this->dropTable('{{%toquv_raw_material_color}}');
    }
}
