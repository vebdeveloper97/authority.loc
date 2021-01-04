<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_kalite}}`.
 */
class m200124_140159_add_some_column_to_toquv_kalite_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_kalite}}', 'send_date', $this->dateTime());
        $this->addColumn('{{%toquv_kalite}}', 'send_user_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_kalite}}', 'send_date');
        $this->dropColumn('{{%toquv_kalite}}', 'send_user_id');
    }
}
