<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%goods}}`.
 */
class m200511_083431_add_package_code_column_to_goods_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('goods','package_code', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('goods','package_code');
    }
}
