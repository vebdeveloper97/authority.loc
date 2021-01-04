<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%template_formula}}`.
 */
class m200528_084530_create_template_formula_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%template_formula}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'formula' => $this->string(255),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%template_formula}}');
    }
}
