<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_outcome_products_pack}}`.
 */
class m191011_142447_add_some_column_to_tikuv_outcome_products_pack_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_outcome_products_pack}}', 'toquv_partiya', $this->string(20));
        $this->addColumn('{{%tikuv_outcome_products_pack}}', 'boyoq_partiya', $this->string(20));
        $this->addColumn('{{%tikuv_outcome_products_pack}}', 'nastel_no', $this->string(20));
        $this->addColumn('{{%tikuv_outcome_products_pack}}', 'musteri_id', $this->bigInteger(20));

        // creates index for column `musteri_id`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products_pack-musteri_id}}',
            '{{%tikuv_outcome_products_pack}}',
            'musteri_id'
        );

        // add foreign key for table `{{%musteri}}`
        $this->addForeignKey(
            '{{%fk-tikuv_outcome_products_pack-musteri_id}}',
            '{{%tikuv_outcome_products_pack}}',
            'musteri_id',
            '{{%musteri}}',
            'id',
            'RESTRICT'
        );

        $this->dropColumn('{{%tikuv_outcome_products_pack}}', 'musteri');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%tikuv_outcome_products_pack}}', 'musteri', $this->string(100));

        // drops foreign key for table `{{%musteri}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_outcome_products_pack-musteri_id}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        // drops index for column `musteri_id`
        $this->dropIndex(
            '{{%idx-tikuv_outcome_products_pack-musteri_id}}',
            '{{%tikuv_outcome_products_pack}}'
        );


        $this->dropColumn('{{%tikuv_outcome_products_pack}}', 'toquv_partiya');
        $this->dropColumn('{{%tikuv_outcome_products_pack}}', 'boyoq_partiya');
        $this->dropColumn('{{%tikuv_outcome_products_pack}}', 'nastel_no');
        $this->dropColumn('{{%tikuv_outcome_products_pack}}', 'musteri_id');
    }
}
