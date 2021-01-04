<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tikuv_rm_items}}`.
 */
class m200129_022114_create_tikuv_rm_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tikuv_rm_items}}', [
            'id' => $this->primaryKey(),
            'tikuv_doc_id' => $this->integer(),
            'entity_id' => $this->integer(),
            'entity_type' => $this->smallInteger()->defaultValue(1),
            'quantity' => $this->decimal(20,3)->defaultValue(0),
            'document_quantity' => $this->decimal(20,3)->defaultValue(0),
            'roll_count' => $this->integer(3)->defaultValue(0),
            'is_accessory' => $this->smallInteger(1)->defaultValue(1),
            'party_no' => $this->string(25),
            'musteri_party_no' => $this->string(25),
            'nastel_no' => $this->string(25),
            'model_id' => $this->smallInteger(6),
            'is_own' => $this->smallInteger()->defaultValue(1),
            'status' => $this->smallInteger()->defaultValue(1),
            'add_info' => $this->text(),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->integer(11)
        ]);

        //nastel_no index
        $this->createIndex(
            'idx-tikuv_rm_items-nastel_no',
            'tikuv_rm_items',
            'nastel_no'
        );

        //tikuv_doc_id
        $this->createIndex(
            'idx-tikuv_rm_items-tikuv_doc_id',
            'tikuv_rm_items',
            'tikuv_doc_id'
        );

        $this->addForeignKey(
            'fk-tikuv_rm_items-tikuv_doc_id',
            'tikuv_rm_items',
            'tikuv_doc_id',
            'tikuv_doc',
            'id'
        );

        //model_id
        $this->createIndex(
            'idx-tikuv_rm_items-model_id',
            'tikuv_rm_items',
            'model_id'
        );

        $this->addForeignKey(
            'fk-tikuv_rm_items-model_id',
            'tikuv_rm_items',
            'model_id',
            'product',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //nastel_no
        $this->dropIndex(
            'idx-tikuv_rm_items-nastel_no',
            'tikuv_rm_items'
        );

        //tikuv_doc_id
        $this->dropForeignKey(
            'fk-tikuv_rm_items-tikuv_doc_id',
            'tikuv_rm_items'
        );

        $this->dropIndex(
            'idx-tikuv_rm_items-tikuv_doc_id',
            'tikuv_rm_items'
        );

        //model_id
        $this->dropForeignKey(
            'fk-tikuv_rm_items-model_id',
            'tikuv_rm_items'
        );

        $this->dropIndex(
            'idx-tikuv_rm_items-model_id',
            'tikuv_rm_items'
        );

        $this->dropTable('{{%tikuv_rm_items}}');
    }
}
