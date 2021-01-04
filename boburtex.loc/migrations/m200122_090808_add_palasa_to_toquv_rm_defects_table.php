<?php

use yii\db\Migration;

/**
 * Class m200122_090808_add_palasa_to_toquv_rm_defects_table
 */
class m200122_090808_add_palasa_to_toquv_rm_defects_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->upsert('{{%toquv_rm_defects}}',['id'=>8,'name'=>'Palasa'],true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }
}
