<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_makine}}`.
 */
class m191003_163912_add_some_column_to_toquv_makine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_makine}}', 'm_code', $this->integer()->after('id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_makine}}', 'm_code');
    }
}
