<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_document_items}}`.
 */
class m190806_064503_create_toquv_document_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%toquv_document_items}}', [
            'id' => $this->primaryKey(),
            'toquv_document_id' => $this->integer(),
            'entity_id' => $this->integer(),
            'entity_type' => $this->smallInteger()->defaultValue(1),
            'quantity' => $this->integer()->defaultValue(0),
            'price_sum' => $this->decimal(20,2)->defaultValue(0),
            'price_usd' => $this->decimal(20,2)->defaultValue(0),
            'current_usd' => $this->decimal(20,2)->defaultValue(0),
            // 1- ozimizniki 2- ularniki(tashqaridan kelgan tovar)
            'is_own' => $this->smallInteger()->defaultValue(1),
            'package_type' => $this->integer(),
            'package_qty' => $this->integer()->defaultValue(0),
            'lot' => $this->string(25),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);

        //toquv_ip_id
        $this->createIndex(
            'idx-toquv_document_items-toquv_document_id',
            'toquv_document_items',
            'toquv_document_id'
        );

        $this->addForeignKey(
            'fk-toquv_document_items-toquv_document_id',
            'toquv_document_items',
            'toquv_document_id',
            'toquv_documents',
            'id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //toquv_ip_id
        $this->dropForeignKey(
            'fk-toquv_document_items-toquv_document_id',
            'toquv_document_items'
        );

        $this->dropIndex(
            'idx-toquv_document_items-toquv_document_id',
            'toquv_document_items'
        );
        $this->dropTable('{{%toquv_document_items}}');
    }
}
