<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_list}}`.
 */
class m200404_054725_add_some_columns_to_models_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_list}}', 'baski_rotatsion', $this->smallInteger(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%models_list}}', 'baski_rotatsion');
    }
}
