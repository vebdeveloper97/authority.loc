<?php

use yii\db\Migration;

/**
 * Class m200825_075834_alter_model_rm_boyoq_sevice_to_model_rm_boyoq_service_table
 */
class m200825_075834_alter_model_rm_boyoq_sevice_to_model_rm_boyoq_service_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('model_rm_boyoq_sevice', "model_rm_boyoq_service");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable('model_rm_boyoq_service', "model_rm_boyoq_sevice");
    }

}
