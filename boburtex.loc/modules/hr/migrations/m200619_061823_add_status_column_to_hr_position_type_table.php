<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%hr_position_type}}`.
 */
class m200619_061823_add_status_column_to_hr_position_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%hr_position_type}}', 'status', $this->smallInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%hr_position_type}}', 'status');
    }
}
