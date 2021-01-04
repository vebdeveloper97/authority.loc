<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_outcome_products_pack}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%musteri}}`
 * - `{{%musteri}}`
 */
class m200519_113120_add_some_columns_to_tikuv_outcome_products_pack_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_outcome_products_pack}}', 'from_musteri', $this->bigInteger());
        $this->addColumn('{{%tikuv_outcome_products_pack}}', 'to_musteri', $this->bigInteger());

        // creates index for column `from_musteri`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products_pack-from_musteri}}',
            '{{%tikuv_outcome_products_pack}}',
            'from_musteri'
        );

        // add foreign key for table `{{%musteri}}`
        $this->addForeignKey(
            '{{%fk-tikuv_outcome_products_pack-from_musteri}}',
            '{{%tikuv_outcome_products_pack}}',
            'from_musteri',
            '{{%musteri}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `to_musteri`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products_pack-to_musteri}}',
            '{{%tikuv_outcome_products_pack}}',
            'to_musteri'
        );

        // add foreign key for table `{{%musteri}}`
        $this->addForeignKey(
            '{{%fk-tikuv_outcome_products_pack-to_musteri}}',
            '{{%tikuv_outcome_products_pack}}',
            'to_musteri',
            '{{%musteri}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%musteri}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_outcome_products_pack-from_musteri}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        // drops index for column `from_musteri`
        $this->dropIndex(
            '{{%idx-tikuv_outcome_products_pack-from_musteri}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        // drops foreign key for table `{{%musteri}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_outcome_products_pack-to_musteri}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        // drops index for column `to_musteri`
        $this->dropIndex(
            '{{%idx-tikuv_outcome_products_pack-to_musteri}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        $this->dropColumn('{{%tikuv_outcome_products_pack}}', 'from_musteri');
        $this->dropColumn('{{%tikuv_outcome_products_pack}}', 'to_musteri');
    }
}
