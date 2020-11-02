<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%images_column_from_message_ru}}`.
 */
class m201102_094235_create_images_column_from_message_ru_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('message_ru', 'images');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('message_ru', 'images', $this->string(50));
    }
}
