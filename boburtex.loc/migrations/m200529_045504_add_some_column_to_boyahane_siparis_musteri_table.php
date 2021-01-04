<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%boyahane_siparis_musteri}}`.
 */
class m200529_045504_add_some_column_to_boyahane_siparis_musteri_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%boyahane_siparis_musteri}}', 'finish_date', $this->dateTime()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%boyahane_siparis_musteri}}', 'finish_date');
    }
}
