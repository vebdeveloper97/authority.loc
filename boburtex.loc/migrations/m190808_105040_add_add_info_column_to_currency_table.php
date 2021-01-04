<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%currency}}`.
 */
class m190808_105040_add_add_info_column_to_currency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('currency', 'add_info', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('currency', 'add_info');
    }
}
