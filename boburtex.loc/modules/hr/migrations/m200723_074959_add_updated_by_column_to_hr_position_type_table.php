<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%hr_position_type}}`.
 */
class m200723_074959_add_updated_by_column_to_hr_position_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%hr_position_type}}', 'updated_by', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%hr_position_type}}', 'updated_by');
    }
}
