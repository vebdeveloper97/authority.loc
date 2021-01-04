<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%recipe_subpart}}`.
 */
class m200514_111440_create_recipe_subpart_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%recipe_subpart}}', [
            'id' => $this->bigPrimaryKey(),
            'recipe_id' => $this->bigInteger(),
            'sub_id' => $this->bigInteger(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%recipe_subpart}}');
    }
}
