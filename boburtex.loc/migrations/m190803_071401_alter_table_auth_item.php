<?php

use yii\db\Migration;

/**
 * Class m190803_071401_alter_table_auth_item
 */
class m190803_071401_alter_table_auth_item extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('auth_item', 'category', $this->string(64));
    }

    public function down()
    {
        echo "m190803_071401_alter_table_auth_item cannot be reverted.\n";

        return false;
    }
}
