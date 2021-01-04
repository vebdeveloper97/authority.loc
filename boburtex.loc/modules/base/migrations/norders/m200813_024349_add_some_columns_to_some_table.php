<?php

use yii\db\Migration;

/**
 * Handles adding columns to table some`.
 */
class m200813_024349_add_some_columns_to_some_table extends Migration
{
    
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        
        $this->addColumn('{{%boyahane_siparis_musteri}}', 'model_order_id', $this->integer());
        $this->addColumn('{{%boyahane_siparis_musteri}}', 'toquv_order_id', $this->integer());
        $this->addColumn('{{%boyahane_siparis_musteri}}', 'toquv_document_id', $this->integer());
        
        $this->addColumn('{{%boyahane_siparis_part}}', 'color_pantone_id', $this->integer());
        $this->addColumn('{{%boyahane_siparis_part}}', 'model_list_id', $this->integer());
        $this->addColumn('{{%boyahane_siparis_part}}', 'model_order_item_id', $this->integer());
        
        $this->addColumn('{{%boyahane_siparis_subpart}}', 'toquv_doc_item_id', $this->integer());
        $this->addColumn('{{%boyahane_siparis_subpart}}', 'toquv_rm_order_id', $this->integer());
        $this->addColumn('{{%boyahane_siparis_subpart}}', 'toquv_raw_material_id', $this->integer());
        $this->addColumn('{{%boyahane_siparis_subpart}}', 'mato_info_id', $this->integer());
        $this->addColumn('{{%boyahane_siparis_subpart}}', 'sort', $this->integer());
        $this->addColumn('{{%boyahane_siparis_subpart}}', 'toquv_pus_fine_id', $this->integer());
        
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown() {
    
        $this->dropColumn('{{%boyahane_siparis_musteri}}', 'model_order_id');
        $this->dropColumn('{{%boyahane_siparis_musteri}}', 'toquv_order_id');
        $this->dropColumn('{{%boyahane_siparis_musteri}}', 'toquv_document_id');

        $this->dropColumn('{{%boyahane_siparis_part}}', 'color_pantone_id');
        $this->dropColumn('{{%boyahane_siparis_part}}', 'model_list_id');
        $this->dropColumn('{{%boyahane_siparis_part}}', 'model_order_item_id');

        $this->dropColumn('{{%boyahane_siparis_subpart}}', 'toquv_doc_item_id');
        $this->dropColumn('{{%boyahane_siparis_subpart}}', 'toquv_rm_order_id');
        $this->dropColumn('{{%boyahane_siparis_subpart}}', 'toquv_raw_material_id');
        $this->dropColumn('{{%boyahane_siparis_subpart}}', 'mato_info_id');
        $this->dropColumn('{{%boyahane_siparis_subpart}}', 'sort');
        $this->dropColumn('{{%boyahane_siparis_subpart}}', 'toquv_pus_fine_id');
        
    }
    
}
