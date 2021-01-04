<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_doc_item}}`.
 */
class m190821_095549_create_bichuv_doc_item_table extends Migration
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

        $this->createTable('{{%bichuv_doc_items}}', [
            'id' => $this->primaryKey(),
            'bichuv_doc_id' => $this->integer(),
            'entity_id' => $this->integer(),
            'entity_type' => $this->smallInteger()->defaultValue(1),
            'quantity' => $this->integer()->defaultValue(0),
            'document_quantity' => $this->integer()->defaultValue(0),
            'price_sum' => $this->decimal(20,2)->defaultValue(0),
            'price_usd' => $this->decimal(20,2)->defaultValue(0),
            'current_usd' => $this->decimal(20,2)->defaultValue(0),
            // 1- ozimizniki 2- ularniki(tashqaridan kelgan tovar)
            'is_own' => $this->smallInteger()->defaultValue(1),
            'package_type' => $this->integer(),
            'package_qty' => $this->integer()->defaultValue(0),
            'lot' => $this->string(25),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->integer(11)
        ]);

        $this->createIndex(
            'idx-bichuv_doc_items-bichuv_doc_id',
            'bichuv_doc_items',
            'bichuv_doc_id'
        );

        $this->addForeignKey(
            'fk-bichuv_doc_items-bichuv_doc_id',
            'bichuv_doc_items',
            'bichuv_doc_id',
            'bichuv_doc',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-bichuv_doc_items-bichuv_doc_id',
            'bichuv_doc_items'
        );

        $this->dropIndex(
            'idx-bichuv_doc_items-bichuv_doc_id',
            'bichuv_doc_items'
        );

        $this->dropTable('{{%bichuv_doc_items}}');
    }
}
