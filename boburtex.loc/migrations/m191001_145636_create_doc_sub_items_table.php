<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%doc_sub_items}}`.
 */
class m191001_145636_create_doc_sub_items_table extends Migration
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

        $this->createTable('{{%toquv_doc_sub_items}}', [
            'id' => $this->primaryKey(),
            'doc_item_id' => $this->integer(),
            'entity_id' => $this->integer(),
            'level' => $this->smallInteger()->defaultValue(0),
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

        //doc_item_id
        $this->createIndex(
            'idx-toquv_doc_sub_items-doc_item_id',
            'toquv_doc_sub_items',
            'doc_item_id'
        );

        $this->addForeignKey(
            'fk-toquv_doc_sub_items-doc_item_id',
            'toquv_doc_sub_items',
            'doc_item_id',
            'toquv_document_items',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //doc_item_id
        $this->dropForeignKey(
            'fk-toquv_doc_sub_items-doc_item_id',
            'toquv_doc_sub_items'
        );

        $this->dropIndex(
            'idx-toquv_doc_sub_items-doc_item_id',
            'toquv_doc_sub_items'
        );
        $this->dropTable('{{%toquv_doc_sub_items}}');
    }
}
