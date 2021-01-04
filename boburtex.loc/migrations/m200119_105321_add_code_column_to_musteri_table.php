<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%musteri}}`.
 */
class m200119_105321_add_code_column_to_musteri_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%musteri}}', 'code', $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%musteri}}', 'code');
    }
}
