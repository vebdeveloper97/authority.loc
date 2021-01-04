<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%mobile_pemissions}}`.
 */
class m200212_063323_create_mobile_pemissions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%mobile_permissions}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50),
            'type' => $this->smallInteger(),
            'desc' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%mobile_permissions}}');
    }
}
