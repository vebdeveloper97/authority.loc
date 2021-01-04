<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_rm_order}}`.
 */
class m200113_061001_add_some_column_to_toquv_rm_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_rm_order}}','color_pantone_id', $this->integer());
        $this->addColumn('{{%toquv_rm_order}}','model_musteri_id', $this->integer());
        $this->addColumn('{{%toquv_orders}}','model_musteri_id', $this->integer());
        $this->addColumn('{{%toquv_rm_order}}','model_code', $this->string(50));
        $this->addColumn('{{%toquv_kalite}}','user_kalite_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_rm_order}}','color_pantone_id');
        $this->dropColumn('{{%toquv_rm_order}}','model_musteri_id');
        $this->dropColumn('{{%toquv_orders}}','model_musteri_id');
        $this->dropColumn('{{%toquv_rm_order}}','model_code');
        $this->dropColumn('{{%toquv_kalite}}','user_kalite_id');
    }
}
