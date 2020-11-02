<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%images_column_from_message_en}}`.
 */
class m201102_094243_create_images_column_from_message_en_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('message_en', 'images');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('message_en', 'images', $this->string(50));
    }
}
