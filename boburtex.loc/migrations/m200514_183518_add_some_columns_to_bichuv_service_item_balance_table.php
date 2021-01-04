<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_service_item_balance}}`.
 */
class m200514_183518_add_some_columns_to_bichuv_service_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_service_item_balance}}', 'from_department', $this->integer());
        $this->addColumn('{{%bichuv_service_item_balance}}', 'to_department', $this->integer());
        $this->addColumn('{{%bichuv_service_item_balance}}', 'from_musteri', $this->integer());
        $this->addColumn('{{%bichuv_service_item_balance}}', 'to_musteri', $this->integer());
        $this->addColumn('{{%bichuv_service_item_balance}}','percentage',$this->double());
        $this->addColumn('{{%bichuv_service_item_balance}}','type',$this->smallInteger()->defaultValue(1));
        $this->addColumn('{{%usluga_doc_items}}','percentage',$this->double());
        $this->addColumn('{{%tikuv_outcome_products_pack}}','bsib_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%bichuv_service_item_balance}}', 'from_department');
        $this->dropColumn('{{%bichuv_service_item_balance}}', 'to_department');
        $this->dropColumn('{{%bichuv_service_item_balance}}', 'from_musteri');
        $this->dropColumn('{{%bichuv_service_item_balance}}', 'to_musteri');
        $this->dropColumn('{{%bichuv_service_item_balance}}','percentage');
        $this->dropColumn('{{%bichuv_service_item_balance}}','type');
        $this->dropColumn('{{%usluga_doc_items}}','percentage');
        $this->dropColumn('{{%tikuv_outcome_products_pack}}','bsib_id');
    }
}
