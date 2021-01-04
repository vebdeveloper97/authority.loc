<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_goods_doc_pack}}`.
 */
class m191113_155019_add_some_column_to_tikuv_goods_doc_pack_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tikuv_goods_doc_pack', 'is_full', $this->smallInteger()->defaultValue(1));
        $this->addColumn('tikuv_goods_doc','weight', $this->decimal(10,3));
        $this->addColumn('tikuv_goods_doc','unit_id', $this->integer());

        // creates index for column `unit_id`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc-unit_id}}',
            '{{%tikuv_goods_doc}}',
            'unit_id'
        );

        // add foreign key for table `{{%tikuv_goods_doc}}`
        $this->addForeignKey(
            '{{%fk-tikuv_goods_doc-unit_id}}',
            '{{%tikuv_goods_doc}}',
            'unit_id',
            '{{%unit}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%tikuv_goods_doc}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_goods_doc-unit_id}}',
            '{{%tikuv_goods_doc}}'
        );

        // drops index for column `unit_id`
        $this->dropIndex(
            '{{%idx-tikuv_goods_doc-unit_id}}',
            '{{%tikuv_goods_doc}}'
        );
        $this->dropColumn('tikuv_goods_doc','unit_id');
        $this->dropColumn('tikuv_goods_doc_pack','is_full');
        $this->dropColumn('tikuv_goods_doc','weight');
    }
}
