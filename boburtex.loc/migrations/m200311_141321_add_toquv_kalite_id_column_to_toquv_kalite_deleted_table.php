<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_kalite_deleted}}`.
 */
class m200311_141321_add_toquv_kalite_id_column_to_toquv_kalite_deleted_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_kalite_deleted}}', 'toquv_kalite_id', $this->integer());
        $this->addColumn('{{%toquv_kalite_deleted}}', 'info', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_kalite_deleted}}', 'toquv_kalite_id');
        $this->dropColumn('{{%toquv_kalite_deleted}}', 'info');
    }
}
