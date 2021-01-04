<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_goods_doc_pack}}`.
 */
class m191105_084833_add_from_department_column_to_tikuv_goods_doc_pack_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tikuv_goods_doc_pack', 'from_department', $this->integer());

        // creates index for column `from_department`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc_pack-from_department}}',
            '{{%tikuv_goods_doc_pack}}',
            'from_department'
        );

        // add foreign key for table `{{%tikuv_goods_doc_pack}}`
        $this->addForeignKey(
            '{{%fk-tikuv_goods_doc_pack-from_department}}',
            '{{%tikuv_goods_doc_pack}}',
            'from_department',
            '{{%toquv_departments}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%from_department}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_goods_doc-from_department}}',
            '{{%tikuv_goods_doc}}'
        );

        // drops index for column `from_department`
        $this->dropIndex(
            '{{%idx-tikuv_goods_doc-from_department}}',
            '{{%tikuv_goods_doc}}'
        );
        $this->dropColumn('tikuv_goods_doc_pack', 'from_department');
    }
}
