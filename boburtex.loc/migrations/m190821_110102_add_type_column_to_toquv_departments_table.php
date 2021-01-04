<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_departments}}`.
 */
class m190821_110102_add_type_column_to_toquv_departments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_departments}}', 'type', $this->smallInteger(2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_departments}}', 'type');
    }
}
