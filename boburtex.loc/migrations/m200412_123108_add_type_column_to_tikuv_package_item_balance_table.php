<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_package_item_balance}}`.
 */
class m200412_123108_add_type_column_to_tikuv_package_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tikuv_package_item_balance','brand_type',$this->smallInteger(1)->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tikuv_package_item_balance','brand_type');
    }
}
