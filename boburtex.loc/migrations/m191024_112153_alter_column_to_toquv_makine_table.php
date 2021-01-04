<?php

use yii\db\Migration;

/**
 * Class m191024_112153_alter_column_to_toquv_makine_table
 */
class m191024_112153_alter_column_to_toquv_makine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('toquv_makine','m_code', $this->string(10));
        $this->alterColumn('toquv_makine','working_user_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('toquv_makine','m_code', $this->integer());
        $this->alterColumn('toquv_makine','working_user_id', $this->integer()->notNull());
    }
}
