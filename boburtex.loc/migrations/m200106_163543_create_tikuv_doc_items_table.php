<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tikuv_doc_items}}`.
 */
class m200106_163543_create_tikuv_doc_items_table extends Migration
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

        $this->createTable('{{%tikuv_doc_items}}', [
            'id' => $this->primaryKey(),
            'tikuv_doc_id' => $this->integer(),
            'size_id' => $this->integer(),
            'entity_id' => $this->integer(),
            'entity_type' => $this->smallInteger()->defaultValue(1),
            'quantity' => $this->decimal(20,3)->defaultValue(0),
            'doc_qty' => $this->decimal(20,3)->defaultValue(0),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->integer(11)
        ], $tableOptions);

        //tikuv_doc_id
        $this->createIndex(
            'idx-tikuv_doc_items-tikuv_doc_id',
            'tikuv_doc_items',
            'tikuv_doc_id'
        );

        $this->addForeignKey(
            'fk-tikuv_doc_items-tikuv_doc_id',
            'tikuv_doc_items',
            'tikuv_doc_id',
            'tikuv_doc',
            'id'
        );

        //size_id
        $this->createIndex(
            'idx-tikuv_doc_items-size_id',
            'tikuv_doc_items',
            'size_id'
        );

        $this->addForeignKey(
            'fk-tikuv_doc_items-size_id',
            'tikuv_doc_items',
            'size_id',
            'size',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //tikuv_doc_id
        $this->dropForeignKey(
            'fk-tikuv_doc_items-tikuv_doc_id',
            'tikuv_doc_items'
        );

        $this->dropIndex(
            'idx-tikuv_doc_items-tikuv_doc_id',
            'tikuv_doc_items'
        );

        //size_id
        $this->dropForeignKey(
            'fk-tikuv_doc_items-size_id',
            'tikuv_doc_items'
        );

        $this->dropIndex(
            'idx-tikuv_doc_items-size_id',
            'tikuv_doc_items'
        );
        $this->dropTable('{{%tikuv_doc_items}}');
    }
}
