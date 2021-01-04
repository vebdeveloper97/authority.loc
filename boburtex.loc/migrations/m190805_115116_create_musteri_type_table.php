<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%musteri_type}}`.
 */
class m190805_115116_create_musteri_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%musteri_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(150)->notNull()
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%musteri_type}}');
    }
}
