<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_document_items}}`.
 */
class m190806_094230_add_some_columns_to_toquv_document_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //add  created_by
        $this->addColumn("toquv_documents", "created_by", $this->integer());
        $this->addColumn("toquv_document_items", "created_by", $this->integer());
        $this->addColumn("toquv_departments", "created_by", $this->integer());
        $this->addColumn("toquv_document_items", "unit_id", $this->integer());

        //unit_id
        $this->createIndex(
            'idx-toquv_document_items-unit_id',
            'toquv_document_items',
            'unit_id'
        );

        $this->addForeignKey(
            'fk-toquv_document_items-unit_id',
            'toquv_document_items',
            'unit_id',
            'unit',
            'id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("toquv_documents", "created_by");
        $this->dropColumn("toquv_document_items", "created_by");
        $this->dropColumn("toquv_departments", "created_by");

        //unit_id
        $this->dropForeignKey(
            'fk-toquv_document_items-unit_id',
            'toquv_document_items'
        );

        $this->dropIndex(
            'idx-toquv_document_items-unit_id',
            'toquv_document_items'
        );

        $this->dropColumn("toquv_document_items", "unit_id");
    }
}
