<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_item_balance}}`.
 */
class m190923_103949_add_musteri_id_column_to_toquv_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('toquv_item_balance','musteri_id',$this->bigInteger(20));

        //musteri_id
        $this->createIndex(
            'idx-toquv_item_balance-musteri_id',
            'toquv_item_balance',
            'musteri_id'
        );

        $this->addForeignKey(
            'fk-toquv_item_balance-musteri_id',
            'toquv_item_balance',
            'musteri_id',
            'musteri',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //musteri_id
        $this->dropForeignKey(
            'fk-toquv_item_balance-musteri_id',
            'toquv_item_balance'
        );

        $this->dropIndex(
            'idx-toquv_item_balance-musteri_id',
            'toquv_item_balance'
        );
        $this->dropColumn('toquv_item_balance','musteri_id');

        return false;
    }
}
