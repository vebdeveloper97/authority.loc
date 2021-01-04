<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tikuv_outcome_products_pack}}`.
 */
class m191010_163322_create_tikuv_outcome_products_pack_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tikuv_outcome_products_pack}}', [
            'id' => $this->primaryKey(),
            'order_no' => $this->string(100),
            'musteri' => $this->string(100),
            'add_info' => $this->text(),
            'status' => $this->integer()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->dropColumn('{{%tikuv_outcome_products}}', 'order_no');
        $this->dropColumn('{{%tikuv_outcome_products}}', 'add_info');
        $this->addColumn('{{%tikuv_outcome_products}}', 'pack_id', $this->integer());

        // creates index for column `pack_id`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products-pack_id}}',
            '{{%tikuv_outcome_products}}',
            'pack_id'
        );

        // add foreign key for table `{{%tikuv_outcome_products_pack}}`
        $this->addForeignKey(
            '{{%fk-tikuv_outcome_products-pack_id}}',
            '{{%tikuv_outcome_products}}',
            'pack_id',
            '{{%tikuv_outcome_products_pack}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey(
            '{{%fk-tikuv_outcome_products-pack_id}}',
            '{{%tikuv_outcome_products}}'
        );

        $this->dropIndex(
            '{{%idx-tikuv_outcome_products-pack_id}}',
            '{{%tikuv_outcome_products}}'
        );

        $this->dropTable('{{%tikuv_outcome_products_pack}}');
    }
}
