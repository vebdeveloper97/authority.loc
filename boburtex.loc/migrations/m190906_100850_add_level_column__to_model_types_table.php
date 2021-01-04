<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_types}}`.
 */
class m190906_100850_add_level_column__to_model_types_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('model_types', 'level', $this->smallInteger(2)->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropColumn('model_types', 'level');
    }
}
