<?php

use yii\db\Migration;

/**
 * Class m190826_050548_alter_toquv_item_balance_table
 */
class m190826_050548_alter_toquv_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('toquv_item_balance','count', $this->decimal(20, 3)->notNull());
        $this->alterColumn('toquv_item_balance','inventory', $this->decimal(20, 3)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }
}
