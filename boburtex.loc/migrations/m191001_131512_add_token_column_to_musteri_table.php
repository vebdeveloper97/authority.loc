<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%musteri}}`.
 */
class m191001_131512_add_token_column_to_musteri_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%musteri}}', 'token', $this->string()->unique());
        $this->upsert('{{%musteri}}',['name' => "Samo", 'created_by' => 1, 'token' => 'SAMO'],true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%musteri}}',['name' => "Samo", 'created_by' => 1, 'token' => 'SAMO']);
        $this->dropColumn('{{%musteri}}', 'token');
    }
}
