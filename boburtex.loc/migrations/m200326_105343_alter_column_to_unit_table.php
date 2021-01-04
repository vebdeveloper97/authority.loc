<?php

use yii\db\Migration;

/**
 * Class m200326_105343_alter_column_to_unit_table
 */
class m200326_105343_alter_column_to_unit_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->upsert('unit', ['name' => 'metr', 'code' => 'METR']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('unit', ['code' => 'METR']);
    }
}
