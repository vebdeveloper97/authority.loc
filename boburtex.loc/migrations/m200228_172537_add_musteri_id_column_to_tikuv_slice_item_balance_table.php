<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tikuv_slice_item_balance}}`.
 */
class m200228_172537_add_musteri_id_column_to_tikuv_slice_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tikuv_slice_item_balance','musteri_id', $this->bigInteger(20));

        // creates index for column `musteri_id`
        $this->createIndex(
            '{{%idx-tikuv_slice_item_balance-musteri_id}}',
            '{{%tikuv_slice_item_balance}}',
            'musteri_id'
        );
        // add foreign key for table `{{%musteri_id}}`
        $this->addForeignKey(
            '{{%fk-tikuv_slice_item_balance-musteri_id}}',
            '{{%tikuv_slice_item_balance}}',
            'musteri_id',
            '{{%musteri}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%musteri_id}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_slice_item_balance-musteri_id}}',
            '{{%tikuv_slice_item_balance}}'
        );

        // drops index for column `musteri_id`
        $this->dropIndex(
            '{{%idx-tikuv_slice_item_balance-musteri_id}}',
            '{{%tikuv_slice_item_balance}}'
        );
        $this->dropColumn('tikuv_slice_item_balance','musteri_id');

    }
}
