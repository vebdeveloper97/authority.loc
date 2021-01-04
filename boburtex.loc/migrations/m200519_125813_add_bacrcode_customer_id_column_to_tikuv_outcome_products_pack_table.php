<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_outcome_products_pack}}`.
 */
class m200519_125813_add_bacrcode_customer_id_column_to_tikuv_outcome_products_pack_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tikuv_outcome_products_pack', 'barcode_customer_id', $this->integer());
        $this->addColumn('tikuv_goods_doc_pack', 'barcode_customer_id', $this->integer());
        $this->addColumn('tikuv_goods_doc', 'barcode_customer_id', $this->integer());
        $this->addColumn('tikuv_package_item_balance', 'barcode_customer_id', $this->integer());

        // creates index for column `barcode_customer_id`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products_pack-barcode_customer_id}}',
            '{{%tikuv_outcome_products_pack}}',
            'barcode_customer_id'
        );

        // add foreign key for table `{{%barcode_customers}}`
        $this->addForeignKey(
            '{{%fk-tikuv_outcome_products_pack-barcode_customer_id}}',
            '{{%tikuv_outcome_products_pack}}',
            'barcode_customer_id',
            '{{%barcode_customers}}',
            'id'
        );

        // creates index for column `barcode_customer_id`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc_pack-barcode_customer_id}}',
            '{{%tikuv_goods_doc_pack}}',
            'barcode_customer_id'
        );

        // add foreign key for table `{{%barcode_customers}}`
        $this->addForeignKey(
            '{{%fk-tikuv_goods_doc_pack-barcode_customer_id}}',
            '{{%tikuv_goods_doc_pack}}',
            'barcode_customer_id',
            '{{%barcode_customers}}',
            'id'
        );

        // creates index for column `barcode_customer_id`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc-barcode_customer_id}}',
            '{{%tikuv_goods_doc}}',
            'barcode_customer_id'
        );

        // add foreign key for table `{{%barcode_customers}}`
        $this->addForeignKey(
            '{{%fk-tikuv_goods_doc-barcode_customer_id}}',
            '{{%tikuv_goods_doc}}',
            'barcode_customer_id',
            '{{%barcode_customers}}',
            'id'
        );

        // creates index for column `barcode_customer_id`
        $this->createIndex(
            '{{%idx-tikuv_package_item_balance-barcode_customer_id}}',
            '{{%tikuv_package_item_balance}}',
            'barcode_customer_id'
        );

        // add foreign key for table `{{%barcode_customers}}`
        $this->addForeignKey(
            '{{%fk-tikuv_package_item_balance-barcode_customer_id}}',
            '{{%tikuv_package_item_balance}}',
            'barcode_customer_id',
            '{{%barcode_customers}}',
            'id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%barcode_customers}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_outcome_products_pack-barcode_customer_id}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        // drops index for column `barcode_customer_id`
        $this->dropIndex(
            '{{%idx-tikuv_outcome_products_pack-barcode_customer_id}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        // drops foreign key for table `{{%barcode_customers}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_goods_doc_pack-barcode_customer_id}}',
            '{{%tikuv_goods_doc_pack}}'
        );

        // drops index for column `barcode_customer_id`
        $this->dropIndex(
            '{{%idx-tikuv_goods_doc_pack-barcode_customer_id}}',
            '{{%tikuv_goods_doc_pack}}'
        );

        // drops foreign key for table `{{%barcode_customers}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_goods_doc-barcode_customer_id}}',
            '{{%tikuv_goods_doc}}'
        );

        // drops index for column `barcode_customer_id`
        $this->dropIndex(
            '{{%idx-tikuv_goods_doc-barcode_customer_id}}',
            '{{%tikuv_goods_doc}}'
        );

        // drops foreign key for table `{{%barcode_customers}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_package_item_balance-barcode_customer_id}}',
            '{{%tikuv_package_item_balance}}'
        );

        // drops index for column `barcode_customer_id`
        $this->dropIndex(
            '{{%idx-tikuv_package_item_balance-barcode_customer_id}}',
            '{{%tikuv_package_item_balance}}'
        );

        $this->dropColumn('tikuv_outcome_products_pack', 'barcode_customer_id');
        $this->dropColumn('tikuv_goods_doc_pack', 'barcode_customer_id');
        $this->dropColumn('tikuv_goods_doc', 'barcode_customer_id');
        $this->dropColumn('tikuv_package_item_balance', 'barcode_customer_id');
    }
}
