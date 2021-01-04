<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%wh_item_country}}`.
 */
class m200421_080051_create_wh_item_country_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%wh_item_country}}', [
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
        $this->dropTable('{{%wh_item_country}}');
    }
}
