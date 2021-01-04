<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%mobile_process}}`.
 */
class m200828_064109_add_type_column_to_mobile_process_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%mobile_process}}', 'type', $this->integer(2)->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%mobile_process}}', 'type');
    }
}
