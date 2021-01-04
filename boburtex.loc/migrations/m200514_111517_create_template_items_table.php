<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%template_items}}`.
 */
class m200514_111517_create_template_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%template_items}}', [
            'id' => $this->primaryKey(),
            'template_id' => $this->integer(),
            'template_group_id' => $this->integer(),
            'item_id' => $this->integer(),
            'location' => $this->string(255),
            'can_edit_item' => $this->tinyInteger(1),
            'koeff' => $this->float(),
            'unit_id' => $this->integer(),
            'amount' => $this->bigInteger(),
            'formula' => $this->string(255),
            'params' => $this->string(255),
            'add_info' => $this->string(255),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%template_items}}');
    }
}
