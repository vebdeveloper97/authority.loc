<?php

use yii\db\Migration;

/**
 * Class m190906_112931_upsert_rows_to_musteri_table
 */
class m190906_112931_upsert_rows_to_musteri_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->upsert('musteri_type',['id'=>3, 'name' => "Xizmat ko'rsatuvchi", 'created_by' => 1],true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }
}
