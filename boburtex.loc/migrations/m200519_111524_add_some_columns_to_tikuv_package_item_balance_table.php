<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_package_item_balance}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%musteri}}`
 * - `{{%musteri}}`
 */
class m200519_111524_add_some_columns_to_tikuv_package_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_package_item_balance}}', 'from_musteri', $this->bigInteger());
        $this->addColumn('{{%tikuv_package_item_balance}}', 'to_musteri', $this->bigInteger());

        // creates index for column `from_musteri`
        $this->createIndex(
            '{{%idx-tikuv_package_item_balance-from_musteri}}',
            '{{%tikuv_package_item_balance}}',
            'from_musteri'
        );

        // add foreign key for table `{{%musteri}}`
        $this->addForeignKey(
            '{{%fk-tikuv_package_item_balance-from_musteri}}',
            '{{%tikuv_package_item_balance}}',
            'from_musteri',
            '{{%musteri}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `to_musteri`
        $this->createIndex(
            '{{%idx-tikuv_package_item_balance-to_musteri}}',
            '{{%tikuv_package_item_balance}}',
            'to_musteri'
        );

        // add foreign key for table `{{%musteri}}`
        $this->addForeignKey(
            '{{%fk-tikuv_package_item_balance-to_musteri}}',
            '{{%tikuv_package_item_balance}}',
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
            '{{%fk-tikuv_package_item_balance-from_musteri}}',
            '{{%tikuv_package_item_balance}}'
        );

        // drops index for column `from_musteri`
        $this->dropIndex(
            '{{%idx-tikuv_package_item_balance-from_musteri}}',
            '{{%tikuv_package_item_balance}}'
        );

        // drops foreign key for table `{{%musteri}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_package_item_balance-to_musteri}}',
            '{{%tikuv_package_item_balance}}'
        );

        // drops index for column `to_musteri`
        $this->dropIndex(
            '{{%idx-tikuv_package_item_balance-to_musteri}}',
            '{{%tikuv_package_item_balance}}'
        );

        $this->dropColumn('{{%tikuv_package_item_balance}}', 'from_musteri');
        $this->dropColumn('{{%tikuv_package_item_balance}}', 'to_musteri');
    }
}
