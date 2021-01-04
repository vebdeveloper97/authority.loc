<?php

use yii\db\Migration;

/**
 * Class m190821_181153_upsert_rows_to_musteri_type_table
 */
class m190821_181153_upsert_rows_to_musteri_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->upsert('musteri_type',['id'=>1, 'name' => "Mijoz", 'created_by' => 1],true);
        $this->upsert('musteri_type',['id'=>2, 'name' => "Ta'minotchi", 'created_by' => 1],true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190821_181153_upsert_rows_to_musteri_type_table cannot be reverted.\n";

        return false;
    }
    */
}
