<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_acs}}`.
 */
class m200709_144805_add_updated_by_and_columns_to_bichuv_acs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_acs}}', 'updated_by', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%bichuv_acs}}', 'updated_by');
    }
}
