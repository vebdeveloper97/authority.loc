<?php

use yii\db\Migration;

/**
 * Class m191023_094838_add_column_to_table_goods_table
 */
class m191023_094838_add_column_to_table_goods_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn( '{{%goods}}', 'desc1',$this->string()->defaultValue('Asosiy'));
        $this->addColumn( '{{%goods}}', 'desc2',$this->string());
        $this->addColumn( '{{%goods}}', 'desc3',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn( '{{%goods}}','desc1');
        $this->dropColumn( '{{%goods}}','desc2');
        $this->dropColumn( '{{%goods}}','desc3');
    }
}
