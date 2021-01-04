<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_rm_order}}`.
 */
class m200419_120626_add_pus_fine_id_column_to_toquv_rm_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_rm_order}}', 'toquv_pus_fine_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_rm_order}}', 'toquv_pus_fine_id');
    }
}
