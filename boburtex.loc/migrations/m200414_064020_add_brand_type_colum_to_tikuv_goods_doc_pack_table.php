<?php

use yii\db\Migration;

/**
 * Class m200414_064020_add_brand_type_colum_to_tikuv_goods_doc_pack_table
 */
class m200414_064020_add_brand_type_colum_to_tikuv_goods_doc_pack_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tikuv_goods_doc_pack','brand_type', $this->smallInteger(1)->defaultValue(1));
        $this->addColumn('tikuv_goods_doc_pack','brand_id', $this->integer());

        // creates index for column `brand_id`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc_pack-brand_id}}',
            '{{%tikuv_goods_doc_pack}}',
            'brand_id'
        );

        // add foreign key for table `{{%brand_id}}`
        $this->addForeignKey(
            '{{%fk-tikuv_goods_doc_pack-brand_id}}',
            '{{%tikuv_goods_doc_pack}}',
            'brand_id',
            '{{%brend}}',
            'id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%brand_id}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_goods_doc_pack-brand_id}}',
            '{{%tikuv_goods_doc_pack}}'
        );

        // drops index for column `brand_id`
        $this->dropIndex(
            '{{%idx-tikuv_goods_doc_pack-brand_id}}',
            '{{%tikuv_goods_doc_pack}}'
        );

        $this->dropColumn('tikuv_goods_doc_pack','brand_type');
        $this->dropColumn('tikuv_goods_doc_pack','brand_id');
    }


}
