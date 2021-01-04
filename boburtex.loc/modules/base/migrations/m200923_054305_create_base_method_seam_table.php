<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%base_method_seam}}`.
 */
class m200923_054305_create_base_method_seam_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%base_method_seam}}', [
            'id' => $this->primaryKey(),
            'name' => $this->char(100),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%base_method_seam}}');
    }
}
