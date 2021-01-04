<?php

use yii\db\Migration;

/**
 * Class m190825_144416_alter_bichuv_item_balance
 */
class m190825_144416_alter_bichuv_item_balance extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('bichuv_item_balance','count', $this->decimal(20, 3)->notNull());
        $this->alterColumn('bichuv_item_balance','inventory', $this->decimal(20, 3)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }

}
