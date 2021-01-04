<?php

use yii\db\Migration;

/**
 * Class m190819_164738_insert_pb_data
 */
class m190819_164738_insert_pb_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->upsert('pul_birligi', ['id'=>1, 'name' => "So'm",   'created_by' => 1],true);
        $this->upsert('pul_birligi', ['id'=>2, 'name' => "Dollar", 'created_by' => 1],true);
        $this->upsert('pul_birligi', ['id'=>3, 'name' => "Rubl",   'created_by' => 1],true);
        $this->upsert('pul_birligi', ['id'=>4, 'name' => "Evro",   'created_by' => 1],true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return null;
    }

}
