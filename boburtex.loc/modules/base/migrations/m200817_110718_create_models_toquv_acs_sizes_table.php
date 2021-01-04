<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%models_toquv_acs_sizes}}`.
 */
class m200817_110718_create_models_toquv_acs_sizes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%models_toquv_acs_sizes}}', [
            'id' => $this->primaryKey(),
            'models_toquv_acs_id' => $this->integer(),
            'size_id' => $this->char(25),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%models_toquv_acs_sizes}}');
    }
}
