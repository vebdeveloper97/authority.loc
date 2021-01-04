<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_raw_material_ip}}`.
 */
class m190807_061347_add_percentage_column_to_toquv_raw_material_ip_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('toquv_raw_material_ip','percentage', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('toquv_raw_material_ip', 'percentage');
    }
}
