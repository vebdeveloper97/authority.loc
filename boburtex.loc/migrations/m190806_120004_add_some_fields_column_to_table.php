<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%}}`.
 */
class m190806_120004_add_some_fields_column_to_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('toquv_document_items','quantity',$this->decimal(20,3));
        $this->addColumn('toquv_document_items','document_qty',$this->decimal(20,3));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('toquv_docuemnt_items','quantity', $this->integer());
        $this->dropColumn('toquv_docuemnt_items','document_qty');
    }
}
