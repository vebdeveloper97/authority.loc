<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%images_column_from_message_uz}}`.
 */
class m201102_094227_create_images_column_from_message_uz_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('message_uz', 'images');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('message_uz', 'images', $this->string(50));
    }
}
