<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_goods_doc_pack}}`.
 */
class m200509_051605_add_from_musteri_id_column_to_tikuv_goods_doc_pack_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_goods_doc_pack}}', 'from_musteri', $this->integer());
        $this->addColumn('{{%tikuv_goods_doc_pack}}', 'to_musteri', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tikuv_goods_doc_pack}}', 'from_musteri');
        $this->dropColumn('{{%tikuv_goods_doc_pack}}', 'to_musteri');
    }
}
