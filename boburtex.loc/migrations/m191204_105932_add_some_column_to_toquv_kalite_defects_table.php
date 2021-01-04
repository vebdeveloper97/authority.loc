<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_kalite_defects}}`.
 */
class m191204_105932_add_some_column_to_toquv_kalite_defects_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_kalite_defects}}','metr', $this->float());
        $this->addColumn('{{%toquv_kalite_defects}}','from', $this->float());
        $this->addColumn('{{%toquv_kalite_defects}}','to', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_kalite_defects}}','metr');
        $this->dropColumn('{{%toquv_kalite_defects}}','from');
        $this->dropColumn('{{%toquv_kalite_defects}}','to');
    }
}
