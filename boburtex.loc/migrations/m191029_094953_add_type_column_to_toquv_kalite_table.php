<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_kalite}}`.
 */
class m191029_094953_add_type_column_to_toquv_kalite_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_kalite}}', 'type', $this->smallInteger(2)->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_kalite}}', 'type');
    }
}
