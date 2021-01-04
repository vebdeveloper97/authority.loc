<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%template_item_group}}`.
 */
class m200514_111527_create_template_item_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%template_item_group}}', [
            'id' => $this->primaryKey(),
            'template_id' => $this->integer(),
            'name' => $this->string(100),
            'sort_weight' => $this->integer(),
            'add_info' => $this->string(255),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%template_item_group}}');
    }
}
