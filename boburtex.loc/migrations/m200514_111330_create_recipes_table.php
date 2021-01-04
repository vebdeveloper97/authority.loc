<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%recipes}}`.
 */
class m200514_111330_create_recipes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%recipes}}', [
            'id' => $this->bigPrimaryKey(),
            'template_id' => $this->integer(),
            'status' => $this->tinyInteger()->defaultValue(6),
            'makine_id' => $this->integer(),
            'flotte' => $this->float()->defaultValue(6),
            'user_id' => $this->integer(),
            'reg_date' => $this->timestamp()->defaultExpression("CURRENT_TIMESTAMP"),
            'add_info' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%recipes}}');
    }
}
