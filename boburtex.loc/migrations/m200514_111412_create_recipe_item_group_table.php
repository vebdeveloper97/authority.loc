<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%recipe_item_group}}`.
 */
class m200514_111412_create_recipe_item_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%recipe_item_group}}', [
            'id' => $this->bigPrimaryKey(),
            'recipe_id' => $this->bigInteger(),
            'name' => $this->string(255),
            'sort_weight' => $this->integer(),
            'add_info' => $this->string(255),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%recipe_item_group}}');
    }
}
