<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_rm_boyoq_sevice}}`.
 */
class m200810_061450_create_model_rm_boyoq_sevice_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_rm_boyoq_sevice}}', [
            'id' => $this->primaryKey(),
            'models_raw_materials_id' => $this->integer(),
            'boyahane_service_id' => $this->integer(),
			'status' => $this->tinyInteger()->defaultValue(1),
			'created_by' => $this->integer(),
			'created_at' => $this->integer(),
			'updated_by' => $this->integer(),
			'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%model_rm_boyoq_sevice}}');
    }
}
